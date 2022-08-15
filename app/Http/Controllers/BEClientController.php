<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use \Session;

use App\Api\Core;

use App\User;

use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportClient;

use App\Model\Log;
use App\Model\UserLevel;
use App\Model\File;
use App\Model\UserBlock;

class BEClientController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();

        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            //
            if (
                $this->_viewer &&
                ($this->_viewer->isDeleted() || $this->_viewer->isBlocked() || !$this->_viewer->isStaff())
            ) {
                return redirect('/invalid');
            }

            return $next($request);
        });

        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!$this->_viewer->isAllowed("client_view")) {
            return redirect('/private');
        }
        $params = $request->all();

        $values = [
            'page_title' => 'Danh Sách Khách Hàng',

            'params' => $params,
        ];

        //user
        $select = User::query("users")
            ->select('users.*')
            ->leftJoin('user_blocks', 'user_blocks.user_id', '=', 'users.id')
            ->where("users.deleted", 0)
            ->where("users.level_id", 4);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (
                isset($params['keyword']) && isset($params['filter'])
            ) {
                $filter = trim($params['filter']);
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("users.name", "LIKE", $search);
                } elseif ($filter == "phone") {
                    $select->where("users.phone", "LIKE", $search);
                } elseif ($filter == "email") {
                    $select->where("users.email", "LIKE", $search);
                }
            }

            if (isset($params['level']) && !empty($params['level']) && (int)$params['level']) {
                $select->where("users.level_id", "=", (int)$params['level']);
            }

            if (isset($params['blocked']) && !empty($params['blocked']) && (int)$params['blocked']) {
                if ((int)$params['blocked'] == 1) {
                    $select->where("user_blocks.user_id", '>', 0);
                } else {
                    $select->where("user_blocks.user_id", '=', NULL);
                }
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        if ($order == "alphabet") {
            $select->orderByRaw("TRIM(LOWER(users.name)) {$orderBy}");
        } else {
            $select->orderBy("users.{$order}", $orderBy);
        }

        $values['items'] = $select->paginate(20);

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.clients.index", $values);
    }

    public function add(Request $request)
    {
        if (!$this->_viewer->isAllowed('client_add')) {
            return redirect('/private');
        }
        //ko mo sua khach hang

        $params = $request->all();
        $pageTitle = 'Tạo Khách Hàng';
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;

        $user = User::find($itemId);
        if ($user) {
            $pageTitle = 'Sửa Thông Tin Khách Hàng';
        }

        $values = [
            'page_title' => $pageTitle,

            'user' => $user,
        ];

        return view("pages.back_end.clients.add", $values);
    }

    public function save(Request $request)
    {
        if (!$this->_viewer->isAllowed('client_add')) {
            return redirect('/private');
        }

        if (!count($request->post())) {
            return redirect('/admin/clients');
        }
        $values = $request->post();
        //        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($values['name']);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('user', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $user = User::find($itemId);
        if ($user) {
        } else {
            //check again
            $row1 = User::select()
                ->where('deleted', 0)
                ->where('email', $this->_apiCore->cleanStr($values['email']))
                ->first();
            $row2 = User::select()
                ->where('deleted', 0)
                ->where('phone', $this->_apiCore->cleanStr($values['phone']))
                ->first(); //tam thoi ko bat phone
            if ($row1) {
                return redirect('/admin/staffs');
            }

            if (!$this->_viewer->isAllowed("client_add")) {
                return redirect('/private');
            }

            $values['password'] = Hash::make("kh123456");
            $values['level_id'] = 4;

            $user = User::create($values);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'client_add',
                'item_id' => $user->id,
                'item_type' => 'user',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            $user->removeAvatar();

            $imageName = 'user_avatar_' . $user->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/user/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/user/'), $imageName);
            $user->uploadAvatar($imageName, $imagePath);
        }

        return redirect('/admin/clients');
    }

    public function changePassword(Request $request)
    {
        $values = $request->post();
        //        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $user = $this->_viewer;
        if ($itemId) {
            $temp = User::find($itemId);
            if ($temp) {
                $user = $temp;
            }
        }

        if ($user && !empty($values['pwd'])) {
            $user->update([
                'password' => Hash::make($values['pwd'])
            ]);

            //log
            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_update_password',
                'item_id' => $user->id,
                'item_type' => 'user',
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return redirect('/admin/clients');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed("staff_client_delete")) {
            return response()->json([]);
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $user = User::find($itemId);
        if ($user && !$user->fullPermissions() && $user->isCustomer()) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_delete',
                'item_id' => $user->id,
                'item_type' => 'user',
            ]);

            $user->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    //excel
    public function exportItem(Request $request)
    {
        if (!$this->_viewer->isAllowed("client_excel_export")) {
            return redirect('/private');
        }

        $params = $request->all();
        //        echo '<pre>';var_dump($params);die;

        $select = User::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);

                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("name", "LIKE", $search);
                } elseif ($filter == "phone") {
                    $select->where("phone", "LIKE", $search);
                } elseif ($filter == "email") {
                    $select->where("cong_dung_text", "LIKE", $search);
                }
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        $select->where("level_id", 4);
        $select->where("deleted", 0);

        if ($order == "alphabet") {
            $select->orderByRaw("TRIM(LOWER(title)) {$orderBy}");
        } else {
            $select->orderBy("{$order}", $orderBy);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'client_excel_export',
            'item_id' => 0,
            'item_type' => 'product',
        ]);

        $excel = new ExportClient();
        $excel->setItems($select->get());
        return Excel::download($excel, 'export_client.xlsx');
    }
}

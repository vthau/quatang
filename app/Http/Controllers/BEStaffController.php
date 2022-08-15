<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use \Session;

use App\Api\Core;

use App\User;

use App\Model\UserView;
use App\Model\UserLevel;
use App\Model\UserCart;
use App\Model\UserBlock;
use App\Model\UserSupplier;

class BEStaffController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();

        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            //
            if ($this->_viewer &&
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
        if (!$this->_viewer->isAllowed("staff_user_view")) {
            return redirect('/private');
        }
        $params = $request->all();

        $values = [
            'page_title' => 'Danh Sách Nhân Viên',

            'params' => $params,
        ];

        //level
        $temp = [];
        $select = UserLevel::whereNotIn('id', [1, 2, 4]);
        foreach ($select->get() as $ite) {
            $temp[$ite->id] = $ite->title;
        }
        $values['levels'] = $temp;

        //user
        $select = User::query('users')
            ->select('users.*')
            ->leftJoin('user_blocks', 'user_blocks.user_id', '=', 'users.id')
            ->whereNotIn('users.level_id', [1, 2, 4])
            ->where('users.deleted', 0)
        ;

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])
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
                    $select->where("user_blocks.user_id", NULL);
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

        $users = $select->paginate(10);

        $values['items'] = $users;

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.staffs.index", $values);
    }

    public function add(Request $request)
    {
        $params = $request->all();
        $pageTitle = 'Tạo Nhân Viên';
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $user = User::find($itemId);
        if ($user) {
            $pageTitle = 'Sửa Thông Tin Nhân Viên';

            if (!$this->_viewer->isAllowed("staff_user_edit")) {
                return redirect('/private');
            }

            if ($user->id <= 2) {
                return redirect('/private');
            }
        } else {
            if (!$this->_viewer->isAllowed("staff_user_add")) {
                return redirect('/private');
            }
        }

        $values = [
            'page_title' => $pageTitle,
        ];

        $levelId = 3;

        //level
        $temp = [];
        $select = UserLevel::whereNotIn('id', [1, 2, 4]);
        foreach ($select->get() as $ite) {
            $temp[$ite->id] = $ite->title;
        }
        $values['levels'] = $temp;

        if ($user) {
            $levelId = (int)$user->level_id;
        }
        $values['item'] = $user;
        $values['level_id'] = $levelId;

        //brand
        $select = UserSupplier::where('deleted', 0);
        $values['suppliers'] = $select->get();

        return view("pages.back_end.staffs.add", $values);
    }

    public function checkInfo(Request $request)
    {
        $values = $request->post();
        $email = (isset($values['email'])) ? $this->_apiCore->cleanStr($values['email']) : NULL;
        $phone = (isset($values['phone'])) ? $this->_apiCore->cleanStr($values['phone']) : NULL;
        $itemId = (isset($values['id'])) ? (int)$values['id'] : 0;

        $select1 = User::where('deleted', 0)
            ->where("email", $email);
        if ($itemId) {
            $select1->where("id", "<>", $itemId);
        }
        $valid1 = !count($select1->get());

        $select2 = User::where('deleted', 0)
            ->where("phone", $phone);
        if ($itemId) {
            $select2->where("id", "<>", $itemId);
        }
        $valid2 = !count($select2->get());

        $valid3 = true;
        if (isset($values['partner_code']) && !empty($values['partner_code'])
            && isset($values['partner_donvi']) && !empty($values['partner_donvi'])
        ) {
            if ($values['partner_donvi'] == 'to_chuc') {
                $select3 = User::where('deleted', 0)
                    ->where('ref_code', strtoupper($this->_apiCore->cleanStr($values['partner_code'])));
                if ($itemId) {
                    $select3->where("id", "<>", $itemId);
                }
                $valid3 = !count($select3->get());
            }
        }

        return response()->json([
            'VALID_EMAIL' => $valid1,
            'VALID_PHONE' => true, //tam thoi ko bat phone //$valid2
            'VALID_REFCODE' => true, //$valid3,
        ]);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('staff_user_add') || $this->_viewer->isAllowed('staff_user_edit'))
        ) {
            return redirect('/private');
        }

        if (!count($request->post())) {
            return redirect('/admin/staffs');
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
            if (!$this->_viewer->isAllowed("staff_user_edit")) {
                return redirect('/private');
            }

            unset($values['item_id']);
            $itemOLD = (array)$user->toArray();

            if ($values['level_id'] != 5) {
                $values['supplier_id'] = 0;
            }

            $user->update($values);

            $user = User::find($user->id);
            $itemNEW = (array)$user->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_edit',
                'item_id' => $user->id,
                'item_type' => 'user',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
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

            if (!$this->_viewer->isAllowed("staff_user_add")) {
                return redirect('/private');
            }

            $values['password'] = Hash::make("nv123456");

            $user = User::create($values);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_add',
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

        return redirect('/admin/staffs');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed("staff_user_delete")) {
            return response()->json([]);
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $user = User::find($itemId);
        if ($user && !$user->fullPermissions() && $user->isStaff()) {

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

    public function block(Request $request)
    {
        $values = $request->post();
        $blocked = (isset($values['block'])) ? (int)$values['block'] : 0;
        $reason = (isset($values['reason'])) ? $this->_apiCore->cleanStr($values['reason']) : '';
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $user = User::find($itemId);

        if (!$this->_viewer->isAllowed("staff_user_block") && $blocked) {
            return response()->json([]);
        }

        if (!$this->_viewer->isAllowed("staff_user_unblock") && !$blocked) {
            return response()->json([]);
        }

        if ($user && $user->isSuperAdmin()) {
            return response()->json([]);
        }

        if ($user) {
            if ($blocked) {
                UserBlock::create([
                    'user_id' => $user->id,
                    'reason' => $reason,
                    'time_block' => date("Y-m-d H:i:s"),
                ]);
            } else {
                UserBlock::where('user_id', $user->id)
                    ->delete();
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => ($blocked) ? 'user_block' : 'user_unblock',
                'item_id' => $user->id,
                'item_type' => 'user',
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }

    //profile
    public function account()
    {
        $user = $this->_viewer;

        $values = [
            'page_title' => 'Thông Tin Cá Nhân',

            'user' => $user,
        ];

        $selectCart = UserCart::where('deleted', 0)
            ->where('user_id', $user->id)
            ->where('status', '<>', 'moi_tao')
            ->orderByDesc('id');

        $selectSum = clone $selectCart;

        $values['carts'] = $selectCart->get();

        $selectView = UserView::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(16);
        $values['views'] = $selectView->get();

        //sum
        $totalCart = $selectSum->sum("total_cart");
        $values['totalCart'] = $totalCart;
        $totalCart = $selectSum->sum("total_discount");
        $values['totalDiscount'] = $totalCart;
        $totalCart = $selectSum->sum("total_price");
        $values['totalPrice'] = $totalCart;

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.staffs.user", $values);
    }

    public function info($href, Request $request)
    {
        $params = $request->all();
        $user = User::where("href", "=", $href)->first();
        if (!$user
            || ($user && !$user->id)
            || ($user && $user->isDeleted() && !$this->_viewer->isSuperAdmin())
            || ($user && $user->fullPermissions())
        ) {
            return redirect('/private');
        }

        if ($user->id == $this->_viewer->id) {
            return redirect('/admin/account');
        }

        $pageTitle = 'Thông Tin Nhân Viên';
        if ($user->isCustomer()) {
            $pageTitle = 'Thông Tin Khách Hàng';
        }
        $values = [
            'page_title' => $pageTitle,

            'user' => $user,
        ];

        $selectCart = UserCart::where('deleted', 0)
            ->where('user_id', $user->id)
            ->where('status', '<>', 'moi_tao')
            ->orderByDesc('id');

        $selectSum = clone $selectCart;

        $values['carts'] = $selectCart->get();

        $selectView = UserView::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(16);
        $values['views'] = $selectView->get();

        //sum
        $totalCart = $selectSum->sum("total_cart");
        $values['totalCart'] = $totalCart;
        $totalCart = $selectSum->sum("total_discount");
        $values['totalDiscount'] = $totalCart;
        $totalCart = $selectSum->sum("total_price");
        $values['totalPrice'] = $totalCart;

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.staffs.user", $values);
    }

    public function update(Request $request)
    {
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        unset($values['_token']);

        $user = $this->_viewer;
        $temp = User::find($itemId);
        if ($temp) {
            $user = $temp;
        }
        $itemOLD = (array)$user->toArray();

        $title = $this->_apiCore->stripVN($values['name']);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);

        $values['href'] = $this->_apiCore->generateHref('user', array(
            'id' => $user->id,
            'name' => $title,
        ));

        $user->update($values);

        $user = User::find($user->id);
        $itemNEW = (array)$user->toArray();

        //log
        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'user_update_info',
            'item_id' => $user->id,
            'item_type' => 'user',
            'params' => json_encode([
                'item_old' => $itemOLD,
                'item_new' => $itemNEW,
            ])
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        if ($this->_viewer->id == $user->id) {
            return redirect('/admin/account');
        }

        return redirect('/admin/profile/' . $user->href);
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

            if ($this->_viewer->id == $user->id) {
                return redirect('/admin/account');
            }

            return redirect('/admin/profile/' . $user->href);
        }

        return redirect('/private');
    }

    public function uploadAvatar(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $user = $this->_viewer;
        $temp = User::find($itemId);
        if ($temp) {
            $user = $temp;
        }

        foreach ($request->photos as $photo) {
            if ($photo) {
                $user->removeAvatar();

                $imageName = 'user_avatar_' . $user->id . '.' . $photo->getClientOriginalExtension();
                $imagePath = "/uploaded/user/" . $imageName;
                $photo->move(public_path('/uploaded/user/'), $imageName);
                $user->uploadAvatar($imageName, $imagePath);

                //log
                $this->_apiCore->addLog([
                    'user_id' => $this->_viewer->id,
                    'action' => 'user_update_avatar',
                    'item_id' => $user->id,
                    'item_type' => 'user',
                ]);
            }
        }

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return response()->json([]);
    }

}

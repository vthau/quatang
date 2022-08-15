<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\UserSupplier;

class BESupplierController extends Controller
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
        if (!$this->_viewer->isAllowed('user_supplier_view')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Nhà Cung Cấp',

            'params' => $params,
        ];

        $select = UserSupplier::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword'])) {
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                $select->where("title", "LIKE", $search);
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    case 'view_count':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        if ($order == "alphabet") {
            $select->orderByRaw("TRIM(LOWER(title)) {$orderBy}");
        } else {
            $select->orderBy("{$order}", $orderBy);
        }

        $values['items'] = $select->get();

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.suppliers.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('user_supplier_add') || $this->_viewer->isAllowed('user_supplier_edit'))
        ) {
            return redirect('/private');
        }
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($itemTitle);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('user_supplier', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $supplier = UserSupplier::find($itemId);
        if ($supplier) {
            if (!$this->_viewer->isAllowed('user_supplier_edit')) {
                return redirect('/private');
            }

            $itemOLD = (array)$supplier->toArray();

            $supplier->update([
                'title' => $itemTitle,
                'href' => $values['href'],
            ]);

            $supplier = UserSupplier::find($supplier->id);
            $itemNEW = (array)$supplier->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_supplier_edit',
                'item_id' => $supplier->id,
                'item_type' => 'user_supplier',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('user_supplier_add')) {
                return redirect('/private');
            }

            $supplier = UserSupplier::create([
                'title' => $itemTitle,
                'href' => $values['href'],
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_supplier_add',
                'item_id' => $supplier->id,
                'item_type' => 'user_supplier',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            //remove old
            $supplier->removeAvatar();

            $imageName = 'supplier_logo_' . $supplier->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/user_supplier/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/user_supplier/'), $imageName);
            $supplier->uploadAvatar($imageName, $imagePath);
        }

        //banner
        if (!empty($request->file('banner'))) {
            //remove old
            $supplier->removeBanner();

            $imageName = 'supplier_banner_' . $supplier->id . '.' . $request->file('banner')->getClientOriginalExtension();
            $imagePath = "/uploaded/user_supplier/" . $imageName;
            $request->file('banner')->move(public_path('/uploaded/user_supplier/'), $imageName);
            $supplier->uploadBanner($imageName, $imagePath);
        }

        return redirect('/admin/suppliers');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('user_supplier_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $supplier = UserSupplier::find($itemId);
        if ($supplier) {
            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_supplier_delete',
                'item_id' => $supplier->id,
                'item_type' => 'user_supplier',
            ]);

            $supplier->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

}

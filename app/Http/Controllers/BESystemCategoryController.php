<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\SystemCategory;

class BESystemCategoryController extends Controller
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
        if (!$this->_viewer->isAllowed('system_category_view')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Nhóm Chủ Đề',

            'params' => $params,
        ];

        $select = SystemCategory::where('deleted', 0);

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

        $values['items'] = $select->paginate(999);

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.system_categories.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('system_category_add') || $this->_viewer->isAllowed('system_category_edit'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;

        unset($values['_token']);

        $sysCategory = SystemCategory::find($itemId);
        if ($sysCategory) {
            if (!$this->_viewer->isAllowed('system_category_add')) {
                return redirect('/private');
            }

            $itemOLD = (array)$sysCategory->toArray();

            $sysCategory->update([
                'title' => $itemTitle,
            ]);

            $sysCategory = SystemCategory::find($sysCategory->id);
            $itemNEW = (array)$sysCategory->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'system_category_edit',
                'item_id' => $sysCategory->id,
                'item_type' => 'system_category',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('system_category_edit')) {
                return redirect('/private');
            }

            $sysCategory = SystemCategory::create([
                'title' => $itemTitle,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'system_category_add',
                'item_id' => $sysCategory->id,
                'item_type' => 'system_category',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        return redirect('/admin/system-categories');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('system_category_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $sysCategory = SystemCategory::find($itemId);
        if ($sysCategory) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'system_category_delete',
                'item_id' => $sysCategory->id,
                'item_type' => 'system_category',
            ]);

            $sysCategory->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('system_category_edit')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;

        $sysCategory = SystemCategory::find($itemId);
        if ($sysCategory) {
            $sysCategory->update([
                'active' => $value,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'system_category_update',
                'item_id' => $sysCategory->id,
                'item_type' => 'system_category',
                'params' => json_encode([
                    'type' => 'active',
                    'value' => $value,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }
}

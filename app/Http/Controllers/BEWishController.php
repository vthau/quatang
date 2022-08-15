<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\SystemCategory;
use App\Model\Wish;

class BEWishController extends Controller
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
        if (!$this->_viewer->isAllowed('wish_view')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Câu Chúc',

            'params' => $params,
        ];

        $select = Wish::where('deleted', 0);

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

        //system categories
        $select = SystemCategory::where('deleted', 0)
            ->orderByRaw('TRIM(LOWER(title))');
        $values['categories'] = $select->get();

        return view("pages.back_end.wishes.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('wish_add') || $this->_viewer->isAllowed('wish_edit'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $cateId = (isset($values['system_category_id'])) ? (int)$values['system_category_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;

        unset($values['_token']);

        $wish = Wish::find($itemId);
        if ($wish) {
            if (!$this->_viewer->isAllowed('wish_edit')) {
                return redirect('/private');
            }

            $itemOLD = (array)$wish->toArray();

            $wish->update([
                'title' => $itemTitle,
                'system_category_id' => $cateId,
            ]);

            $wish = Wish::find($wish->id);
            $itemNEW = (array)$wish->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'wish_edit',
                'item_id' => $wish->id,
                'item_type' => 'wish',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('wish_add')) {
                return redirect('/private');
            }

            $wish = Wish::create([
                'title' => $itemTitle,
                'system_category_id' => $cateId,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'wish_add',
                'item_id' => $wish->id,
                'item_type' => 'wish',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        return redirect('/admin/wishes');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('wish_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $wish = Wish::find($itemId);
        if ($wish) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'wish_delete',
                'item_id' => $wish->id,
                'item_type' => 'wish',
            ]);

            $wish->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('wish_edit')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $column = (isset($values['column'])) ? $this->_apiCore->cleanStr($values['column']) : NULL;

        $wish = Wish::find($itemId);
        if ($wish && !empty($column)) {

            switch ($column) {
                case 'active':
                    $wish->update([
                        'active' => $value,
                    ]);
                    break;

                case 'category':
                    $wish->update([
                        'system_category_id' => $value,
                    ]);
                    break;
            }


            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'wish_update',
                'item_id' => $wish->id,
                'item_type' => 'wish',
                'params' => json_encode([
                    'type' => $column,
                    'value' => $value,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }
}

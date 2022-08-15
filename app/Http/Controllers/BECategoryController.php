<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\ProductCategory;

class BECategoryController extends Controller
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
        if (!$this->_viewer->isAllowed('product_category_view')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Nhóm Sản Phẩm',

            'params' => $params,
        ];

        $select = ProductCategory::where('level', 1)
            ->where('parent_id', 0)
            ->where('deleted', 0)
        ;

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword'])) {
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                $select->where(function ($query) use ($search) {
                    $query->where("title", "LIKE", $search)
                        ->orWhereIn("id", function ($q1) use ($search) {
                            $q1->select('parent_id')
                                ->from('product_categories')
                                ->where('deleted', 0)
                                ->where('level', 2)
                                ->where('title', 'LIKE', $search);
                        })
                        ->orWhereIn("id", function ($q1) use ($search) {
                            $q1->select('parent_id')
                                ->from('product_categories')
                                ->where('deleted', 0)
                                ->where('level', 2)
                                ->whereIn('id', function ($q2) use ($search) {
                                    $q2->select('parent_id')
                                        ->from('product_categories')
                                        ->where('deleted', 0)
                                        ->where('level', 3)
                                        ->where('title', 'LIKE', $search);
                                });
                        })
                    ;
                });
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

        return view("pages.back_end.categories.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('product_category_add') || $this->_viewer->isAllowed('product_category_edit'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $parentId = (isset($values['parent_id'])) ? (int)$values['parent_id'] : 0;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($itemTitle);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('product_category', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $category = ProductCategory::find($itemId);
        if ($category && $category->id) {
            if (!$this->_viewer->isAllowed('product_category_edit')) {
                return redirect('/private');
            }

            $itemOLD = (array)$category->toArray();

            $category->update([
                'title' => $itemTitle,
                'href' => $values['href'],
            ]);

            $category = ProductCategory::find($category->id);
            $itemNEW = (array)$category->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_category_edit',
                'item_id' => $category->id,
                'item_type' => 'product_category',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('product_category_add')) {
                return redirect('/private');
            }

            $level = 1;
            $sort = 99;
            if ($parentId) {
                $level += 1;
                $parent = ProductCategory::find($parentId);
                if ($parent && $parent->parent_id) {
                    $level += 1;

                    $sort = count($parent->getSubCategories()) + 1;
                }
            }

            $category = ProductCategory::create([
                'title' => $itemTitle,
                'href' => $values['href'],
                'parent_id' => $parentId,
                'level' => $level,
                'sort' => $sort,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_category_add',
                'item_id' => $category->id,
                'item_type' => 'product_category',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //banner
        if (!empty($request->file('banner'))) {
            //remove old
            $category->removeBanner();

            $imageName = 'product_category_banner_' . $category->id . '.' . $request->file('banner')->getClientOriginalExtension();
            $imagePath = "/uploaded/product_category/" . $imageName;
            $request->file('banner')->move(public_path('/uploaded/product_category/'), $imageName);
            $category->uploadBanner($imageName, $imagePath);
        }

        //banner mobi
        if (!empty($request->file('banner-mobi'))) {
            //remove old
            $category->removeBannerMobi();

            $imageName = 'product_category_banner_mobi_' . $category->id . '.' . $request->file('banner-mobi')->getClientOriginalExtension();
            $imagePath = "/uploaded/product_category/" . $imageName;
            $request->file('banner-mobi')->move(public_path('/uploaded/product_category/'), $imageName);
            $category->uploadBannerMobi($imageName, $imagePath);
        }

        return redirect('/admin/product-categories');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('product_category_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $category = ProductCategory::find($itemId);
        if ($category) {
            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_category_delete',
                'item_id' => $category->id,
                'item_type' => 'product_category',
            ]);

            $category->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

}

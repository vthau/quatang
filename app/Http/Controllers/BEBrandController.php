<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\UserSupplier;
use App\Model\ProductBrand;

class BEBrandController extends Controller
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
        if (!($this->_viewer->isAllowed('product_brand_view') || $this->_viewer->isSupplier())) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Thương Hiệu',

            'params' => $params,
        ];

        $select = ProductBrand::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword'])) {
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                $select->where("title", "LIKE", $search);
            }

            if (isset($params['supplier_id']) && !empty($params['supplier_id'])) {
                if ($params['supplier_id'] == 'he_thong') {
                    $select->where('supplier_id', 0);
                } else {
                    $select->where('supplier_id', (int)$params['supplier_id']);
                }
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

        $values['items'] = $select->paginate(20);

        //supplier
        $select = UserSupplier::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where('id', $this->_viewer->supplier_id);
        }
        $values['suppliers'] = $select->get();

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.brands.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('product_brand_add') || $this->_viewer->isAllowed('product_brand_edit') || $this->_viewer->isSupplier())
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
        $values['href'] = $this->_apiCore->generateHref('product_brand', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $brand = ProductBrand::find($itemId);
        if ($brand) {
            $canEditDelete = $this->_viewer->isSupplier() && $this->_viewer->supplier_id == $brand->supplier_id;

            if (!($this->_viewer->isAllowed('product_brand_edit') || $canEditDelete)) {
                return redirect('/private');
            }

            $itemOLD = (array)$brand->toArray();

            $brand->update([
                'title' => $itemTitle,
                'href' => $values['href'],
            ]);

            $brand = ProductBrand::find($brand->id);
            $itemNEW = (array)$brand->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_brand_edit',
                'item_id' => $brand->id,
                'item_type' => 'product_brand',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!($this->_viewer->isAllowed('product_brand_add') || $this->_viewer->isSupplier())) {
                return redirect('/private');
            }

            $brand = ProductBrand::create([
                'title' => $itemTitle,
                'href' => $values['href'],
            ]);

            if ($this->_viewer->supplier_id) {
                $brand->update([
                    'supplier_id' => $this->_viewer->supplier_id,
                ]);
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_brand_add',
                'item_id' => $brand->id,
                'item_type' => 'product_brand',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            //remove old
            $brand->removeAvatar();

            $imageName = 'brand_logo_' . $brand->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/product_brand/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/product_brand/'), $imageName);
            $brand->uploadAvatar($imageName, $imagePath);
        }

        //banner
        if (!empty($request->file('banner'))) {
            //remove old
            $brand->removeBanner();

            $imageName = 'brand_banner_' . $brand->id . '.' . $request->file('banner')->getClientOriginalExtension();
            $imagePath = "/uploaded/product_brand/" . $imageName;
            $request->file('banner')->move(public_path('/uploaded/product_brand/'), $imageName);
            $brand->uploadBanner($imageName, $imagePath);
        }

        return redirect('/admin/product-brands');
    }

    public function delete(Request $request)
    {
        if (!($this->_viewer->isAllowed('product_brand_delete') || $this->_viewer->isSupplier())) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $brand = ProductBrand::find($itemId);
        if ($brand) {
            $canEditDelete = $this->_viewer->isSupplier() && $this->_viewer->supplier_id == $brand->supplier_id;
            if (!($this->_viewer->isAllowed('product_brand_delete') || $canEditDelete)) {
                return response()->json([]);
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_brand_delete',
                'item_id' => $brand->id,
                'item_type' => 'product_brand',
            ]);

            $brand->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('product_brand_edit')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;

        $brand = ProductBrand::find($itemId);
        if ($brand) {
            $brand->update([
                'active' => $value,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_brand_update',
                'item_id' => $brand->id,
                'item_type' => 'product_brand',
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

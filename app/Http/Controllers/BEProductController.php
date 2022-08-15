<?php

namespace App\Http\Controllers;

use App\Model\UserSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ImportRow;
use App\Excel\ExportProduct;

use App\Api\Core;
use App\User;
use \Session;

use App\Model\Product;
use App\Model\ProductBrand;
use App\Model\ProductCategory;
use App\Model\Photo;
use App\Model\News;
use App\Model\Event;
use App\Model\ProductCategoryOther;
use App\Model\ProductReview;
use App\Model\Review;
use App\Model\UserCartProduct;

class BEProductController extends Controller
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
        if (!($this->_viewer->isAllowed('product_view') || $this->_viewer->isSupplier())) {
            return redirect('/private');
        }
        $params = $request->all();
        if ($this->_viewer->isSupplier()) {
            $params['product_supplier_id'] = $this->_viewer->supplier_id;
        }

        $values = [
            'page_title' => 'Danh Sách Sản Phẩm',

            'params' => $params,
        ];

        $select = Product::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where('product_supplier_id', $this->_viewer->supplier_id);
        }

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);

                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("title", "LIKE", $search);
                } elseif ($filter == "mo_ta") {
                    $select->where("mo_ta_text", "LIKE", $search);
                } elseif ($filter == "cong_dung") {
                    $select->where("cong_dung_text", "LIKE", $search);
                } elseif ($filter == "thanh_phan") {
                    $select->where("thanh_phan_text", "LIKE", $search);
                } elseif ($filter == "hdsd") {
                    $select->where("huong_dan_su_dung_text", "LIKE", $search);
                }
            }

            if (isset($params['category']) && !empty($params['category']) && (int)$params['category']) {
                $category = ProductCategory::find((int)$params['category']);
                if ($category) {
                    if ($category->level == 3) {
                        $select->where("product_category_id", (int)$params['category']);
                    } else {
                        $select->where(function ($query1) use ($category) {
                            $query1->where("product_category_id", $category->id)
                                ->orWhereIn("product_category_id", $category->getAllChildren());
                        });
                    }
                }
            }
            if (isset($params['product_brand_id']) && !empty($params['product_brand_id'])) {
                if ($params['product_brand_id'] == 'he_thong') {
                    $select->where("product_brand_id", 0);
                } else {
                    $select->where("product_brand_id", (int)$params['product_brand_id']);
                }
            }
            if (isset($params['product_supplier_id']) && !empty($params['product_supplier_id']) && (int)$params['product_supplier_id']) {
                $select->where("product_supplier_id", (int)$params['product_supplier_id']);
            }
            if (isset($params['country']) && !empty($params['country'])) {
                $select->where("made_in", $params['country']);
            }
            if (isset($params['status']) && !empty($params['status'])) {
                $select->where("status", $params['status']);
            }
            if (isset($params['active']) && !empty($params['active']) && (int)$params['active']) {
                if ((int)$params['active'] == 1) {
                    $select->where("active", 1);
                } else {
                    $select->where("active", 0);
                }
            }
            if (isset($params['state']) && !empty($params['state'])) {
                switch ($params['state']) {
                    case 'is_new':
                        $select->where('is_new', 1);
                        break;
                    case 'is_best_seller':
                        $select->where('is_best_seller', 1);
                        break;
                    case 'other':
                        $select->where('is_new', 0)
                            ->where('is_best_seller', 0);
                        break;
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
            $select->orderByRaw("TRIM(LOWER(title)) {$orderBy}");
        } elseif ($order == "quantity") {
            $select->orderBy("unlimited", $orderBy);
            $select->orderBy("quantity", $orderBy);
        } else {
            $select->orderBy("{$order}", $orderBy);
        }

        $values['items'] = $select->paginate(20);

        //categories
        $values['categories'] = ProductCategory::where('deleted', 0)
            ->where("parent_id", "=", 0)
            ->orderByRaw("TRIM(LOWER(title))")
            ->get();

        //brands
        $select = ProductBrand::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where(function ($q) {
                $q->where('supplier_id', 0)
                    ->orWhere('supplier_id', $this->_viewer->supplier_id);
            });
        }
        $values['brands'] = $select->get();

        //suppliers
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

        return view("pages.back_end.products.index", $values);
    }

    public function add(Request $request)
    {
        $params = $request->all();
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $product = Product::find($itemId);

        $pageTitle = 'Tạo Sản Phẩm';
        if ($product) {
            $pageTitle = 'Sửa Thông Tin Sản Phẩm';

            if (!($this->_viewer->isAllowed("product_edit")
                || ($this->_viewer->isSupplier() && $this->_viewer->supplier_id == $product->product_supplier_id)
            )) {
                return redirect('/private');
            }
        } else {
            if (!($this->_viewer->isAllowed("product_add") || $this->_viewer->isSupplier())) {
                return redirect('/private');
            }
        }

        $values = [
            'page_title' => $pageTitle,

            'item' => $product,
        ];

        //categories
        $values['categories'] = ProductCategory::where('deleted', 0)
            ->where("parent_id", 0)
            ->orderBy('id', 'asc')
            ->get();

        //brands
        $select = ProductBrand::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where(function ($q) {
                $q->where('supplier_id', 0)
                    ->orWhere('supplier_id', $this->_viewer->supplier_id);
            });
        }
        $values['brands'] = $select->get();

        //suppliers
        $select = UserSupplier::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where('id', $this->_viewer->supplier_id);
        }
        $values['suppliers'] = $select->get();

        //products
        $select = Product::where('deleted', 0);
        if ($product) {
            $select->where('id', '<>', $product->id);
        }
        if ($this->_viewer->isSupplier()) {
            $select->where('product_supplier_id', $this->_viewer->supplier_id);
        }
        $values['others'] = $select->get();

        $comboIds = [];
        if ($product && !empty($product->combo)) {
            $comboIds = (array)json_decode($product->combo);
        }
        $values['comboIds'] = $comboIds;

        $values['photos'] = ($product) ? $product->getSlides() : [];

        //cate others
        $categoriesIds = [];
        if ($product && count($product->getCategoryOthers())) {
            foreach ($product->getCategoryOthers() as $cate) {
                $categoriesIds[] = $cate->id;
            }
        }
        $values['categoriesIds'] = $categoriesIds;

        return view("pages.back_end.products.add", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('product_add') || $this->_viewer->isAllowed('product_edit') || $this->_viewer->isSupplier())
        ) {
            return redirect('/private');
        }

        if (!count($request->post())) {
            return redirect('/admin/products');
        }
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $values['title'] = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $itemTitle = $values['title'];
        $product = Product::find($itemId);

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($itemTitle);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('product', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $values['price_main'] = (isset($values['price_main'])) ? $this->_apiCore->parseToInt($values['price_main']) : 0;
        $values['quantity'] = (isset($values['quantity']) && !empty($values['quantity'])) ? $this->_apiCore->parseToInt($values['quantity']) : NULL;
        $values['price_pay'] = (isset($values['price_pay'])) ? $this->_apiCore->parseToInt($values['price_pay']) : 0;
        $values['price_pay'] = $values['price_pay'] > 0 ? $values['price_pay'] : $values['price_main'];

        $values['active'] = (!empty($values['active']) && $values['active'] == 'on') ? 1 : 0;
        $values['unlimited'] = (!empty($values['unlimited']) && $values['unlimited'] == 'on') ? 1 : 0;
        $values['is_new'] = (!empty($values['is_new']) && $values['is_new'] == 'on') ? 1 : 0;
        $values['is_best_seller'] = (!empty($values['is_best_seller']) && $values['is_best_seller'] == 'on') ? 1 : 0;

        $values['mo_ta_text'] = html_entity_decode($this->_apiCore->cleanStr($values['mo_ta']));
        $values['mo_ta_text'] = filter_var($values['mo_ta_text'], FILTER_SANITIZE_STRING);
        $values['cong_dung_text'] = html_entity_decode($this->_apiCore->cleanStr($values['cong_dung']));
        $values['cong_dung_text'] = filter_var($values['cong_dung_text'], FILTER_SANITIZE_STRING);
        $values['thanh_phan_text'] = html_entity_decode($this->_apiCore->cleanStr($values['thanh_phan']));
        $values['thanh_phan_text'] = filter_var($values['thanh_phan_text'], FILTER_SANITIZE_STRING);
        $values['huong_dan_su_dung_text'] = html_entity_decode($this->_apiCore->cleanStr($values['huong_dan_su_dung']));
        $values['huong_dan_su_dung_text'] = filter_var($values['huong_dan_su_dung_text'], FILTER_SANITIZE_STRING);

        $values['video_link'] = (isset($values['video_link'])) ? $this->_apiCore->cleanStr($values['video_link']) : NULL;
        $values['mo_ta_ngan'] = (isset($values['mo_ta_ngan'])) ? $this->_apiCore->cleanStr($values['mo_ta_ngan']) : NULL;
        $values['can_nang'] = (isset($values['can_nang'])) ? $this->_apiCore->cleanStr($values['can_nang']) : NULL;
        $values['the_tich'] = (isset($values['the_tich'])) ? $this->_apiCore->cleanStr($values['the_tich']) : NULL;
        $values['kich_thuoc'] = (isset($values['kich_thuoc'])) ? $this->_apiCore->cleanStr($values['kich_thuoc']) : NULL;
        $values['tu_khoa_seo'] = (isset($values['tu_khoa_seo'])) ? $this->_apiCore->cleanStr($values['tu_khoa_seo']) : NULL;

        $values['combo'] = (isset($values['combo']) && count($values['combo'])) ? json_encode($values['combo']) : NULL;

        $cateOthers = (isset($values['cate_others'])) ? $values['cate_others'] : [];
        $oldPhotos = (isset($values['old_photos'])) ? $values['old_photos'] : [];

        unset($values['cate_others']);
        unset($values['old_photos']);
        unset($values['item_id']);

//        echo '<pre>';var_dump($values);die;

        $updated = $values;

        if ($product) {
            if (!($this->_viewer->isAllowed("product_edit")
                || ($this->_viewer->isSupplier() && $this->_viewer->supplier_id == $product->product_supplier_id)
            )) {
                return redirect('/private');
            }
            $itemOLD = (array)$product->toArray();

            //cate others
            ProductCategoryOther::where('product_id', $product->id)
                ->delete();
            if (count($cateOthers)) {
                foreach ($cateOthers as $i) {
                    ProductCategoryOther::create([
                        'product_id' => $product->id,
                        'category_id' => (int)$i,
                    ]);
                }
            }

            $product->update($updated);

            $product = Product::find($product->id);
            $itemNEW = (array)$product->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_edit',
                'item_id' => $product->id,
                'item_type' => 'product',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            //slides
            $idsOLD = [];
            $olds = array_filter(explode(";", $oldPhotos));
            if (count($olds)) {
                foreach ($olds as $old) {
                    $arr = explode("_", $old);
                    $idsOLD[] = (int)$arr[1];
                }
            }
            //old
            if (count($idsOLD)) {
                $olds = Photo::where('item_id', $product->id)
                    ->where('item_type', 'product')
                    ->where('type', 'slides')
                    ->where('parent_id', 0)
                    ->get();
                foreach ($olds as $old) {
                    if (!in_array($old->id, $idsOLD)) {
                        $old->delItem();
                    }
                }
            } else {
                $product->removeSlides();
            }

            Session::put('MESSAGE', 'ITEM_EDITED');

        } else {
            if (!($this->_viewer->isAllowed("product_add") || $this->_viewer->isSupplier())) {
                return redirect('/private');
            }

            $product = Product::create($updated);

            if (count($cateOthers)) {
                foreach ($cateOthers as $i) {
                    ProductCategoryOther::create([
                        'product_id' => $product->id,
                        'category_id' => (int)$i,
                    ]);
                }
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_add',
                'item_id' => $product->id,
                'item_type' => 'product',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            //remove old
            $product->removeAvatar();

            $imageName = 'product_logo_' . $product->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/product/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/product/'), $imageName);
            $product->uploadAvatar($imageName, $imagePath);
        }

        //slides
        $slides = $request->file('slides');
        if (!empty($slides)) {
            $i = 0;
            foreach ($slides as $slide) {
                $i += 1;
                $imageName = 'product_slide_' . $product->id . '_' . time() . '_' . $i . '.' . $slide->getClientOriginalExtension();
                $imagePath = "/uploaded/product/" . $imageName;
                $slide->move(public_path('/uploaded/product/'), $imageName);
                $product->addSlides($imageName, $imagePath);
            }
        }

        return redirect('/admin/products');
    }

    public function updateStatus(Request $request)
    {
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $column = (isset($values['column'])) ? $this->_apiCore->cleanStr($values['column']) : NULL;

        $product = Product::find($itemId);
        if ($product) {

            if (!($this->_viewer->isAllowed("product_edit")
                || ($this->_viewer->isSupplier() && $this->_viewer->supplier_id == $product->product_supplier_id)
            )) {
                return response()->json([]);
            }

            switch ($column) {
                case 'is_new':
                    $product->update([
                        'is_new' => $value,
                    ]);
                    break;

                case 'is_best_seller':
                    $product->update([
                        'is_best_seller' => $value,
                    ]);
                    break;

                case 'status':
                    $product->update([
                        'status' => $value,
                    ]);
                    break;

                case 'active':
                    $product->update([
                        'active' => $value,
                    ]);
                    break;
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_update',
                'item_id' => $product->id,
                'item_type' => 'product',
                'params' => json_encode([
                    'type' => $column,
                    'value' => $value,
                ])
            ]);
        }

        return response()->json([]);
    }

    public function delete(Request $request)
    {
        if (!($this->_viewer->isAllowed('product_delete') || $this->_viewer->isSupplier())) {
            return response()->json([]);
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $product = Product::find($itemId);
        if ($product) {
            if (!($this->_viewer->isAllowed("product_delete")
                || ($this->_viewer->isSupplier() && $this->_viewer->supplier_id == $product->product_supplier_id)
            )) {
                return response()->json([]);
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_delete',
                'item_id' => $product->id,
                'item_type' => 'product',
            ]);

            $product->delItem();
        }

        return response()->json([]);
    }

    //review
    public function review(Request $request)
    {
        $params = $request->all();
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $product = Product::find($itemId);
        if (!$product
            || (!$this->_viewer->isAllowed('product_review_client'))
        ) {
            return redirect('/private');
        }

        $values = [
            'page_title' => $product->getTitle(),

            'item' => $product,
        ];

        $select = ProductReview::where('product_id', $product->id)
            ->orderByDesc('id');
        $values['reviews'] = $select->get();

        return view("pages.be.products.review", $values);
    }

    public function reviewStatus(Request $request)
    {
        $values = $request->post();
        $active = (isset($values['active'])) ? (int)$values['active'] : 0;
        $itemId = (isset($values['id'])) ? (int)$values['id'] : 0;
        $review = ProductReview::find($itemId);
        if ($review) {
            $product = $review->getProduct();

            $review->update([
                'active' => $active,
            ]);

            $review->getProduct()->reCalStar();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'product_review_update',
                'item_id' => $product->id,
                'item_type' => 'product',
                'params' => json_encode([
                    'type' => 'active',
                    'value' => $active,
                ])
            ]);
        }

        return response()->json([]);
    }

    //excel
    public function exportItem(Request $request)
    {
        if (!$this->_viewer->isAllowed("product_excel_export")) {
            return redirect('/private');
        }

        $params = $request->all();
//        echo '<pre>';var_dump($params);die;

        $select = Product::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);

                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("title", "LIKE", $search);
                } elseif ($filter == "mo_ta") {
                    $select->where("mo_ta_text", "LIKE", $search);
                } elseif ($filter == "cong_dung") {
                    $select->where("cong_dung_text", "LIKE", $search);
                } elseif ($filter == "thanh_phan") {
                    $select->where("thanh_phan_text", "LIKE", $search);
                } elseif ($filter == "hdsd") {
                    $select->where("huong_dan_su_dung_text", "LIKE", $search);
                }
            }

            if (isset($params['category']) && !empty($params['category']) && (int)$params['category']) {
                $category = ProductCategory::find((int)$params['category']);
                if ($category) {
                    if ($category->level == 3) {
                        $select->where("product_category_id", (int)$params['category']);
                    } else {
                        $select->where(function ($query1) use ($category) {
                            $query1->where("product_category_id", $category->id)
                                ->orWhereIn("product_category_id", $category->getAllChildren());
                        });
                    }
                }
            }
            if (isset($params['brand']) && !empty($params['brand']) && (int)$params['brand']) {
                $select->where("product_brand_id", (int)$params['brand']);
            }
            if (isset($params['country']) && !empty($params['country'])) {
                $select->where("made_in", $params['country']);
            }
            if (isset($params['status']) && !empty($params['status'])) {
                $select->where("status", $params['status']);
            }
            if (isset($params['active']) && !empty($params['active']) && (int)$params['active']) {
                if ((int)$params['active'] == 1) {
                    $select->where("active", 1);
                } else {
                    $select->where("active", 0);
                }
            }
            if (isset($params['state']) && !empty($params['state'])) {
                switch ($params['state']) {
                    case 'is_new':
                        $select->where('is_new', 1);
                        break;
                    case 'is_best_seller':
                        $select->where('is_best_seller', 1);
                        break;
                    case 'other':
                        $select->where('is_new', 0)
                            ->where('is_best_seller', 0);
                        break;
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
            $select->orderByRaw("TRIM(LOWER(title)) {$orderBy}");
        } elseif ($order == "quantity") {
            $select->orderBy("unlimited", $orderBy);
            $select->orderBy("quantity", $orderBy);
        } else {
            $select->orderBy("{$order}", $orderBy);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'product_excel_export',
            'item_id' => 0,
            'item_type' => 'product',
        ]);

        $excel = new ExportProduct();
        $excel->setItems($select->get());
        return Excel::download($excel, 'export_san_pham.xlsx');

    }

    public function importItem(Request $request)
    {
        if (!$this->_viewer->isAllowed("product_excel_import")) {
            return redirect('/private');
        }

        $params = $request->post();
        $rows = (new ImportRow)->toArray($request->file);
        if (!count($rows)) {
            return redirect('private');
        }
//        echo '<pre>';var_dump($rows[0]);die;

        $message = '';
        $count = 0;
        $arr = [];

        DB::beginTransaction();
        try {
            if (count($rows)) {
                foreach ($rows[0] as $k => $row) {
                    if (!$k) {
                        $arr[] = $row;
                        continue;
                    }

//                    echo '<pre>';var_dump(count($row));die;

                    $spTen = $this->_apiCore->cleanStr($row[0]);
                    $spNhomChinh = $this->_apiCore->cleanStr($row[1]);
                    $spNhomPhu = $this->_apiCore->cleanStr($row[2]);
                    $spThuongHieu = $this->_apiCore->cleanStr($row[3]);
                    $spXuatXu = $this->_apiCore->cleanStr($row[4]);

                    $spGiaGoc = $this->_apiCore->parseToInt($row[5]);
                    $spGiamGia = $this->_apiCore->parseToInt($row[6]);
                    $spGiaBan = $this->_apiCore->parseToInt($row[7]);
                    $spSoLuong = $this->_apiCore->parseToInt($row[8]);

                    $spDRC = $this->_apiCore->cleanStr($row[9]);
                    $spKL = $this->_apiCore->parseToInt($row[10]);
                    $spKLBB = $this->_apiCore->parseToInt($row[11]);

                    $spDonVi = $this->_apiCore->cleanStr($row[12]);
                    $spCanNang = $this->_apiCore->cleanStr($row[13]);
                    $spTheTich = $this->_apiCore->cleanStr($row[14]);
                    $spKichThuoc = $this->_apiCore->cleanStr($row[15]);
                    $spKichThuocVH = $this->_apiCore->cleanStr($row[16]);
                    $spGioiTinh = $this->_apiCore->cleanStr($row[17]);
                    $spDangSP = $this->_apiCore->cleanStr($row[18]);
                    $spDiemThamHut = $this->_apiCore->cleanStr($row[19]);
                    $spMucThamHut = $this->_apiCore->cleanStr($row[20]);
                    $spQuiCachDongGoi = $this->_apiCore->cleanStr($row[21]);
                    $spSoMieng = $this->_apiCore->cleanStr($row[22]);
                    $spTuKhoaSEO = $this->_apiCore->cleanStr($row[23]);
                    $spMoTaNgan = $this->_apiCore->cleanStr($row[24]);
                    $spVideo = $this->_apiCore->cleanStr($row[25]);
                    $spThongTinChiTiet = $this->_apiCore->cleanStr($row[26]);

                    if (empty($spTen)) {
                        $row[27] = 'Dữ liệu lỗi!';
                        $arr[] = $row;
                        continue;
                    }

                    //href
                    $title = $this->_apiCore->stripVN($spTen);
                    $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
                    $spHref = $this->_apiCore->generateHref('product', array(
                        'id' => 0,
                        'name' => $title,
                    ));

                    $unlimited = !$spSoLuong ? 1 : 0;

                    $spThongTinChiTietText = html_entity_decode(trim(strip_tags($spThongTinChiTiet)));
                    $spThongTinChiTietText = filter_var($spThongTinChiTietText, FILTER_SANITIZE_STRING);

                    //
                    $categoryId = 0;
                    if (!empty($spNhomChinh)) {
                        $category = ProductCategory::where('title', $spNhomChinh)
                            ->where('deleted', 0)
                            ->limit(1)
                            ->first();
                        if ($category) {
                            $categoryId = $category->id;
                        }
                    }
                    //
                    $brandId = 0;
                    if (!empty($spThuongHieu)) {
                        $brand = ProductBrand::where('title', $spThuongHieu)
                            ->where('deleted', 0)
                            ->limit(1)
                            ->first();
                        if ($brand) {
                            $brandId = $brand->id;
                        }
                    }
                    //
                    $madeIn = '';
                    if (!empty($spXuatXu)) {
                        foreach ($this->_apiCore->listCountries() as $key => $val) {
                            if ($spXuatXu == $val) {
                                $madeIn = $key;
                                break;
                            }
                        }
                    }
                    //
                    $cate = [];
                    $spNhomPhu = array_filter(explode(';', $spNhomPhu));
                    if (count($spNhomPhu)) {
                        foreach ($spNhomPhu as $nhomPhu) {
                            $category = ProductCategory::where('title', $nhomPhu)
                                ->where('deleted', 0)
                                ->limit(1)
                                ->first();
                            if ($category) {
                                $cate[] = $category->id;
                            }
                        }
                    }
                    //
                    $spChieuDai = 0;
                    $spChieuCao = 0;
                    $spChieuRong = 0;
                    $spDRC = array_filter(explode('x', $spDRC));
                    if (count($spDRC) == 3) {
                        $spChieuDai = (int)$this->_apiCore->cleanStr($spDRC[0]);
                        $spChieuRong = (int)$this->_apiCore->cleanStr($spDRC[1]);
                        $spChieuCao = (int)$this->_apiCore->cleanStr($spDRC[2]);
                    }

                    $updated = [
                        'title' => $spTen,
                        'href' => $spHref,
                        'product_category_id' => $categoryId,
                        'product_brand_id' => $brandId,
                        'made_in' => $madeIn,
                        'price_main' => $spGiaGoc,
                        'discount' => $spGiamGia,
                        'price_pay' => $spGiaBan,
                        'quantity' => $spSoLuong >= 0 ? $spSoLuong : NULL,
                        'unlimited' => $unlimited,
                        'status' => 'con_hang',
                        'video_link' => $spVideo,
                        'thong_tin_chi_tiet' => $spThongTinChiTiet,
                        'thong_tin_chi_tiet_text' => $spThongTinChiTietText,
                        'so_mieng' => $spSoMieng,
                        'mo_ta_ngan' => $spMoTaNgan,
                        'don_vi' => $spDonVi,
                        'the_tich' => $spTheTich,
                        'can_nang' => $spCanNang,
                        'kich_thuoc' => $spKichThuoc,
                        'kich_thuoc_vong_hong' => $spKichThuocVH,
                        'gioi_tinh' => $spGioiTinh,
                        'dang_san_pham' => $spDangSP,
                        'diem_tham_hut' => $spDiemThamHut,
                        'muc_tham_hut' => $spMucThamHut,
                        'qui_cach_dong_goi' => $spQuiCachDongGoi,
                        'tu_khoa_seo' => $spTuKhoaSEO,
                        'chieu_dai' => $spChieuDai,
                        'chieu_rong' => $spChieuRong,
                        'chieu_cao' => $spChieuCao,
                        'khoi_luong' => $spKL,
                        'khoi_luong_bao_bi' => $spKLBB,
                    ];

//                    echo '<pre>';var_dump($updated);die;

                    $product = Product::create($updated);

                    if (count($cate)) {
                        foreach ($cate as $i) {
                            ProductCategoryOther::create([
                                'category_id' => (int)$i,
                                'product_id' => $product->id,
                            ]);
                        }
                    }

                    $this->_apiCore->addLog([
                        'user_id' => $this->_viewer->id,
                        'action' => 'product_add',
                        'item_id' => $product->id,
                        'item_type' => 'product',
                    ]);

                    $count++;

                }
            }

            $message = 'IMPORT_SUCCESS';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
//            var_dump($e->getMessage());die;

            $count = 0;
            $message = 'IMPORT_ERROR';
        }

//        echo '<pre>';var_dump($count, $emp, $arr);die;

        Session::put('MESSAGE', $message);
        Session::put('SAVED', $count);

        if (count($arr)) {
            Session::put('FAILED', $arr);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'product_excel_import',
            'item_id' => 0,
            'item_type' => 'product',
        ]);

        return redirect('admin/products');
    }

    public function importFailed(Request $request)
    {
        $arr = (Session::get('FAILED'));
        if (!empty($arr)) {
            Session::forget('FAILED');
        }

        $excel = new ImportProductFailed();
        $excel->setARR($arr);
        return Excel::download($excel, 'import_san_pham_that_bai.xlsx');
    }

}

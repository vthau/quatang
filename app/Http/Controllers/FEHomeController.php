<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use \Session;
use \Artisan;

use App\Api\Core;
use App\Api\FE;
use App\Api\Email;
use App\Api\Permission;
use App\User;

use App\Model\Contact;
use App\Model\Event;
use App\Model\Faq;
use App\Model\File;
use App\Model\GhnWard;
use App\Model\GhnDistrict;
use App\Model\GhnProvince;
use App\Model\LevelAction;
use App\Model\LevelPermission;
use App\Model\Log;
use App\Model\MailQueue;
use App\Model\News;
use App\Model\Notification;
use App\Model\NotificationType;
use App\Model\Photo;
use App\Model\Product;
use App\Model\ProductBrand;
use App\Model\ProductCategory;
use App\Model\ProductCategoryOther;
use App\Model\ProductReview;
use App\Model\Review;
use App\Model\Setting;
use App\Model\SmsVerify;
use App\Model\Test;
use App\Model\TestAnswer;
use App\Model\TestDetail;
use App\Model\TestQuestion;
use App\Model\UserCart;
use App\Model\UserCartOnline;
use App\Model\UserCartProduct;
use App\Model\UserLevel;
use App\Model\UserSocial;
use App\Model\UserView;
use App\Model\UserWishlist;

class FEHomeController extends Controller
{
    protected $_apiCore = null;
    protected $_apiFE = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiFE = new FE();
    }

    //landing
    public function trangChu()
    {
        $values = [

        ];

        return view("pages.front_end.index", $values);
    }
    public function gioiThieu()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Giới Thiệu';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.about_us", $values);
    }

    public function lienHe()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Liên Hệ';

        $message = "";
        $success = (Session::get('CONTACT_SENT_SUCCESS'));
        if ((int)$success) {
            Session::forget('CONTACT_SENT_SUCCESS');
            $message = "Thay đổi thành công!";
        }

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
            'message' => $message,
        ];

        return view("pages.front_end.info.contact_us", $values);
    }
    public function lienHeGui(Request $request)
    {
        $values = $request->post();

        if (isset($values['name']) && !empty($values['name'])
            && isset($values['phone']) && !empty($values['phone'])
            && isset($values['email']) && !empty($values['email'])
            && isset($values['body']) && !empty($values['body'])
        ) {
            $row = Contact::create([
                'name' => $values['name'],
                'phone' => $values['phone'],
                'email' => $values['email'],
                'body' => $values['body'],
            ]);

            //notify
            $this->_apiCore->notifyAdmin('contact_new', [
                'object_type' => 'contact',
                'object_id' => $row->id,
            ]);

            Session::put('CONTACT_SENT_SUCCESS', '1');
        }

        return response()->json([]);
    }

    //chinh-sach
    public function chinhSachThanhVien()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Chính Sách Thành Viên';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.policy_member", $values);
    }
    public function chinhSachGiaoHang()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Chính Sách Giao Hàng';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.policy_shipment", $values);
    }
    public function chinhSachDoiTra()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Chính Sách Đổi Trả';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.policy_refund", $values);
    }
    public function chinhSachThanhToan()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Chính Sách Thanh Toán';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.policy_payment", $values);
    }
    public function chinhSachBaoMat()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Chính Sách Bảo Mật';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.info.policy_security", $values);
    }

    //news
    public function tinTuc(Request $request)
    {
        $params = $request->all();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Tin Tức';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'params' => $params,
        ];

        return view("pages.front_end.news.index", $values);
    }
    public function tinTucChiTiet($href, Request $request)
    {
        $item = News::where('href', $href)->first();
        if (!$item
            || ($item && $item->isDeleted())
        ) {
            return redirect('/');
        }
        $params = $request->all();

        $values = [
            'page_title' => $item->getTitle(),

            'item' => $item,

            'params' => $params,
        ];

        $item->isViewed();

        return view("pages.front_end.news.info", $values);
    }

    //event
    public function tuVan(Request $request)
    {
        $params = $request->all();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Góc Tư Vấn';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'params' => $params,
        ];

        return view("pages.front_end.events.index", $values);
    }
    public function tuVanChiTiet($href, Request $request)
    {
        $item = Event::where('href', $href)->first();
        if (!$item
            || ($item && $item->isDeleted())
        ) {
            return redirect('/');
        }
        $params = $request->all();

        $values = [
            'page_title' => $item->getTitle(),

            'item' => $item,

            'params' => $params,
        ];

        $item->isViewed();

        return view("pages.front_end.events.info", $values);
    }

    //thuong hieu
    public function thuongHieu()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Thương Hiệu';
        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
        ];

        return view("pages.front_end.brands.index", $values);
    }
    public function thuongHieuChiTiet($href, Request $request)
    {
        $item = ProductBrand::where('href', $href)->first();
        if (!$item
            || ($item && $item->isDeleted())
        ) {
            return redirect('/');
        }
        $params = $request->all();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = ucfirst($item->getTitle());

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'item' => $item,

            'params' => $params,
        ];

        $item->isViewed();

        return view("pages.front_end.brands.info", $values);
    }

    //san pham
    public function sanPham(Request $request)
    {
        $params = $request->all();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Sản Phẩm';

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'params' => $params,
        ];

        return view("pages.front_end.products.index", $values);
    }
    public function danhMuc($href, Request $request)
    {
        $item = ProductCategory::where('href', $href)
            ->where('deleted', 0)
            ->first();
        if (!$item
            || ($item && $item->isDeleted())
        ) {
            return redirect('/');
        }
        $params = $request->all();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = ucfirst($item->getTitle());

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'item' => $item,

            'params' => $params,
        ];

        $item->isViewed();

        return view("pages.front_end.products.category", $values);
    }
    public function sanPhamChiTiet($href, Request $request)
    {
        $params = $request->all();
        $item = Product::where('href', $href)->first();
        if (!$item
            || ($item && $item->isDeleted())
            || ($item && !$item->active)
        ) {
            return redirect('/');
        }
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = ucfirst($item->getTitle());

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'item' => $item,

            'products_seen' => $this->_apiFE->getProducts([
                'limit' => 4,
                'category' => $item->product_category_id,
                'except' => $item->id,
            ]),

            'products_viewed' => $this->_apiFE->getViewedProducts(4, $item->id),

            'params' => $params,
        ];

        $item->isViewed();

        //
        $select = ProductReview::where('product_id', $item->id)
            ->where('active', 1);
        $values['reviews'] = $select->get();

        return view("pages.front_end.products.info", $values);
    }

    //tim kiem
    public function timKiem(Request $request)
    {
        $params = $request->all();
        $keyword = (isset($params['keyword'])) ? $this->_apiCore->cleanStr($params['keyword']) : '';

        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Tìm Kiếm';

        //search product
        $select = Product::where('deleted', 0)
            ->where('active', 1);

        if (isset($params['category']) && (int)$params['category']) {
            $select->where('product_category_id', (int)$params['category']);
        }

        if (isset($params['brand']) && (int)$params['brand']) {
            $select->where('product_brand_id', (int)$params['brand']);
        }

        if (isset($params['made_in']) && !empty($params['made_in'])) {
            $select->where('made_in', $params['made_in']);
        }

        if (isset($params['max']) && !empty($params['max']) && (int)$params['max']) {
            $select->where('price_pay', '<=', (int)$params['max']);
        }

        if (isset($params['price']) && !empty($params['price'])) {
            switch ($params['price']) {
                case 'to500':
                    $select->where('price_pay', '<=', 500000);
                    break;

                case 'to1m':
                    $select->where('price_pay', '>', 500000)
                        ->where('price_pay', '<=', 1000000);
                    break;

                case 'to2m':
                    $select->where('price_pay', '>', 1000000)
                        ->where('price_pay', '<=', 2000000);
                    break;

                case 'to3m':
                    $select->where('price_pay', '>', 2000000)
                        ->where('price_pay', '<=', 3000000);
                    break;

                case 'to5m':
                    $select->where('price_pay', '>', 3000000)
                        ->where('price_pay', '<=', 5000000);
                    break;

                case 'to10m':
                    $select->where('price_pay', '>', 5000000)
                        ->where('price_pay', '<=', 10000000);
                    break;

                case 'to20m':
                    $select->where('price_pay', '>', 10000000)
                        ->where('price_pay', '<=', 20000000);
                    break;

                case 'max':
                    $select->where('price_pay', '>', 20000000);
                    break;
            }
        }

        if (isset($params['sort']) && !empty($params['sort'])) {
            switch ($params['sort']) {
                case 'price_asc':
                    $select->orderBy('price_pay', 'asc');
                    break;

                case 'price_desc':
                    $select->orderBy('price_pay', 'desc');
                    break;

                case 'sort_new':
                    $select->where('is_new', 1)
                        ->orderBy('price_pay', 'asc');
                    break;

                case 'sort_best_seller':
                    $select->where('is_best_seller', 1)
                        ->orderBy('price_pay', 'asc');
                    break;

                case 'sort_discount':
                    $select->where('discount', '>', 0)
                        ->orderBy('price_pay', 'asc');
                    break;
            }
        } else {
            $select->orderBy('price_pay', 'asc');
        }



        //tracking dua vao
        //ma don hang - ma van don - dien thoai
        $carts = [];
        $cart = null;

        if (!empty($keyword)) {
            $search = '%' . str_replace(' ', '%', $keyword) . '%';

            $select->where('title', 'LIKE', $search);

//            $products = $this->_apiFE->getProducts([
//                'keyword' => $keyword,
//                'pagination' => 1,
//                'page' => (isset($params['page']) && (int)$params['page']) ? (int)$params['page'] : 1,
//            ]);

            //
            $cart = UserCart::where('deleted', 0)
                ->where('href', $keyword)
                ->whereIn('status', ['chua_thanh_toan', 'da_thanh_toan'])
                ->limit(1)
                ->first();
            if (!$cart) {
                $cart = UserCart::where('deleted', 0)
                    ->where('ghn_code', $keyword)
                    ->whereIn('status', ['chua_thanh_toan', 'da_thanh_toan'])
                    ->limit(1)
                    ->first();
                if (!$cart) {
                    $carts = UserCart::query('user_carts')
                        ->select('user_carts.*')
                        ->leftJoin('users', 'user_carts.user_id', '=', 'users.id')
                        ->where('user_carts.deleted', 0)
                        ->whereIn('user_carts.status', ['chua_thanh_toan', 'da_thanh_toan'])
                        ->where(function($q) use($search) {
                            $q->where('users.phone', 'LIKE', $search)
                                ->orWhere('user_carts.phone', 'LIKE', $search);
                        })
                        ->limit(3)
                        ->orderByDesc('user_carts.id')
                        ->get();
                }
            }
        }

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,
            'params' => $params,

            'products' => $select->paginate(16),

            'cart' => $cart,
            'carts' => $carts,
        ];

        //categories
        $values['categories'] = ProductCategory::where('deleted', 0)
            ->where("parent_id", "=", 0)
            ->orderByRaw("TRIM(LOWER(title))")
            ->get();

        //brands
        $select = ProductBrand::where('deleted', 0);
        $values['brands'] = $select->get();

        return view("pages.front_end.users.search", $values);
    }

    //yeu thich
    public function yeuThich(Request $request)
    {
        $params = $request->all();
        $viewer = $this->_apiCore->getViewer();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Sản Phẩm Yêu Thích';

        $products = [];
        if ($viewer) {
            $products = $viewer->getWishlist();
        } else {
            $loved = (Session::get('USR_LOVE'));
            if ($loved && count($loved)) {
                $products = Product::where('deleted', 0)
                    ->where('active', 1)
                    ->whereIn('id', $loved)
                    ->orderByDesc('id')
                    ->get();
            }
        }

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'products' => $products,

            'params' => $params,
        ];

        return view("pages.front_end.users.loved_products", $values);
    }



}

<?php

namespace App\Http\Controllers;

use App\Model\UserPerson;
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
use App\Model\SystemCategory;
use App\Model\CardTemplate;
use App\Model\Wish;

class FECartController extends Controller
{
    protected $_apiCore = null;
    protected $_apiFE = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiFE = new FE();
    }

    public function gioHang()
    {
        $viewer = $this->_apiCore->getViewer();
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Giỏ Hàng';

        $this->_apiCore->clearCache();

        $cartItems = [];
        $products = [];
        if ($viewer) {
            $cart = $viewer->getCart();
            $cart->updateCart();

            $products = $cart->getProducts();

            foreach ($products as $ite) {
                $cartItems[] = [
                    'id' => $ite->product_id,
                    'quantity' => $ite->quantity,
                ];
            }
        } else {

            $cartQty = (Session::get('USR_CART_QTY'));
            $cart = (Session::get('USR_CART'));
            if ($cart && count($cart)) {
                $ids = '';
                foreach ($cart as $i) {
                    $ids .= (int)$i . ',';
                }
                $ids = substr($ids, 0, -1);

                $products = Product::where('deleted', 0)
                    ->where('active', 1)
                    ->whereIn('id', $cart)
                    ->orderByRaw("FIELD(id, $ids)")
                    ->get();

                foreach ($products as $ite) {
                    $quantity = 1;
                    if ($cartQty && count($cartQty) && isset($cartQty[$ite->id])) {
                        $quantity = (int)$cartQty[$ite->id];
                    }

                    $cartItems[] = [
                        'id' => $ite->id,
                        'quantity' => $quantity,
                    ];
                }
            }
        }

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'products' => $products,
        ];

        $saved = (Session::get('CHECKOUT_SUCCESS'));
        if ((int)$saved) {
            Session::forget('CHECKOUT_SUCCESS');
        }
        $values['saved'] = $saved;

        //phi giao hang
        $values['shippingFee'] = $this->_apiCore->getSetting('payment_ship_fee');

        //free 1 so tinh/thanh
        $freeCity = false;
        if ($viewer && $viewer->province_id) {
            $row = Setting::where('title', 'payment_ship_free_city')->first();
            if ($row && !empty($row->value)) {
                $arr = (array)json_decode($row->value);
                if (count($arr) && in_array($viewer->province_id, $arr)) {
                    $freeCity = true;
                }
            }
        }
        $values['freeCity'] = $freeCity ? 1 : 0;

        //template
        $select = SystemCategory::where('deleted', 0)
            ->where('active', 1)
            ->orderByRaw('TRIM(LOWER(title))');
        $values['templates'] = $select->get();

        return view("pages.front_end.users.cart", $values);
    }

    public function gioHangLoi()
    {
        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Giỏ Hàng';

        $this->_apiCore->clearCache();

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

        ];

        return view("pages.front_end.users.cart_error", $values);
    }

    public function donHang($href)
    {
        if (empty($href)) {
            return redirect('/invalid');
        }
        $item = UserCart::where('href', $href)->first();
        if (!$item
            || ($item && $item->isDeleted())
        ) {
            return redirect('/invalid');
        }

        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = 'Đơn Hàng';

        $cart = $item;

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'products' => $cart->getProducts(),
            'cart' => $cart,
            'cards' => $cart->getPhotoCards(),
        ];

        return view("pages.front_end.users.cart_info", $values);
    }

    public function phiGiaoHang(Request $request)
    {
        $values = $request->post();
        $ids = (isset($values['ids'])) ? $this->_apiCore->cleanStr($values['ids']) : NULL;
        $express = (isset($values['express'])) ? $this->_apiCore->parseToInt($values['express']) : 0;
        $provinceId = (isset($values['province_id'])) ? $this->_apiCore->parseToInt($values['province_id']) : 0;
        $districtId = (isset($values['district_id'])) ? $this->_apiCore->parseToInt($values['district_id']) : 0;
        $wardId = (isset($values['ward_id'])) ? $this->_apiCore->parseToInt($values['ward_id']) : 0;

        //free 1 so tinh/thanh
        $freeCity = false;
        $row = Setting::where('title', 'payment_ship_free_city')->first();
        if ($row && !empty($row->value)) {
            $arr = (array)json_decode($row->value);
            if (count($arr) && in_array($provinceId, $arr)) {
                $freeCity = true;
            }
        }

        return response()->json([
            'VALID' => true,
            'FREE_CITY' => $freeCity ? 1 : 0
        ]);
    }

    public function khachHangSuKien(Request $request)
    {
        $values = $request->post();
        $personId = (isset($values['person_id'])) ? (int)$values['person_id'] : 0;

        $arr = [];
        $address = [];
        $quanHuyen = [];
        $phuongXa = [];

        $person = UserPerson::find($personId);
        if ($person) {
            $dates = $this->_apiFE->getDates(['person_id' => $personId]);
            if (count($dates)) {
                foreach ($dates as $date) {
                    $arr[] = [
                        'id' => $date->id,
                        'title' => $date->title . ' - ' . $date->day . ' / ' . $date->month,
                    ];
                }
            }

            $address = [
                'address' => $person->address,
                'ward_id' => $person->ward_id ? $person->ward_id : '',
                'district_id' => $person->district_id ? $person->district_id : '',
                'province_id' => $person->province_id ? $person->province_id : '',
            ];

            $districts = $this->_apiFE->getDistrictsByProvinceId($person->province_id);
            if (count($districts)) {
                foreach ($districts as $district) {
                    $quanHuyen[] = [
                        'id' => $district->id,
                        'title' => $district->title,
                    ];
                }
            }
            $wards = $this->_apiFE->getWardsByDistrictId($person->district_id);
            if (count($wards)) {
                foreach ($wards as $ward) {
                    $phuongXa[] = [
                        'id' => $ward->id,
                        'title' => $ward->title,
                    ];
                }
            }
        }

        return response()->json([
            'ARR' => $arr,
            'DC' => $address,
            'DISTRICTS' => json_encode($quanHuyen),
            'WARDS' => json_encode($phuongXa),
        ]);
    }

    //cod
    public function codCreate(Request $request)
    {
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $params = (array)json_decode($values['data']);

        //tao don hang
        $cart = $this->_apiFE->cartBookedCOD($params);

        //card template
        $slides = $request->file('files');
        if (!empty($slides) && !$params['mau_thiep_co_san']) {
            $i = 0;
            foreach ($slides as $photo) {
                $i += 1;
                $imageName = 'card_template_' . $cart->id . '_' . time() . '_' . $i . '.' . $photo->getClientOriginalExtension();
                $imagePath = "/uploaded/order/" . $imageName;
                $photo->move(public_path('/uploaded/order/'), $imageName);
                $cart->addPhotoCard($imageName, $imagePath);
            }
        }
        if ($params['mau_thiep_co_san'] && $params['mau_thiep_id']) {
            $card = CardTemplate::find((int)$params['mau_thiep_id']);
            if ($card) {
                $slides = $card->getSlides();
                if (count($slides)) {
                    $i = 0;
                    foreach ($slides as $photo) {
                        $i += 1;
                        $imageName = 'card_template_' . $cart->id . '_' . time() . '_' . $i . '.' . $photo->name;
                        $imagePath = "/uploaded/order/" . $imageName;
                        copy($photo->getPhoto(), public_path('/uploaded/order/' . $imageName));
                        $cart->addPhotoCard($imageName, $imagePath);
                    }
                }
            }
        }

        //commission
//        $cart->updateCommission();

        //return
//        $days = $cart->duDoanDonHang();
//        Session::put('CHECKOUT_NEXT', $days);

        Session::put('CHECKOUT_SUCCESS', $cart->id);

        return response()->json(['URL' => url('/gio-hang')]);
    }
}

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
use App\Model\Setting;
use App\Model\UserCart;
use App\Model\UserCartOnline;
use App\Model\UserCartProduct;
use App\Model\UserLevel;
use App\Model\UserSocial;
use App\Model\UserView;
use App\Model\UserWishlist;
use App\Model\UserPerson;
use App\Model\UserPersonDate;
use App\Model\UserRelationship;

class FEUserController extends Controller
{
    protected $_apiCore = null;
    protected $_apiFE = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiFE = new FE();

        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            if (!$this->_viewer || ($this->_viewer && !$this->_viewer->id)) {
                return redirect('/dang-nhap');
            }

            return $next($request);
        });
    }

    public function taiKhoan(Request $request)
    {
        $params = $request->all();

        $siteTitle = $this->_apiCore->getSetting('site_title');
        $pageTitle = $this->_apiCore->getSetting('text_tk_title');

        $values = [
            'page_title' => (!empty($siteTitle)) ? $siteTitle . " | " . $pageTitle : $pageTitle,

            'params' => $params,
        ];

        $selectView = UserView::where('user_id', $this->_viewer->id)
            ->orderBy('updated_at', 'desc')
            ->limit(16);
        $values['views'] = $selectView->get();

        //relationship
        $relationships = $this->_viewer->getRelationships();
        if (!count($relationships)) {
            $this->_viewer->createRelationships();
            $relationships = $this->_viewer->getRelationships();
        }
        $values['relationships'] = $relationships;

        //person
        $select = UserPerson::orderByRaw('TRIM(LOWER(title))');
        $values['persons'] = $select->get();

        return view("pages.front_end.users.account", $values);
    }

    public function thongTinCapNhat(Request $request)
    {
        $values = $request->post();
        $viewer = $this->_apiCore->getViewer();
        $valid = true;

        if ($viewer
            && isset($values['key']) && !empty($values['key'])
//            && isset($values['value']) && !empty($values['value'])
        ) {
            $value = trim($values['value']);
            switch ($values['key']) {
                case 'password':
                    $viewer->update([
                        'password' => Hash::make($value),
                    ]);
                    break;

                case 'name':
                    $href = $this->_apiCore->generateHref('user', array(
                        'id' => $viewer->id,
                        'name' => $value,
                    ));

                    $viewer->update([
                        'name' => $value,
                        'href' => $href,
                    ]);
                    break;

                case 'email':
                    $row = User::where('email', $value)
                        ->where('id', '<>', $viewer->id)
                        ->first();
                    if ($row) {
                        $valid = false;
                        break;
                    }

                    $viewer->update([
                        'email' => $value,
                    ]);
                    break;

                case 'phone':
                    $viewer->update([
                        'phone' => $value,
                    ]);

                    //ref_code = thay so dt
                    if ($viewer->doiTacHopLe() && $viewer->don_vi == 'ca_nhan') {
                        $viewer->doiTacCode();
                    }
                    break;

                case 'cmnd':
                    $viewer->update([
                        'cmnd' => $value,
                    ]);
                    break;

                case 'chung_chi_hanh_nghe':
                    $viewer->update([
                        'chung_chi_hanh_nghe' => $value,
                    ]);
                    break;

                case 'thong_tin_chuyen_khoan':
                    $viewer->update([
                        'thong_tin_chuyen_khoan' => $value,
                    ]);
                    break;

                case 'dia_chi_nha':
                case 'dia_chi_cong_ty':
                    $viewer->update([
                        'address' => $value,
                        'province_id' => (isset($values['province_id'])) ? (int)$values['province_id'] : 0,
                        'district_id' => (isset($values['district_id'])) ? (int)$values['district_id'] : 0,
                        'ward_id' => (isset($values['ward_id'])) ? (int)$values['ward_id'] : 0,
                    ]);

                    return response()->json(['VALID' => $valid, 'ADDRESS' => $viewer->getFullAddress()]);
                    break;

            }
        }

        return response()->json(['VALID' => $valid]);
    }

    public function danhSachCapNhat(Request $request)
    {
        $values = $request->post();
        $title = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $phone = (isset($values['phone'])) ? $this->_apiCore->cleanStr($values['phone']) : NULL;
        $address = (isset($values['address'])) ? $this->_apiCore->cleanStr($values['address']) : NULL;
        $note = (isset($values['note'])) ? $this->_apiCore->cleanStr($values['note']) : NULL;
        $relationship = (isset($values['relationship'])) ? $this->_apiCore->cleanStr($values['relationship']) : NULL;
        $relationshipId = (isset($values['relationship_id'])) ? (int)$values['relationship_id'] : 0;
        $wardId = (isset($values['ward_id'])) ? (int)$values['ward_id'] : 0;
        $districtId = (isset($values['district_id'])) ? (int)$values['district_id'] : 0;
        $provinceId = (isset($values['province_id'])) ? (int)$values['province_id'] : 0;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        if (!empty($relationship)) {
            $row = UserRelationship::where('user_id', $this->_viewer->id)
                ->where('deleted', 0)
                ->where('title', $relationship)
                ->first();
            if (!$row) {
                $row = UserRelationship::create([
                    'user_id' => $this->_viewer->id,
                    'title' => $relationship
                ]);
                $relationshipId = $row->id;
            }
        }

        $updated = [
            'title' => $title,
            'phone' => $phone,
            'address' => $address,
            'note' => $note,
            'user_relationship_id' => $relationshipId,
            'ward_id' => $wardId,
            'province_id' => $provinceId,
            'district_id' => $districtId,
        ];

        $person = UserPerson::find($itemId);
        if ($person) {
            $person->update($updated);
        } else {
            $updated['user_id'] = $this->_viewer->id;
            UserPerson::create($updated);
        }

        return response()->json([]);
    }

    public function danhSach(Request $request)
    {
        $values = $request->post();
        $keyword= (isset($values['keyword'])) ? $this->_apiCore->cleanStr($values['keyword']) : NULL;
        $relationshipId = (isset($values['relationship_id'])) ? (int)$values['relationship_id'] : 0;
        $month = (isset($values['month'])) ? (int)$values['month'] : 0;

        $select = UserPerson::where('deleted', 0)
            ->where('user_id', $this->_viewer->id);

        if (!empty($keyword)) {
            $search = '%' . str_replace(' ', '%', $keyword . '%');

            $select->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', $search)
                    ->orWhere('phone', 'LIKE', $search)
                    ->orWhere('address', 'LIKE', $search)
                    ->orWhere('note', 'LIKE', $search);
            });
        }
        if ($relationshipId) {
            $select->where('user_relationship_id', $relationshipId);
        }

        $html = view('modals.front_end.user_person')
            ->with('items', $select->get())
            ->with('month', $month)
            ->render();

        return response()->json(['BODY' => $html]);
    }

    public function danhSachXoa(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $person = UserPerson::find($itemId);
        if ($person) {
            $person->delItem();
        }

        return response()->json([]);
    }

    public function suKienCapNhat(Request $request)
    {
        $values = $request->post();
        $title = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $note = (isset($values['note'])) ? $this->_apiCore->cleanStr($values['note']) : NULL;
        $budget = (isset($values['budget'])) ? $this->_apiCore->parseToInt($values['budget']) : 0;
        $day = (isset($values['day'])) ? (int)$values['day'] : 0;
        $month = (isset($values['month'])) ? (int)$values['month'] : 0;
        $personId = (isset($values['person_id'])) ? (int)$values['person_id'] : 0;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $updated = [
            'title' => $title,
            'budget' => $budget,
            'note' => $note,
            'day' => $day,
            'month' => $month,
        ];

        $date = UserPersonDate::find($itemId);
        if ($date) {
            $date->update($updated);
        } else {
            $updated['user_id'] = $this->_viewer->id;
            $updated['user_person_id'] = $personId;
            UserPersonDate::create($updated);
        }

        return response()->json(['PERSON' => $date->user_person_id]);
    }

    public function suKien(Request $request)
    {
        $values = $request->post();
        $personId = (isset($values['person_id'])) ? (int)$values['person_id'] : 0;

        $select = UserPerson::where('deleted', 0)
            ->where('user_id', $this->_viewer->id)
            ->where('id', $personId);

        $html = view('modals.front_end.user_person_item')
            ->with('person', $select->first())
            ->render();

        return response()->json(['BODY' => $html]);
    }

    public function suKienXoa(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $date = UserPersonDate::find($itemId);
        if ($date) {
            $date->delItem();

            $person = $date->getPerson();

            $html = view('modals.front_end.user_person_item')
                ->with('person', $person)
                ->render();

            return response()->json(['BODY' => $html, 'PERSON' => $person->id]);
        }

        return response()->json([]);
    }

    public function donHang(Request $request)
    {
        $params = $request->post();
//        echo '<pre>';var_dump($params);die;

        $selectCart = UserCart::query('user_carts')
            ->select('user_carts.*')
            ->leftJoin('user_persons', 'user_carts.user_person_id', '=', 'user_persons.id')
            ->leftJoin('user_relationships', 'user_persons.user_relationship_id', '=', 'user_relationships.id')
            ->where('user_carts.deleted', 0)
            ->where('user_carts.user_id', $this->_viewer->id)
            ->where('user_carts.status', '<>', 'moi_tao')
            ->orderByDesc('user_carts.id');

        if (isset($params['person_id']) && (int)$params['person_id']) {
            $selectCart->where('user_carts.user_person_id', (int)$params['person_id']);
        }
        if (isset($params['relationship_id']) && (int)$params['relationship_id']) {
            $selectCart->where('user_persons.user_relationship_id', (int)$params['relationship_id']);
        }
        if (isset($params['month']) && (int)$params['month']) {
            $selectCart->whereMonth('user_carts.created_at', (int)$params['month']);
        }
        if (isset($params['year']) && (int)$params['year']) {
            $selectCart->whereYear('user_carts.created_at', (int)$params['year']);
        }

        $selectSum = clone $selectCart;

        $html = view('modals.front_end.user_cart')
            ->with('carts', $selectCart->get())
            ->with('persons', $this->_viewer->getPersons())
            ->render();

        //sum
        $totalCart = $selectSum->sum("user_carts.total_cart");
        $totalPrice = $selectSum->sum("user_carts.total_price");
//        $totalCart = $selectSum->sum("total_discount");

        return response()->json(['BODY' => $html, 'CART' => $totalCart, 'PRICE' => $totalPrice]);
    }

    public function donHangCapNhat(Request $request)
    {
        $values = $request->post();
        $cartId = (isset($values['cart_id'])) ? (int)$values['cart_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $type = (isset($values['type'])) ? $this->_apiCore->cleanStr($values['type']) : NULL;

        $cart = UserCart::find($cartId);
        if ($cart) {
            $arr = [];

            if ($type == 'person') {
                $cart->update([
                    'user_person_id' => $value,
                    'user_person_date' => 0,
                ]);

                $dates = $this->_apiFE->getDates(['person_id' => $value]);
                if (count($dates)) {
                    foreach ($dates as $date) {
                        $arr[] = [
                            'id' => $date->id,
                            'title' => $date->title . ' - ' . $date->day . ' / ' . $date->month,
                        ];
                    }
                }

            } elseif($type == 'date') {
                $cart->update([
                    'user_person_date' => $value,
                ]);
            }

            return response()->json(['VALID' => true, 'ARR' => $arr]);
        }

        return response()->json(['VALID' => false]);
    }


}

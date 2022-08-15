<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserCart extends Item
{
    public $table = 'user_carts';

    protected $fillable = [
        'user_id', 'href',
        'total_cart', 'total_quantity', 'total_price', 'payment_by',
        'phone', 'email', 'address', 'note', 'name',
        'coupon_code', 'sale_id', 'total_percent', 'total_discount',
        'confirm_success',
        'refer_id', 'refer_percent', 'refer_money',
        'parent_refer_id', 'parent_refer_percent', 'parent_refer_money',
        'ht_refer_id', 'ht_refer_percent', 'ht_refer_money',
        'status', 'shipping', 'paid_date', 'note_manual',

        'total_ship', 'total_over', 'free_ship', 'ship_express',
        'ghn_code', 'trans_type', 'expected_delivery_time', 'shipping_status', 'total_price_ghn',

        'ward_id', 'district_id', 'province_id',

        'text_wish', 'user_person_id', 'user_person_date',

        'address_id', // 1 = dia chi ng tang , 2 = dia chi ng dc tang

        'deleted',
    ];

    //info
    public function getTitle()
    {
        return $this->href;
    }

    public function getPerson()
    {
        return UserPerson::find($this->user_person_id);
    }

    public function getPersonDate()
    {
        return UserPersonDate::find($this->user_person_date);
    }

    public function updateHref()
    {
        $href = date('ymd') . 'DH';

        $rows = UserCart::selectRaw('COUNT(id) as total')
            ->where('href', 'LIKE', $href)
            ->get();

        $count = (int)$rows[0]['total'];

        do {
            $count++;

            if ($count < 10) {
                $number = '00' . $count;
            } elseif ($count < 100) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }

            $href = date('ymd') . 'DH' . $number;

            $row = UserCart::where('href', $href)
                ->first();
            if (!$row) {
                break;
            }

        } while(1);

        $this->update([
            'href' => $href,
        ]);
    }

    public function getHref($fe = false)
    {
        return url('don-hang/') . '/' . $this->href;
    }

    public function toHTML($params = [])
    {
        $apiCore = new Core();
        $class = (count($params) && isset($params['class'])) ? $params['class'] : "";
        $href = (count($params) && isset($params['href']) && $params['href']) ? $this->getHref(true) : $this->getHref();
        if ($this->deleted) {
            return $this->getTitle();
        }
        $title = $this->getTitle();

        $htmlTitle = "<div class='c-title-href'>{$title}</div>";

        return '<a target="_blank" class="c-href-item ' . $class . '" title="' . $this->getTitle() . '" href="' . $href . '">' . $htmlTitle . '</a>';
    }

    public function delItem()
    {
        $apiFE = new FE();
        $apiCore = new Core();
        $apiCore->clearNotifications('cart', $this->id);

//        if (in_array($this->status, ["chua_thanh_toan", "da_thanh_toan"])) {
//            $this->isCancelled();
//        }

        //huy ghn
//        if (!empty($this->ghn_code) && $this->shipping_status == 'ready_to_pick') {
//            $apiFE->ghnCancelOrder($this);
//        }

        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function getProducts()
    {
        $select = UserCartProduct::where('deleted', 0)
            ->where('cart_id', $this->id)
            ->whereIn("product_id", function ($q1) {
                $q1->select('id')
                    ->from('products')
                    ->where('deleted', 0)
                    ->where('active', 1);
            })
            ->orderBy('id', 'asc');

        return $select->get();
    }

    public function getValidProducts()
    {
        $select = Product::query('products')
            ->select('products.*')
            ->leftJoin('user_cart_products', 'user_cart_products.product_id', '=', 'products.id')
            ->where('products.deleted', 0)
            ->where('products.active', 1)
            ->where('user_cart_products.cart_id', $this->id)
            ->orderBy('user_cart_products.id', 'asc');

        return $select->get();
    }

    public function updateCart()
    {
        $cart = $this;

        $products = Product::where('deleted', 0)
            ->where('active', 1)
            ->whereIn("id", function ($q1) use ($cart) {
                $q1->select('product_id')
                    ->from('user_cart_products')
                    ->where('deleted', 0)
                    ->where('cart_id', $cart->id);
            })
            ->orderByDesc('id')
            ->get();

        if (count($products)) {
            foreach ($products as $product) {
                $row = UserCartProduct::where('cart_id', $this->id)
                    ->where('product_id', $product->id)
                    ->where('deleted', 0)
                    ->first();

                if ($row) {
                    $row->update([
                        'price_main' => $product->price_main,
                        'discount' => $product->discount,
                        'price_pay' => $product->price_pay,
                        'sale_id' => $product->sale_id,
                        'sale_discount' => $product->sale_discount,
                    ]);
                }
            }
        }

        return $products;
    }

    public function getStatus()
    {
        $txt = "Mới Tạo";
        switch ($this->status) {
            case 'chua_thanh_toan':
                $txt = "Chưa Thanh Toán";
                break;

            case 'da_thanh_toan':
                $txt = "Đã Thanh Toán";
                break;

            case 'bi_huy':
                $txt = "Bị Hủy";
                break;
        }
        return $txt;
    }

    public function getShipStatus()
    {
        $txt = "Chờ Giao Hàng";
        switch ($this->status) {
            case 'da_giao':
                $txt = "Đã Giao Hàng";
                break;

            case 'da_nhan':
                $txt = "Đã Nhận Hàng";
                break;
        }
        return $txt;
    }

    public function getOwner()
    {
        return User::find((int)$this->user_id);
    }

    public function getRefer()
    {
        return User::find((int)$this->refer_id);
    }

    public function isCancelled()
    {
        $products = $this->getProducts();
        if (count($products)) {
            foreach ($products as $p) {
                $product = Product::find((int)$p->product_id);

                $sold = $product->sold_count - $p->quantity;
                $quantity = (!$product->unlimited) ? $product->quantity + $p->quantity : NULL;

                $product->update([
                    'sold_count' => $sold,
                    'quantity' => ($product->unlimited) ? NULL : $quantity,
                ]);

            }
        }
    }

    public function getPaymentText()
    {
        switch ($this->payment_by) {
            case 'banking':
                $text = "Chuyển Khoản Ngân Hàng";
                break;

            case 'vnpay':
                $text = "Thanh Toán VNPAY";
                break;

            case 'zalopay':
                $text = "Thanh Toán ZALOPAY";
                break;

            case 'momo':
                $text = "Thanh Toán MOMO";
                break;

            default:
                $text = "Tiền Mặt Khi Nhận Hàng";
        }

        return $text;
    }

    public function getFullAddress()
    {
        $text = $this->address;

        $ward = GhnWard::find($this->ward_id);
        $district = GhnDistrict::find($this->district_id);
        $province = GhnProvince::find($this->province_id);

        if ($ward) {
            $text .= ' ' . $ward->title;
        }
        if ($district) {
            $text .= ' ' . $district->title;
        }
        if ($province) {
            $text .= ' ' . $province->title;
        }

        return $text;
    }

    //
    public function duDoanDonHang()
    {
        $rows = Product::query('products')
            ->select('products.id', 'products.so_mieng', 'user_cart_products.quantity')
            ->leftJoin('user_cart_products', 'user_cart_products.product_id', '=', 'products.id')
            ->where('user_cart_products.deleted', 0)
            ->where('user_cart_products.cart_id', $this->id)
//            ->where(function($q) {
//                $q->where('products.product_category_id', 1)
//                    ->orWhere('products.product_category_id', 8);
//            })
            ->where('products.so_mieng', '>', 0)
            ->get();

        $total = 0;
        if (count($rows)) {
            foreach ($rows as $row) {
                $total += $row->so_mieng * $row->quantity;
            }
        }

        return $total && $total > 4 ? $total - 4 : 0;
    }

    public function createGHN($params = [])
    {
        $apiFE = new FE();
        $paid = (isset($params['paid'])) ? $params['paid'] : false;
        if ($this->confirm_success) {
            $paid = true;
        }

        $cartItems = [];
        $items = [];
        foreach ($this->getProducts() as $ite) {
            $product = Product::find($ite->product_id);
            if (!$product) {
                continue;
            }

            $cartItems[] = [
                'id' => $ite->product_id,
                'quantity' => $ite->quantity,
            ];

            $items[] = [
                'name' => $product->title,
                'code' => 'SP' . $product->id,
                'quantity' => $ite->quantity,
            ];
        }
        $ghnCart = $apiFE->thongSoDonHang($cartItems);

        $note = 'GECKOSO CART: ' . $this->href;
        if (!empty($this->note)) {
            $note = $this->note;
        }

        $cartName = (!empty($this->name)) ? $this->name : $this->phone;
        $toName = $this->getOwner() ? $this->getOwner()->name : $cartName;
        $toPhone = $this->getOwner() ? $this->getOwner()->phone : $this->phone;

        $district = GhnDistrict::find($this->district_id);
        $ward = GhnWard::find($this->ward_id);

        $arr = [
            'to_name' => $toName,
            'to_phone' => $toPhone,
            'to_address' => $this->getFullAddress(),
            'to_ward_code' => (string)$ward->code,
            'to_district_id' => $district->district_id,
            'cod_amount' => $paid ? 0 : $this->total_price,
            'content' => $note,
            'payment_type_id' => 1, //shop-seller
            'required_note' => 'KHONGCHOXEMHANG',
            'note' => $this->note,
            'service_type_id' => 2,
            'service_id' => $this->ship_express ? $this->ship_express : 2,
            'items' => $items,
        ];

        $arr = array_merge($arr, $ghnCart);

        $apiFE->ghnCreateOrder($this, $arr);
    }

    public function getGhnStatus()
    {
        $apiFE = new FE();

        return $apiFE->getGhnStatus($this->shipping_status);
    }

    //
    public function updateStore()
    {
        $products = $this->getProducts();
        if (count($products)) {
            foreach ($products as $p) {
                $product = Product::find((int)$p->product_id);

                $sold = $product->sold_count + $p->quantity;
                $quantity = (!$product->unlimited && $product->quantity >= $p->quantity) ? $product->quantity - $p->quantity : 0;

                $product->update([
                    'sold_count' => $sold,
                    'quantity' => ($product->unlimited) ? NULL : $quantity,
                ]);

                if (!$product->unlimited && !$quantity) {
                    $product->update([
                        'status' => 'het_hang',
                    ]);
                }
            }
        }
    }

    public function onPaymentSuccess($params = [])
    {
        $confirmId = (isset($params['confirm_success'])) ? (int)$params['confirm_success'] : 1;

        //for cod
        $this->update([
            'status' => 'da_thanh_toan',
            'confirm_success' => $confirmId,
            'paid_date' => date('Y-m-d H:i:s')
        ]);

        //mail commission
//        $this->mailCommission();

        //commission
//        $this->updateCommission();
    }

    public function onlinePaymentSuccess()
    {
        $apiCore = new Core();
        $this->updateHref();
        $user = $this->getOwner();
        $transaction = $this->getTransaction();
        $params = (array)json_decode($transaction->params);
//        echo '<pre>';var_dump($params);die;
        $totalQuantity = 0;

        //cart product
        foreach ($params['products'] as $ite) {
            $ite = (array)$ite;

            UserCartProduct::create([
                'cart_id' => $this->id,
                'product_id' => $ite['product_id'],
                'quantity' => $ite['quantity'],

                'price_main' => $ite['price_main'],
                'discount' => $ite['discount'],
                'price_pay' => $ite['price_pay'],
            ]);

            $totalQuantity += $ite['quantity'];
        }

        //cart info
        $this->update([
            'user_id' => $transaction->user_id,
            'status' => 'da_thanh_toan',

            'total_cart' => $params['tong_tien_hang'],
            'total_quantity' => $totalQuantity,
            'total_ship' => $params['phi_gh'],
            'total_over' => $params['vuot_qua_mien_phi_gh'],
            //da tru giam gia - [tong_thanh_toan_chua_phi_gh]
            'free_ship' => $params['vuot_qua_mien_phi_gh'] > 0 && $params['tong_thanh_toan_chua_phi_gh'] >= $params['vuot_qua_mien_phi_gh'] ? 1 : 0,
            'ship_express' => $params['gh_nhanh'],
            'total_price' => $params['tong_thanh_toan'],

            'payment_by' => $params['phuong_thuc'],

            'address' => $params['gh_dia_chi'],
            'province_id' => $params['gh_tinh_thanh'],
            'district_id' => $params['gh_quan_huyen'],
            'ward_id' => $params['gh_phuong_xa'],

            'name' => $params['kh_ten'],
            'phone' => $params['kh_dt'],
            'email' => $params['kh_email'],
            'note' => $params['ghi_chu'],

            //giam gia
            'total_percent' => $params['giam_gia_dac_biet_pt'],
            'total_discount' => $params['giam_gia_dac_biet'],

            'confirm_success' => 1, //super admin = system
            'paid_date' => date('Y-m-d H:i:s'),

            'time_book' => $transaction->created_at,

//            'coupon_code' => ,
//            'sale_id' => ,
        ]);

        //update cho khach hang cu
        if ($user && !$user->ward_id && $params['gh_phuong_xa']) {
            $user->update([
                'province_id' => $params['gh_tinh_thanh'],
                'district_id' => $params['gh_quan_huyen'],
                'ward_id' => $params['gh_phuong_xa'],
            ]);
        }

        //tru kho
        $this->updateStore();

        //hoa hong tu van
        $this->createCommission($params['ref_code']);

        //notify
        $this->notifyBooked();

        //mail remind
        $this->mailRemind();

        //mail commission
        $this->mailCommission();

        //ghn
        $this->createGHN([
            'paid' => true,
        ]);

        //commission
        $this->updateCommission();
    }

    public function mailCommission()
    {
        if ($this->refer_id) {
            MailQueue::create([
                'user_id' => $this->confirm_success,
                'item_id' => $this->id,
                'item_type' => 'cart',
                'type' => 'cart_paid',
            ]);
        }
    }

    public function mailRemind()
    {
        $user = $this->getOwner();
        $days = $this->duDoanDonHang();
        if ($days) {
            MailQueue::create([
                'user_id' => $user ? $user->id : 0,
                'item_id' => $this->id,
                'item_type' => 'cart',
                'type' => 'cart_next',
                'params' => json_encode([
                    'days' => $days,
                    'cart_id' => $this->id,
                ])
            ]);
        }
    }

    public function notifyBooked()
    {
        $apiCore = new Core();
        $user = $this->getOwner();

        //notify
        $apiCore->notifyAllStaffs('cart_new', [
            'subject_type' => 'cart',
            'subject_id' => $this->id,
            'object_type' => 'cart',
            'object_id' => $this->id,
        ]);

        //notify to supplier


        //send mail
        MailQueue::create([
            'user_id' => $user ? $user->id : 0,
            'item_id' => $this->id,
            'item_type' => 'cart',
            'type' => 'cart_booked',
        ]);
    }

    public function getTransaction()
    {
        $select = UserCartOnline::where('cart_id', $this->id)
            ->limit(1);
        return $select->first();
    }

    public function createCommission($refCode)
    {
        $apiCore = new Core();
        $validReferer = false; $refer = null; $referPercent = 0; $referMoney = 0; $parentReferId = 0; $parentReferPercent = 0; $parentReferMoney = 0;
        $percentTVTT = (float)$apiCore->getSetting('percent_tvtt');
        $percentTVGT = (float)$apiCore->getSetting('percent_tvgt');

        $user = $this->getOwner();
        if ($user && $user->doiTacHopLe()) {
            $refer = $user->doiTacCha();
        } else {
            //khach hang chua dang ki + da dang ki
            $refer = User::where('ref_code', $refCode)
                ->where('ref_code', '<>', NULL)
                ->limit(1)
                ->first();

            //khach da dang ki
            if ($user) {
                //nhap ma ng nao tinh cho ng do
                if (!$refer) {
                    $refer = $referMAIN;
                }

                //neu ko co refer main thi refer = referMAIN
                if (!$referMAIN && $refer) {
                    $user->update([
                        'care_id' => $refer->id,
                    ]);
                }

            } else {
                //khach chua dang ki
                //ktra so dt _ tim dh dau tien => carer
                $referMAIN = null;
                $cartFirst = UserCart::where('deleted', 0)
                    ->where('user_id', 0)
                    ->where('phone', $this->phone)
                    ->where('refer_id', '>', 0)
                    ->orderBy('id', 'asc')
                    ->limit(1)
                    ->first();
                if ($cartFirst) {
                    $referMAIN = User::find($cartFirst->refer_id);
                }

                //nhap ma ng nao tinh cho ng do
                if (!$refer) {
                    $refer = $referMAIN;
                }
            }
        }

        $referId = 0;
        if ($refer && $refer->doiTacHopLe()) {
            $validReferer = true;
            $referId = $refer->id;

            $referPercent = $percentTVTT;
            $referMoney = (int)($this->total_cart * $referPercent / 100); //tong_tien_hang

            $parentRefer = $refer->doiTacCha();
            if ($parentRefer && $parentRefer->doiTacHopLe()) {
                $parentReferId = $parentRefer->id;
                $parentReferPercent = $percentTVGT;
                $parentReferMoney = (int)($this->total_cart * $parentReferPercent / 100); //tong_tien_hang
            }
        } else {
            //ko co refer, ctv mua truc tiep
            if ($user && $user->doiTacHopLe()) {
                $validReferer = true;

                $referId = $user->id;
                $referPercent = $percentTVTT;
                $referMoney = (int)($this->total_cart * $referPercent / 100); //tong_tien_hang
            }
        }

        $this->update([
            'refer_id' => $validReferer ? $referId : 0,
            'refer_percent' => $validReferer ? $referPercent : 0,
            'refer_money' => $validReferer ? $referMoney : 0,

            'parent_refer_id' => $validReferer ? $parentReferId : 0,
            'parent_refer_percent' => $validReferer ? $parentReferPercent : 0,
            'parent_refer_money' => $validReferer ? $parentReferMoney : 0,
        ]);
    }

    public function updateCommission()
    {
        if ($this->user_id) {
            $user = User::find($this->user_id);
            if ($user) {
                $user->createCommission((int)date('m', strtotime($this->created_at)), (int)date('Y', strtotime($this->created_at)));
            }
        }

        if ($this->refer_id) {
            $user = User::find($this->refer_id);
            if ($user) {
                $user->createCommission((int)date('m', strtotime($this->created_at)), (int)date('Y', strtotime($this->created_at)));
            }
        }

        if ($this->parent_refer_id) {
            $user = User::find($this->parent_refer_id);
            if ($user) {
                $user->createCommission((int)date('m', strtotime($this->created_at)), (int)date('Y', strtotime($this->created_at)));
            }
        }
    }

    //card templates
    public function addPhotoCard($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $row = Photo::create([
            'item_type' => 'user_cart',
            'item_id' => $this->id,
            'type' => 'card_template',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function getPhotoCards()
    {
        $select = Photo::where('item_type', 'user_cart')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'card_template')
            ->orderBy('id', 'ASC');
        return $select->get();
    }

}

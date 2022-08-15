<?php

namespace App\Api;

use App\Model\UserPersonDate;
use App\Model\Wish;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

use \DateTime;
use \Session;

use App\User;
use App\Api\Core;

use App\Model\UserCartOnline;
use App\Model\UserCart;
use App\Model\UserView;
use App\Model\Photo;
use App\Model\File;
use App\Model\Setting;
use App\Model\Log;
use App\Model\ProductCategory;
use App\Model\Product;
use App\Model\ProductBrand;
use App\Model\News;
use App\Model\Event;
use App\Model\UserCartProduct;
use App\Model\UserWishlist;
use App\Model\GhnWard;
use App\Model\GhnDistrict;
use App\Model\GhnProvince;

class FE
{
//    =====================================================================

    public function getEvents($params = [])
    {
        $select = Event::where('deleted', 0)
            ->where('active', 1);

        if (isset($params['except']) && (int)$params['except']) {
            $select->where('id', '<>', (int)$params['except']);
        }

        if (isset($params['featured']) && (int)$params['featured']) {
            $select->where('featured', 1);
        }

        if (isset($params['not_featured']) && (int)$params['not_featured']) {
            $select->where('featured', 0);
        }

        if (isset($params['random']) && (int)$params['random']) {
            $select->orderByRaw('RAND()');
        } else {
            $select->orderBy('id', 'desc');
        }

        if (isset($params['only_one']) && (int)$params['only_one']) {
            return $select->limit(1)
                ->first();
        }

        if (isset($params['pagination']) && (int)$params['pagination']) {
            $limit = 16;
            if (isset($params['limit']) && (int)$params['limit']) {
                $limit = (int)$params['limit'];
            }
            $page = 1;
            if (isset($params['page']) && (int)$params['page']) {
                $page = (int)$params['page'];
            }

            return $select->paginate($limit, ['*'], 'page', $page);
        }

        if (isset($params['limit']) && (int)$params['limit']) {
            $select->limit((int)$params['limit']);
        }

        return $select->get();
    }

    public function getNews($params = [])
    {
        $select = News::where('deleted', 0)
            ->where('active', 1);

        if (isset($params['except']) && (int)$params['except']) {
            $select->where('id', '<>', (int)$params['except']);
        }

        if (isset($params['featured']) && (int)$params['featured']) {
            $select->where('featured', 1);
        }

        if (isset($params['not_featured']) && (int)$params['not_featured']) {
            $select->where('featured', 0);
        }

        if (isset($params['random']) && (int)$params['random']) {
            $select->orderByRaw('RAND()');
        } else {
            $select->orderBy('id', 'desc');
        }

        if (isset($params['only_one']) && (int)$params['only_one']) {
            return $select->limit(1)
                ->first();
        }

        if (isset($params['pagination']) && (int)$params['pagination']) {
            $limit = 16;
            if (isset($params['limit']) && (int)$params['limit']) {
                $limit = (int)$params['limit'];
            }
            $page = 1;
            if (isset($params['page']) && (int)$params['page']) {
                $page = (int)$params['page'];
            }

            return $select->paginate($limit, ['*'], 'page', $page);
        }

        if (isset($params['limit']) && (int)$params['limit']) {
            $select->limit((int)$params['limit']);
        }

        return $select->get();
    }

    public function getProducts($params = [])
    {
        $select = Product::where('deleted', 0)
            ->where('active', 1);

        $apiCore = new Core();
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $search = $apiCore->cleanStr($params['keyword']);
            $search = '%' . str_replace(' ', '%', $search) . '%';

            $select->where('title', 'LIKE', $search);
        }

        if (isset($params['except']) && (int)$params['except']) {
            $select->where('id', '<>', (int)$params['except']);
        }

        if (isset($params['is_new']) && (int)$params['is_new']) {
            $select->where('is_new', 1);
        }

        if (isset($params['is_best_seller']) && (int)$params['is_best_seller']) {
            $select->where('is_best_seller', 1);
        }

        if (isset($params['brand']) && (int)$params['brand']) {
            $select->where('product_brand_id', (int)$params['brand']);
        }

        if (isset($params['category']) && (int)$params['category']) {
            $select->where('product_category_id', (int)$params['category']);
        }

        if (isset($params['random']) && (int)$params['random']) {
            $select->orderByRaw('RAND()');
        } else {
            $select->orderBy('id', 'desc');
        }

        if (isset($params['only_one']) && (int)$params['only_one']) {
            return $select->limit(1)
                ->first();
        }

        if (isset($params['pagination']) && (int)$params['pagination']) {
            $limit = 16;
            if (isset($params['limit']) && (int)$params['limit']) {
                $limit = (int)$params['limit'];
            }
            $page = 1;
            if (isset($params['page']) && (int)$params['page']) {
                $page = (int)$params['page'];
            }

            return $select->paginate($limit, ['*'], 'page', $page);
        }

        if (isset($params['limit']) && (int)$params['limit']) {
            $select->limit((int)$params['limit']);
        }

        return $select->get();
    }

    public function getBrands($params = [])
    {
        $select = ProductBrand::where('deleted', 0)
            ->where('active', 1)
            ->orderByRaw('RAND()');

        if (isset($params['except']) && (int)$params['except']) {
            $select->where('id', '<>', (int)$params['except']);
        }

        if (isset($params['only_one']) && (int)$params['only_one']) {
            return $select->limit(1)
                ->first();
        }

        if (isset($params['limit']) && (int)$params['limit']) {
            $select->limit((int)$params['limit']);
        }

        return $select->get();
    }

    public function getViewedProducts($limit = 0, $except = 0)
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();
        $id = 0;
        if ($viewer) {
            $id = $viewer->id;
        }

        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->whereIn('id', function ($q) use ($id) {
                $q->select('product_id')
                    ->from('user_views')
                    ->where('user_id', $id);
            })
            ->orderByRaw('RAND()');
        if ($except) {
            $select->where('id', '<>', $except);
        }
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getDates($params = [])
    {
        $select = UserPersonDate::where('deleted', 0);

        if (isset($params['user_id']) && (int)$params['user_id']) {
            $select->where('user_id', (int)$params['user_id']);
        }

        if (isset($params['person_id']) && (int)$params['person_id']) {
            $select->where('user_person_id', (int)$params['person_id']);
        }

        return $select->get();
    }

//    =====================================================================



    public function getNewProducts($limit = 0)
    {
        $select = Product::where('deleted', 0)
            ->where('is_new', 1)
            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getHotProducts($limit = 0)
    {
        $select = Product::where('deleted', 0)
            ->where('is_best_seller', 1)
            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getRandomProducts($limit = 0)
    {
        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->where(function ($q) {
                $q->where('discount', '>', 0)
                    ->orWhere('is_new', 1)
                    ->orWhere('is_best_seller', 1);
            })
            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getSaleProducts($limit = 0)
    {
        $select = Product::where('deleted', 0)
            ->where('is_sale', 1)
            ->orderBy('id', 'DESC');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getTopBrands()
    {
        $select = ProductBrand::where('deleted', 0)
            ->where('active', 1)
            ->orderByRaw('TRIM(LOWER(title))');
        return $select->get();
    }

    public function getRandomBrands($limit = 0)
    {
        $select = ProductBrand::where('deleted', 0)
            ->where('active', 1)
            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getLastNews($limit = 0)
    {
        $select = News::where('deleted', 0)
            ->where('active', 1)
//            ->orderBy('id', 'DESC')
            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getLastEvents()
    {
        $select = Event::where('deleted', 0)
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->limit(4);
        return $select->get();
    }

    public function getProductCategories($limit = 0)
    {
        $select = ProductCategory::where('deleted', 0)
            ->where('level', 1)
            ->orderBy('id', 'asc');
//            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit((int)$limit);
        }
        return $select->get();
    }

    public function getSubCategories($categoryId)
    {
        $select = ProductCategory::where('deleted', 0)
            ->where("parent_id", $categoryId)
            ->orderBy('sort', 'asc');
        return $select->get();
    }

    public function getOtherNews($except = 0)
    {
        $select = News::where('deleted', 0)
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->limit(3);
        if ($except) {
            $select->where('id', '<>', $except);
        }
        return $select->get();
    }

    public function getRandomNews($limit = 0)
    {
        $select = News::where('deleted', 0)
            ->where('active', 1)
            ->orderBy('view_count', 'desc');
//            ->orderByRaw('RAND()');
        if ($limit) {
            $select->limit($limit);
        }
        return $select->get();
    }

    public function getOtherEvents($except = 0)
    {
        $select = Event::where('deleted', 0)
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->limit(3);
        if ($except) {
            $select->where('id', '<>', $except);
        }
        return $select->get();
    }

    public function getProductsByBrandId($id, $page = 1, $limit = 48)
    {
        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->where('product_brand_id', (int)$id)
            ->orderBy('id', 'desc');

        return $select->paginate($limit, ['*'], 'page', $page);
    }

    public function getProductsByCategoryId($id, $page = 1, $limit = 48)
    {
        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->orderBy('product_category_id', 'asc');

        $category = ProductCategory::find((int)$id);
        if ($category) {
            if ($category->level == 3) {
                $select->where('product_category_id', $category->id);
            } else {
                $select->where(function ($query1) use ($category) {
                    $query1->where("product_category_id", $category->id)
                        ->orWhereIn("product_category_id", $category->getAllChildren())
                        ->orWhereIn("id", $category->getProductOthers());
                });
            }
        } else {
            return [];
        }

        return $select->paginate($limit, ['*'], 'page', $page);
    }

    public function getSPLovedCount()
    {
        $count = 0;

        $apiCore = new Core();
        $viewer = $apiCore->getViewer();
        if ($viewer && $viewer->id) {
            $count = count($viewer->getWishlist());
        } else {
            $loved = (Session::get('USR_LOVE'));
            $count = $loved ? count($loved) : 0;
        }

        return $count;
    }

    public function getSPCartCount()
    {
        $count = 0;

        $apiCore = new Core();
        $viewer = $apiCore->getViewer();
        if ($viewer && $viewer->id) {
            $cart = $viewer->getCart();
            $products = $cart->getProducts();
            if (count($products)) {
                foreach ($products as $p) {
                    $count += $p->quantity;
                }
            }
        } else {
            $cartQty = (Session::get('USR_CART_QTY'));

            if ($cartQty) {
                foreach ($cartQty as $value) {
                    $count += (int)$value;
                }
            }
        }

        return $count;
    }

    public function sessionLovedCart($signUp = false)
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();

        if ($viewer) {
            //cart
            $temp = (Session::get('USR_CART'));
            $cartQty = (Session::get('USR_CART_QTY'));

            $cart = $viewer->getCart();

            if ($temp && count($temp)) {
                //empty cart
                UserCartProduct::where('cart_id', $cart->id)
                    ->delete();

                //add new cart
                foreach ($temp as $t) {
                    $product = Product::find((int)$t);
                    if (!$product) {
                        continue;
                    }

                    $quantity = 1;
                    if ($cartQty && count($cartQty) && isset($cartQty[$product->id])) {
                        $quantity = (int)$cartQty[$product->id];
                    }

                    UserCartProduct::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                    ]);
                }

                Session::forget('USR_CART');
                Session::forget('USR_CART_QTY');
            }

            $cart->updateCart();

            //love
            $temp = (Session::get('USR_LOVE'));
            if ($temp && count($temp)) {
                foreach ($temp as $t) {
                    $product = Product::find((int)$t);
                    if (!$product) {
                        continue;
                    }

                    $row = UserWishlist::where('user_id', $viewer->id)
                        ->where('product_id', $product->id)
                        ->first();
                    if (!$row) {
                        UserWishlist::create([
                            'user_id' => $viewer->id,
                            'product_id' => $product->id,
                        ]);

                        $product->update([
                            'love_count' => $product->love_count + 1,
                        ]);
                    }
                }

                Session::forget('USR_LOVE');
            }

            //view
            $temp = (Session::get('USR_VIEW'));
            if ($temp && count($temp)) {
                foreach ($temp as $t) {
                    $product = Product::find((int)$t);
                    if (!$product) {
                        continue;
                    }

                    $row = UserView::where('user_id', $viewer->id)
                        ->where('product_id', $product->id)
                        ->first();
                    if (!$row) {
                        UserView::create([
                            'user_id' => $viewer->id,
                            'product_id' => $product->id,
                        ]);
                    } else {
                        $row->update([
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                    }
                }

                Session::forget('USR_VIEW');
            }

            //care
            if ($signUp) {
                $temp = (Session::get('USR_CARE'));
                $dkDT = (Session::get('USR_DK_DT'));

                //viewer ko la doi tac
                if (!empty($temp)) {
                    $refer = User::where('ref_code', $temp)
                        ->first();
                    if ($refer) {
                        $viewer->update([
                            'care_id' => $refer->id,
                        ]);
                    }

                    Session::forget('USR_CARE');
                }

                //viewer co dang ki doi tac
                if ((int)$dkDT && $viewer->care_id) {
                    $viewer->update([
                        'care_id' => 0,
                        'ref_id' => $viewer->care_id,
                    ]);

                    Session::forget('USR_DK_DT');
                }

                if ((int)$dkDT) {
                    $viewer->troThanhDoiTac();
                }
            }
        }
    }

    //




    public function getProductsByArray($arr = [])
    {
        if (!count($arr)) {
            return [];
        }

        $ids = '';
        foreach ($arr as $i) {
            $ids .= (int)$i . ',';
        }
        $ids = substr($ids, 0, -1);

        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->whereIn('id', $arr)
            ->orderByRaw("FIELD(id, $ids)");
        return $select->get();
    }

    //ghn
    public function getProvinces()
    {
        $select = GhnProvince::orderBy('id', 'asc');
        return $select->get();
    }

    public function getDistrictsByProvinceId($provinceId)
    {
        $row = GhnProvince::find((int)$provinceId);
        if ($row) {
            $select = GhnDistrict::where('province_id', $row->province_id)
                ->orderBy('id', 'asc');

            return $select->get();
        }

        return [];
    }

    public function getWardsByDistrictId($districtId)
    {
        $row = GhnDistrict::find((int)$districtId);
        if ($row) {
            $select = GhnWard::where('district_id', $row->district_id)
                ->orderBy('id', 'asc');

            return $select->get();
        }

        return [];
    }

    public function thongSoDonHang($arr)
    {
        //default required
        $width = 15; //cm
        $height = 15; //cm
        $length = 15; //cm
        $weight = 200; //gram

        if (count($arr)) {
            $widthTemp = 0;
            $heightTemp = 0;
            $lengthTemp = 0;
            $weightTemp = 0;
            foreach ($arr as $ite) {
                $product = Product::find((int)$ite['id']);
                if ($product) {
                    $quantity = (int)$ite['quantity'];
                    $weightSP = $quantity * $product->khoi_luong;

                    if ($quantity > 16) {
                        $widthSP = (int)($quantity * $product->chieu_rong / 8);
                        $heightSP = (int)$quantity * $product->chieu_cao / 8;
                        $lengthSP = $product->chieu_dai * 5;
                    } elseif ($quantity > 8) {
                        $widthSP = (int)($quantity * $product->chieu_rong / 6);
                        $heightSP = (int)($quantity * $product->chieu_cao / 6);
                        $lengthSP = $product->chieu_dai * 4;
                    } elseif ($quantity > 4) {
                        $widthSP = (int)($quantity * $product->chieu_rong / 4);
                        $heightSP = (int)($quantity * $product->chieu_cao / 4);
                        $lengthSP = $product->chieu_dai * 3;
                    } else {
                        $widthSP = $quantity * $product->chieu_rong;
                        $heightSP = $quantity * $product->chieu_cao;
                        $lengthSP = $product->chieu_dai;
                    }


                    $widthTemp += $widthSP;
                    $heightTemp += $heightSP;
                    $lengthTemp += $lengthSP;
                    $weightTemp += $weightSP;
                }
            }

//            echo '<pre>';var_dump($weight, $length, $width, $height);

            if ($widthTemp > $width) {
                $width = $widthTemp;
            }
            if ($heightTemp > $height) {
                $height = $heightTemp;
            }
            if ($lengthTemp > $length) {
                $length = $lengthTemp;
            }
            if ($weightTemp > $weight) {
                $weight = $weightTemp;
            }

            //
            $weightGHN = (int)(($width * $height * $length) / 5);
//            $weightGHN = 0;

            //
            $width = $this->giamThongSoDonHang($width);
            $height = $this->giamThongSoDonHang($height);
            $length = $this->giamThongSoDonHang($length);

            //tru hao dong goi , de , ep
            if ($weight > 20000) {
                $weight -= 4000;
            } elseif ($weight > 15000) {
                $weight -= 3000;
            } elseif ($weight > 10000) {
                $weight -= 2000;
            } elseif ($weight > 5000) {
                $weight -= 1500;
            }

            if ($weightGHN && $weight > $weightGHN) {
                $weight = $weightGHN;
            }

//            echo '<pre>';var_dump($weight, $length, $width, $height, $weightGHN);

            if ($weight >= 20000) {
                $weight = 18000;
            }
        }

        return [
            'height' => $height,
            'length' => $length,
            'weight' => $weight,
            'width' => $width,
        ];
    }

    public function giamThongSoDonHang($value)
    {
        if ($value >= 200) {
            $value = 150;
        } elseif ($value >= 180) {
            $value = 140;
        } elseif ($value >= 160) {
            $value = 130;
        } elseif ($value >= 140) {
            $value = 120;
        } elseif ($value >= 120) {
            $value = 110;
        } elseif ($value >= 110) {
            $value = 100;
        }

        return $value;
    }

    public function ghnAPI($type)
    {
        $apiCore = new Core();

        //test
        $url = '';
        $token = "0acbf078-3537-11eb-93da-4612b8d25643";
        $shopId = '76358';

        if ($type == 'shipping_fee') {
            $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
        } elseif ($type == 'shipping_time') {
            $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/leadtime';
        } elseif ($type == 'create_order') {
            $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create';
        } elseif ($type == 'cancel_order') {
            $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel';
        } elseif ($type == 'get_service') {
            $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services';
        }

        return [
            'token' => $token,
            'shop_id' => $shopId,
            'url' => $url,
        ];
    }

    public function ghnGetService($params)
    {
        $default = 53321; //default = duong bo

        $api = $this->ghnAPI('get_service');

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Token: ' . $api['token'],
        ];

        $postData = array_merge($params, [
            'shop_id' => (int)$api['shop_id']
        ]);

//        echo '<pre>';var_dump($postData);

        curl_setopt($ch, CURLOPT_URL, $api['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

//        echo '<pre>';var_dump($statusCode, $result);die;

        if ($statusCode == 200) {
            $data = (array)json_decode($result);
            if (count($data) && isset($data['data'])) {
                $data = (array)$data['data'];

                if (count($data)) {
                    foreach ($data as $ite) {
                        $ite = (array)$ite;

                        if ($ite['short_name'] == 'Đi bộ') {
                            $default = (int)$ite['service_id'];
                        }

//                        echo '<pre>';var_dump($ite);
                    }
//                    die;
                }

            }
        }

        return $default;
    }

    public function ghnGetFee($params)
    {
//        echo '<pre>';var_dump($params);die;
        //api
        $api = $this->ghnAPI('shipping_fee');

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Token: ' . $api['token'],
            'ShopId: ' . $api['shop_id']
        ];

        $services = [
            'from_district' => 1452, //kho thanh thai q10 = 1452
            'to_district' => (int)$params['to_district_id'],
        ];

        $postData = [
            'service_id' => $this->ghnGetService($services), //default = duong bo
            'from_district_id' => 1452, //kho thanh thai q10 = 1452
//            'to_district_id' => 1444,
//            'to_ward_code' => 20314,
//            'height' => 15,
//            'length' => 15,
//            'weight' => 900, //khoi luong ko bao bi
//            'width' => 15,
        ];

        $postData = array_merge($postData, $params);

        if (!array_key_exists('service_type_id', $postData) || !isset($postData['service_type_id']) || empty($postData['service_type_id'])) {
            $postData['service_type_id'] = 2; //standard
        }

//        $postData['service_type_id'] = 0;

//        echo '<pre>';var_dump($postData);

        curl_setopt($ch, CURLOPT_URL, $api['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

//        echo '<pre>';var_dump($statusCode, $result);die;

        if ($statusCode == 200) {
            $data = (array)json_decode($result);
            if (count($data) && isset($data['data'])) {
                $data = (array)$data['data'];

                return $data['service_fee'];
            }
        }

        return "ERR";
    }

    public function ghnGetTime($params)
    {
//        echo '<pre>';var_dump($params);die;
        //api
        $api = $this->ghnAPI('shipping_time');

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Token: ' . $api['token'],
            'ShopId: ' . $api['shop_id']
        ];
        $postData = [
            'service_id' => 53321, //default = duong bo
            'from_district_id' => 1452, //kho thanh thai q10 = 1452
            'from_ward_code' => '21014', //p14
//            'to_district_id' => 1444,
//            'to_ward_code' => 20314,
        ];

        $postData = array_merge($postData, $params);

//        echo '<pre>';var_dump($postData);

        curl_setopt($ch, CURLOPT_URL, $api['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

//        echo '<pre>';var_dump($statusCode, $result);die;

        if ($statusCode == 200) {
            $data = (array)json_decode($result);
            if (count($data) && isset($data['data'])) {
                $data = (array)$data['data'];

                return [
                    'time' => $data['leadtime'],
                    'date' => $data['order_date'],
                ];
            }
        }

        return "ERR";
    }

    public function ghnCreateOrder($cart, $params)
    {
//        echo '<pre>';var_dump($params);die;
        //api
        $api = $this->ghnAPI('create_order');

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Token: ' . $api['token'],
            'ShopId: ' . $api['shop_id']
        ];
        $postData = [
            'from_district_id' => 1452, //kho thanh thai q10 = 1452
            'from_ward_code' => '21014', //p14
//            'to_district_id' => 1444,
//            'to_ward_code' => 20314,
        ];

        $postData = array_merge($postData, $params);

//        echo '<pre>';var_dump($postData);

        curl_setopt($ch, CURLOPT_URL, $api['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode == 200) {
            $data = (array)json_decode($result);
            if (count($data) && isset($data['data'])) {
                $data = (array)$data['data'];

                $cart->update([
                    'ghn_code' => $data['order_code'],
                    'trans_type' => $data['trans_type'],
                    'expected_delivery_time' => isset($data['expected_delivery_time']) ? date("Y-m-d H:i:s", strtotime($data['expected_delivery_time'])) : NULL,
                    'shipping_status' => 'ready_to_pick',
                ]);

                //update lai total_fee neu sai
                if ((int)$data['total_fee'] != $cart->total_ship) {
                    $oldShip = $cart->total_ship;

                    $cart->update([
                        'total_ship' => (int)$data['total_fee']
                    ]);

                    //
                    if (!$cart->free_ship) {
                        $cart->update([
                            'total_price' => $cart->total_price - $cart->total_discount - $oldShip + (int)$data['total_fee'],
                        ]);
                    }
                }

            }
        }

//        echo '<pre>';var_dump($statusCode, $result);die;

        return "ERR";
    }

    public function ghnCancelOrder($cart, $params = [])
    {
//        echo '<pre>';var_dump($params);die;
        //api
        $api = $this->ghnAPI('cancel_order');

        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Token: ' . $api['token'],
            'ShopId: ' . $api['shop_id']
        ];
        $postData = [
            'order_codes' => [$cart->ghn_code],
        ];

        $postData = array_merge($postData, $params);

//        echo '<pre>';var_dump($postData);

        curl_setopt($ch, CURLOPT_URL, $api['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode == 200) {
            $data = (array)json_decode($result);
            if (count($data) && isset($data['data'])) {
                $data = (array)$data['data'];
            }
        }

//        echo '<pre>';var_dump($statusCode, $result);die;

        return "ERR";
    }

    public function getShippingStatus()
    {
        return [
            'new' => 'Đã Đặt Hàng',
            'ready_to_pick' => 'Chờ Lấy Hàng',
            'picking' => 'Đang Lấy Hàng',
            'cancel' => 'Hủy Đơn',
            'money_collect_picking' => 'Liên Hệ Kho',
            'picked' => 'GHN Đã Lấy Hàng',
            'storing' => 'Giữ Tại Kho GHN',
            'transporting' => 'Hàng Đang Luân Chuyển',
            'sorting' => 'Hàng Đang Phân Loại',
            'delivering' => 'Đang Giao Hàng Cho Khách',
            'money_collect_delivering' => 'Đang Thu Tiền Của Khách',
            'delivered' => 'Đã Giao Hàng',
            'delivery_fail' => 'Khách Chưa Nhận Hàng',
            'waiting_to_return' => 'Trả Kho Chờ Giao Lại',
            'return' => 'Trả Hàng Sau 3 Lần Giao Hàng Thất Bại',
            'return_transporting' => 'Luân Chuyển Trả Kho',
            'return_sorting' => 'Phân Loại Trả Kho',
            'returning' => 'Đang Trả Hàng Về',
            'return_fail' => 'Trả Hàng Về Thất Bại',
            'returned' => 'Đã Trả Hàng Về',
            'exception' => 'Lí Do Khác',
            'damage' => 'Hàng Bị Hư Hại',
            'lost' => 'Hàng Bị Mất',
        ];
    }

    public function getGhnStatus($value)
    {
        $text = '';

        switch ($value) {
            case 'ready_to_pick':
                $text = 'Chờ Lấy Hàng';
                break;
            case 'picking':
                $text = 'Đang Lấy Hàng';
                break;
            case 'cancel':
                $text = 'Hủy Đơn';
                break;
            case 'money_collect_picking':
                $text = 'Liên Hệ Kho';
                break;
            case 'picked':
                $text = 'GHN Đã Lấy Hàng';
                break;
            case 'storing':
                $text = 'Giữ Tại Kho GHN';
                break;
            case 'transporting':
                $text = 'Hàng Đang Luân Chuyển';
                break;
            case 'sorting':
                $text = 'Hàng Đang Phân Loại';
                break;
            case 'delivering':
                $text = 'Đang Giao Hàng Cho Khách';
                break;
            case 'money_collect_delivering':
                $text = 'Đang Thu Tiền Của Khách';
                break;
            case 'delivered':
                $text = 'Đã Giao Hàng';
                break;
            case 'delivery_fail':
                $text = 'Khách Chưa Nhận Hàng';
                break;
            case 'waiting_to_return':
                $text = 'Trả Kho Chờ Giao Lại';
                break;
            case 'return':
                $text = 'Trả Hàng Sau 3 Lần Giao Hàng Thất Bại';
                break;
            case 'return_transporting':
                $text = 'Luân Chuyển Trả Kho';
                break;
            case 'return_sorting':
                $text = 'Phân Loại Trả Kho';
                break;
            case 'returning':
                $text = 'Đang Trả Hàng Về';
                break;
            case 'return_fail':
                $text = 'Trả Hàng Về Thất Bại';
                break;
            case 'returned':
                $text = 'Đã Trả Hàng Về';
                break;
            case 'exception':
                $text = 'Lí Do Khác';
                break;
            case 'damage':
                $text = 'Hàng Bị Hư Hại';
                break;
            case 'lost':
                $text = 'Hàng Bị Mất';
                break;
            case 'new':
            default:
                $text = 'Đã Đặt Hàng';
        }

        return $text;
    }

    //cart
    public function cartBookedCOD($values)
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();

        //params
        $params = [
            //cod, banking
            'phuong_thuc' => (isset($values['payment_by'])) ? $values['payment_by'] : NULL,
            'ghi_chu' => (isset($values['note'])) ? $values['note'] : NULL,
            'kh_ten' => (isset($values['name'])) ? $values['name'] : NULL,
            'kh_dt' => (isset($values['phone'])) ? $values['phone'] : NULL,
            'kh_email' => (isset($values['email'])) ? $values['email'] : NULL,
            'gh_dia_chi' => (isset($values['address'])) ? $values['address'] : NULL,
            'gh_tinh_thanh' => (isset($values['province_id'])) ? (int)$values['province_id'] : 0,
            'gh_quan_huyen' => (isset($values['district_id'])) ? (int)$values['district_id'] : 0,
            'gh_phuong_xa' => (isset($values['ward_id'])) ? (int)$values['ward_id'] : 0,
            //calculated
            'tong_tien_hang' => (isset($values['total_all'])) ? $apiCore->parseToInt($values['total_all']) : 0,
            'tong_thanh_toan' => (isset($values['total_paid'])) ? $apiCore->parseToInt($values['total_paid']) : 0,
            'tong_thanh_toan_chua_phi_gh' => (isset($values['total_paid_no_ship'])) ? $apiCore->parseToInt($values['total_paid_no_ship']) : 0,
            //giam gia dac biet
            'giam_gia_dac_biet' => (isset($values['total_discount'])) ? $apiCore->parseToInt($values['total_discount']) : 0,
            'giam_gia_dac_biet_pt' => (isset($values['discount_gg'])) ? (float)$values['discount_gg'] : 0,
            //over
            'vuot_qua_mien_phi_gh' => (isset($values['over_cart'])) ? $apiCore->parseToInt($values['over_cart']) : 0,
            'phi_gh' => (isset($values['ghn_fee'])) ? $apiCore->parseToInt($values['ghn_fee']) : 0,
            'gh_nhanh' => (isset($values['express'])) ? $apiCore->parseToInt($values['express']) : 0,
            //ref_code
            'ref_code' => (isset($values['ref_code'])) ? $values['ref_code'] : NULL,
            //chua dung
            'code_km' => (isset($values['coupon'])) ? $values['coupon'] : NULL,

            'cau_chuc_co_san' => (isset($values['cau_chuc_co_san'])) ? $apiCore->parseToInt($values['cau_chuc_co_san']) : 0,
            'cau_chuc_tu_viet' => (isset($values['cau_chuc_tu_viet'])) ? $values['cau_chuc_tu_viet'] : NULL,
            'cau_chuc_id' => (isset($values['cau_chuc_id'])) ? $apiCore->parseToInt($values['cau_chuc_id']) : 0,
            'mau_thiep_co_san' => (isset($values['mau_thiep_co_san'])) ? $apiCore->parseToInt($values['mau_thiep_co_san']) : 0,
            'mau_thiep_id' => (isset($values['mau_thiep_id'])) ? $apiCore->parseToInt($values['mau_thiep_id']) : 0,

            'user_person_id' => (isset($values['person_id'])) ? $apiCore->parseToInt($values['person_id']) : 0,
            'user_person_date' => (isset($values['date_id'])) ? $apiCore->parseToInt($values['date_id']) : 0,
            'address_id' => (isset($values['address_id'])) ? $apiCore->parseToInt($values['address_id']) : 1,

        ];

        $ids = (isset($values['ids'])) ? $values['ids'] : NULL;
        $ids = array_filter(explode(';', $ids));

        if ($viewer) {
            $cart = $viewer->getCart();

            UserCartProduct::where('cart_id', $cart->id)
                ->delete();

            //create cart
            $cart = UserCart::create([
                'user_id' => $viewer->id,
            ]);
        } else {
            Session::forget('USR_CART');
            Session::forget('USR_CART_QTY');

            //create cart
            $cart = UserCart::create([
                'user_id' => 0,
            ]);
        }

        $cart->updateHref();

        $totalQuantity = 0;

        foreach ($ids as $i) {
            $arr = explode('_', $i);

            $product = Product::find((int)$arr[0]);
            if ($product && (int)$arr[1]) {
                UserCartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => (int)$arr[1],

                    'price_main' => $product->price_main,
                    'discount' => $product->discount,
                    'price_pay' => $product->price_pay,
                ]);

                $totalQuantity += (int)$arr[1];
            }
        }

        $freeShip = 0;
        if ($params['vuot_qua_mien_phi_gh'] > 0 && $params['tong_thanh_toan_chua_phi_gh'] >= $params['vuot_qua_mien_phi_gh']) {
            $freeShip = 1;
        }
        //free 1 so tinh/thanh
        $freeCity = false;
        $row = Setting::where('title', 'payment_ship_free_city')->first();
        if ($row && !empty($row->value)) {
            $arr = (array)json_decode($row->value);
            if (count($arr) && in_array($params['gh_tinh_thanh'], $arr)) {
                $freeCity = true;
            }
        }
        $freeShip = $freeShip && $freeCity ? 1 : 0;

        //wish
        $textWish = $params['cau_chuc_tu_viet'];
        if ($params['cau_chuc_co_san'] > 0 && $params['cau_chuc_id']) {
            $wish = Wish::find((int)$params['cau_chuc_id']);
            if ($wish) {
                $textWish = $wish->title;
            }
        }

        //cart info
        $cart->update([
            'user_id' => $viewer ? $viewer->id : 0,
            'status' => 'chua_thanh_toan',

            'total_cart' => $params['tong_tien_hang'],
            'total_quantity' => $totalQuantity,
            'total_ship' => $params['phi_gh'],
            'total_over' => $params['vuot_qua_mien_phi_gh'],
            //da tru giam gia - [tong_thanh_toan_chua_phi_gh]
            'free_ship' => $freeShip,
            'ship_express' => 0, //$params['gh_nhanh'],
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

            'time_book' => date('Y-m-d H:i:s'),
            'shipping_status' => 'new',

            'text_wish' => $textWish,

            'user_person_id' => $params['user_person_id'],
            'user_person_date' => $params['user_person_date'],
            'address_id' => $params['address_id'],
        ]);

        //update cho khach hang cu
        if ($viewer && !$viewer->ward_id && $params['gh_phuong_xa']) {
            $viewer->update([
                'province_id' => $params['gh_tinh_thanh'],
                'district_id' => $params['gh_quan_huyen'],
                'ward_id' => $params['gh_phuong_xa'],
            ]);
        }

        //tru kho
//        $cart->updateStore();

        //hoa hong tu van
//        $cart->createCommission($params['ref_code']);

        //notify
        $cart->notifyBooked();

        //mail remind
//        $cart->mailRemind();

        //ghn
//        if ($params['phuong_thuc'] == 'cod') {
//            $cart->createGHN();
//        }

        $apiCore->clearCache();

        return $cart;
    }

    public function cartBookedOnline($values)
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();
        $cartParams = [];

        //params
        $ids = (isset($values['ids'])) ? $values['ids'] : NULL;
        $ids = array_filter(explode(';', $ids));

        $params = [
            //cod, vnpay, zalopay, momo
            'phuong_thuc' => (isset($values['payment_by'])) ? $values['payment_by'] : NULL,
            'ghi_chu' => (isset($values['note'])) ? $values['note'] : NULL,
            'kh_ten' => (isset($values['name'])) ? $values['name'] : NULL,
            'kh_dt' => (isset($values['phone'])) ? $values['phone'] : NULL,
            'kh_email' => (isset($values['email'])) ? $values['email'] : NULL,
            'gh_dia_chi' => (isset($values['address'])) ? $values['address'] : NULL,
            'gh_tinh_thanh' => (isset($values['province_id'])) ? (int)$values['province_id'] : 0,
            'gh_quan_huyen' => (isset($values['district_id'])) ? (int)$values['district_id'] : 0,
            'gh_phuong_xa' => (isset($values['ward_id'])) ? (int)$values['ward_id'] : 0,
            //calculated
            'tong_tien_hang' => (isset($values['total_all'])) ? $apiCore->parseToInt($values['total_all']) : 0,
            'tong_thanh_toan' => (isset($values['total_paid'])) ? $apiCore->parseToInt($values['total_paid']) : 0,
            'tong_thanh_toan_chua_phi_gh' => (isset($values['total_paid_no_ship'])) ? $apiCore->parseToInt($values['total_paid_no_ship']) : 0,
            //giam gia dac biet
            'giam_gia_dac_biet' => (isset($values['total_discount'])) ? $apiCore->parseToInt($values['total_discount']) : 0,
            'giam_gia_dac_biet_pt' => (isset($values['discount_gg'])) ? (float)$values['discount_gg'] : 0,
            //over
            'vuot_qua_mien_phi_gh' => (isset($values['over_cart'])) ? $apiCore->parseToInt($values['over_cart']) : 0,
            'phi_gh' => (isset($values['ghn_fee'])) ? $apiCore->parseToInt($values['ghn_fee']) : 0,
            'gh_nhanh' => (isset($values['express'])) ? $apiCore->parseToInt($values['express']) : 0,
            //ref_code
            'ref_code' => (isset($values['ref_code'])) ? $values['ref_code'] : NULL,
            //chua dung
            'code_km' => (isset($values['coupon'])) ? $values['coupon'] : NULL,
        ];

        //cart products
        $cartProducts = [];
        foreach ($ids as $i) {
            $arr = explode('_', $i);

            $product = Product::find((int)$arr[0]);
            if ($product && (int)$arr[1]) {
                $cartProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => (int)$arr[1],

                    'price_main' => $product->price_main,
                    'discount' => $product->discount,
                    'price_pay' => $product->price_pay,
                ];
            }
        }

        //user
        if ($viewer) {
            $cart = $viewer->getCart();

            UserCartProduct::where('cart_id', $cart->id)
                ->delete();

            foreach ($cartProducts as $ite) {
                UserCartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $ite['product_id'],
                    'quantity' => $ite['quantity'],

                    'price_main' => $ite['price_main'],
                    'discount' => $ite['discount'],
                    'price_pay' => $ite['price_pay'],
                ]);
            }

            $cartParams['user_id'] = $viewer->id;

            UserCartOnline::where('user_id', $viewer->id)
                ->where('status', 'new')
                ->delete();
        } else {

            $cart = [];
            $cartQty = [];

            foreach ($cartProducts as $ite) {
                $cart[] = $ite['product_id'];

                $cartQty[$ite['product_id']] = $ite['quantity'];
            }

            Session::put('USR_CART', $cart);
            Session::put('USR_CART_QTY', $cartQty);

            $apiCore->clearCache();

            $cartParams['ip_address'] = $this->getIp();

            UserCartOnline::where('user_id', 0)
                ->where('ip_address', $cartParams['ip_address'])
                ->where('status', 'new')
                ->delete();
        }

        //create
        $params['products'] = $cartProducts;

        $cartParams['params'] = json_encode($params);
        $cartParams['type'] = $params['phuong_thuc'];
        $cartParams['amount'] = $params['tong_thanh_toan'];

        return UserCartOnline::create($cartParams);
    }

    //sms
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Product extends Item
{
    public $table = 'products';

    protected $fillable = [
        'title', 'href', 'sort',

        'product_category_id', 'product_brand_id', 'product_supplier_id',

        'price_main', 'discount', 'price_pay',

        'quantity', 'unlimited', 'active', 'is_new', 'is_best_seller', 'status',

        'made_in', 'can_nang', 'kich_thuoc', 'the_tich', 'tu_khoa_seo', 'mo_ta_ngan', 'combo', 'parent_id', 'video_link',

        'mo_ta', 'mo_ta_text', 'cong_dung', 'cong_dung_text', 'thanh_phan', 'thanh_phan_text', 'huong_dan_su_dung', 'huong_dan_su_dung_text',

        'sold_count', 'view_count', 'love_count', 'star_count',

        'deleted',
    ];

    //info
    public function getHref($fe = false)
    {
        return url('san-pham') . '/' . $this->href;
    }

    public function toHTML($params = [])
    {
        $apiCore = new Core();
        $class = (count($params) && isset($params['class'])) ? $params['class'] : "";
        $href = (count($params) && isset($params['href']) && $params['href']) ? $this->getHref(true) : $this->getHref();
        $avatar = (count($params) && isset($params['avatar']) && $params['avatar']) ? true : false;
        $short = (count($params) && isset($params['short']) && $params['short']) ? true : false;
        if ($this->deleted) {
            return $this->getTitle();
        }
        $htmlAvatar = '';
        if ($avatar) {
            $img = $this->getAvatar('profile');
            $htmlAvatar = '<div class="margin-right-5 c-avatar-href" style="background-image:url(\'' . $img . '\')"></div>';
        }
        $title = $this->getTitle();
        if ($short) {
            $title = $this->getShortTitle();
        }

        $htmlTitle = "<div class='c-title-href'>{$title}</div>";

        return '<a target="_blank" class="c-href-item ' . $class . '" title="' . $this->getTitle() . '" href="' . $href . '">' . $htmlAvatar . $htmlTitle . '</a>';
    }

    public function getCategory()
    {
        return ProductCategory::find((int)$this->product_category_id);
    }

    public function getBrand()
    {
        return ProductBrand::find((int)$this->product_brand_id);
    }

    public function getSupplier()
    {
        return UserSupplier::find((int)$this->product_supplier_id);
    }

    public function getLabel($type)
    {
        $apiCore = new Core();
        $text = "";
        switch ($type) {
            case 'category':
                $row = ProductCategory::find((int)$this->product_category_id);
                if ($row) {
                    $text = $row->getTitle();
                }
                break;
            case 'brand':
                $row = ProductBrand::find((int)$this->product_brand_id);
                if ($row) {
                    $text = $row->getTitle();
                }
                break;
            case 'made_in':
                foreach ($apiCore->listCountries() as $k => $v) {
                    if ($k == $this->made_in) {
                        $text = $v;
                        break;
                    }
                }
                break;
            case 'status':
                $text = ($this->status == 'con_hang') ? "Còn Hàng" : "Hết Hàng";
                break;
        }
        return $text;
    }

    public function getCategoryOthers()
    {
        $select = ProductCategory::query('product_categories')
            ->select('product_categories.*')
            ->leftJoin('product_categories_other', 'product_categories_other.category_id', '=', 'product_categories.id')
            ->where('product_categories_other.product_id', $this->id)
            ->orderBy('product_categories_other.id', 'asc');

        return $select->get();
    }

    public function isLoved()
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();
        $boo = false;
        if ($viewer) {
            $row = UserWishlist::where('user_id', $viewer->id)
                ->where('product_id', $this->id)
                ->first();
            $boo = ($row) ? true : false;
        } else {
            $loved = (Session::get('USR_LOVE'));
            if ($loved && count($loved) && in_array($this->id, $loved)) {
                $boo = true;
            }
        }

        return $boo;
    }

    public function isViewed()
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();

        if ($viewer) {
            $row = UserView::where('user_id', $viewer->id)
                ->where('product_id', $this->id)
                ->first();
            if ($row) {
                $row->update([
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            } else {
                UserView::create([
                    'user_id' => $viewer->id,
                    'product_id' => $this->id,
                ]);
            }
        } else {
            $viewed = (Session::get('USR_VIEW'));
            if (!$viewed || !count($viewed)) {
                $viewed = [];
            }

            if (count($viewed) && in_array(!$this->id, $viewed)) {
                $viewed[] = $this->id;
            }

            Session::put('USR_VIEW', $viewed);
        }

        $this->update([
            'view_count' => $this->view_count + 1,
        ]);
    }

    public function canBuy()
    {
        return ($this->active && !$this->deleted && $this->status == 'con_hang') ? true : false;
    }

    public function isBought($user, $params = [])
    {
        $apiCore = new Core();
        if ($user) {
            $rows = UserCartProduct::query('user_cart_products')
                ->leftJoin('user_carts', 'user_cart_products.cart_id', '=', 'user_carts.id')
                ->where('user_cart_products.product_id', $this->id)
                ->where('user_carts.user_id', $user->id)
                ->where('user_carts.status', 'da_thanh_toan')
                ->get();
            if (count($rows)) {
                return true;
            }

        } else {
            if (count($params)
                && isset($params['phone']) && !empty($params['phone'])
//                && isset($params['email']) && !empty($params['email'])
            ) {
                $rows = UserCartProduct::query('user_cart_products')
                    ->leftJoin('user_carts', 'user_cart_products.cart_id', '=', 'user_carts.id')
                    ->where('user_cart_products.product_id', $this->id)
                    ->where('user_carts.user_id', 0)
                    ->where('phone', $apiCore->cleanStr($params['phone']))
//                    ->where('email', $apiCore->cleanStr($params['email']))
                    ->where('user_carts.status', 'da_thanh_toan')
                    ->get();
                if (count($rows)) {
                    return true;
                }
            }
        }

        return false;
    }

    //avatar
    public function getAvatar($thumb = "")
    {
        $URL = url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'product')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'avatar')
            ->first();
        if ($row) {
            $URL = $row->getPhoto($thumb);
        }

        return $URL;
    }

    public function uploadAvatar($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $rows = Photo::where('item_type', 'product')
            ->where('item_id', $this->id)
            ->where('type', 'avatar')
            ->get();
        if (count($rows)) {
            foreach ($rows as $row) {
                $diskPath = public_path('/' . $row->path);
                $diskPath = $apiCore->platformSlashes($diskPath);
                if (is_file($diskPath) && !empty($row->thumb)) {
                    unlink($diskPath);
                }

                $row->delete();
            }
        }

        $row = Photo::create([
            'item_type' => 'product',
            'item_id' => $this->id,
            'type' => 'avatar',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function removeAvatar()
    {
        $row = Photo::where('item_id', $this->id)
            ->where('item_type', 'product')
            ->where('type', 'avatar')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }

    //slides
    public function addSlides($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $row = Photo::create([
            'item_type' => 'product',
            'item_id' => $this->id,
            'type' => 'slides',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function getSlides()
    {
        $select = Photo::where('item_type', 'product')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'slides')
            ->orderBy('id', 'ASC');
        return $select->get();
    }

    public function removeSlides()
    {
        $olds = Photo::where('item_id', $this->id)
            ->where('item_type', 'product')
            ->where('type', 'slides')
            ->where('parent_id', 0)
            ->get();
        foreach ($olds as $old) {
            $old->delItem();
        }
    }

    //delete
    public function delItem()
    {
        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    //review
    public function getReviews($params = [])
    {
        $select = ProductReview::query('product_reviews')
            ->select('product_reviews.*')
            ->leftJoin('users', 'users.id', '=', 'product_reviews.user_id')
            ->where('product_reviews.product_id', $this->id)
            ->where('product_reviews.active', 1)
            ->orderByDesc('product_reviews.id')
//            ->orderBy('star', 'desc')
//            ->orderBy('users.chuyen_gia', 'desc')
            ;

        $arr = [];
        if (count($params)) {
            if (isset($params['count']) && (int)$params['count']) {
                return $select->get();
            }

            //
            if (isset($params['max']) || isset($params['limit'])) {
                $reviews = $select->get();
                if (count($reviews)) {

                    $pagi = false;
                    if (isset($params['max'])) {
                        $pagi = true;
                    }

                    foreach ($reviews as $review) {
                        if (count($arr) == (int)$params['limit']) {
                            break;
                        }

                        if (!$pagi) {
                            $arr[] = $review;
                        } else {
                            if ($review->id == (int)$params['max']) {
                                $pagi = false;
                            }
                        }
                    }
                }
            }
        }

        return $arr;
    }

    public function countReviews()
    {
        return count($this->getReviews(['count' => 1]));
    }

    public function getReviewStar($star)
    {
        $select = ProductReview::where('product_id', $this->id)
            ->where('active', 1)
            ->where('star', (int)$star);
        return $select->get();
    }

    public function countReviewStar($star)
    {
        return count($this->getReviewStar($star));
    }

    public function reCalStar()
    {
        $reviews = ProductReview::where('product_id', $this->id)
            ->where('active', 1)
            ->get();
        $star = 0;
        if (count($reviews)) {
            foreach ($reviews as $review) {
                $star += $review->star;
            }

            $star = $star / count($reviews);

            if ($star - (int)$star) {
                $star = (int)$star + 0.5;
            }
        }

        $this->update([
            'star_count' => $star,
        ]);
    }
}

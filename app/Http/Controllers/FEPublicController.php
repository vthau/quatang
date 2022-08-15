<?php

namespace App\Http\Controllers;

use App\Model\CardTemplate;
use App\Model\Wish;
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

class FEPublicController extends Controller
{
    protected $_apiCore = null;
    protected $_apiFE = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiFE = new FE();
    }

    //general
    public function getOpts(Request $request)
    {
        $values = $request->post();
        $type = (isset($values['type'])) ? $this->_apiCore->cleanStr($values['type']) : NULL;
        $itemId = (isset($values['id'])) ? $this->_apiCore->parseToInt($values['id']) : 0;

        if (in_array($type, ['district', 'ward'])) {
            $item = null;
            if ($type == 'district') {
                $item = GhnProvince::find($itemId);
            } elseif ($type == 'ward') {
                $item = GhnDistrict::find($itemId);
            }

            if ($item) {
                $arr = [];
                $items = [];

                if ($type == 'district') {
                    $items = GhnDistrict::where('province_id', $item->province_id)
                        ->orderBy('id', 'asc')
                        ->get();
                } elseif ($type == 'ward') {
                    $items = GhnWard::where('district_id', $item->district_id)
                        ->orderBy('id', 'asc')
                        ->get();
                }

                if (count($items)) {
                    foreach ($items as $ite) {
                        $arr[] = [
                            'id' => $ite->id,
                            'title' => $ite->title,
                        ];
                    }
                }

                return response()->json(['VALID' => true, 'ARR' => $arr]);
            }
        }

        return response()->json(['VALID' => false]);
    }

    public function getOptWish(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['id'])) ? $this->_apiCore->parseToInt($values['id']) : 0;
        $arr = [];

        $items = Wish::where('deleted', 0)
            ->where('active', 1)
            ->where('system_category_id', $itemId)
            ->get();

        if (count($items)) {
            foreach ($items as $ite) {
                $arr[] = [
                    'id' => $ite->id,
                    'title' => $ite->title,
                ];
            }
        }

        return response()->json(['VALID' => true, 'ARR' => $arr]);
    }

    public function getOptCard(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['id'])) ? $this->_apiCore->parseToInt($values['id']) : 0;
        $arr = [];

        $items = CardTemplate::where('deleted', 0)
            ->where('active', 1)
            ->where('system_category_id', $itemId)
            ->get();

        if (count($items)) {
            foreach ($items as $ite) {
                $arr[] = [
                    'id' => $ite->id,
                    'title' => $ite->title,
                ];
            }
        }

        return response()->json(['VALID' => true, 'ARR' => $arr]);
    }

    public function getWish(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['id'])) ? $this->_apiCore->parseToInt($values['id']) : 0;
        $body = '';

        $item = Wish::find($itemId);
        if ($item) {
            $body = nl2br($item->title);
        }

        return response()->json(['VALID' => true, 'BODY' => $body]);
    }

    public function getCard(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['id'])) ? $this->_apiCore->parseToInt($values['id']) : 0;
        $arr = [];

        $item = CardTemplate::find($itemId);
        if ($item) {
            $slides = $item->getSlides();
            if (count($slides)) {
                foreach ($slides as $photo) {
                    $arr[] = $photo->getPhoto();
                }
            }
        }

        return response()->json(['VALID' => true, 'ARR' => $arr]);
    }
}

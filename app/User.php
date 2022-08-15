<?php

namespace App;

use App\Model\UserPerson;
use App\Model\UserRelationship;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use App\Api\Core;
use \DateTime;

use App\Model\UserBlock;
use App\Model\LevelAction;
use App\Model\LevelPermission;
use App\Model\Photo;
use App\Model\UserLevel;
use App\Model\Product;
use App\Model\UserCart;
use App\Model\NotificationType;
use App\Model\Notification;
use App\Model\Test;
use App\Model\TestDetail;
use App\Model\TestQuestion;
use App\Model\GhnDistrict;
use App\Model\GhnProvince;
use App\Model\GhnWard;
use App\Model\Setting;
use App\Model\UserCommission;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone',
        'address', 'ward_id', 'district_id', 'province_id',
        'href', 'level_id', 'supplier_id',
        'time_notification', 'note', 'deleted',

        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin()
    {
        return $this->level_id == 1 ? true : false;
    }

    public function isAdmin()
    {
        return $this->level_id == 2 ? true : false;
    }

    public function fullPermissions()
    {
        return ($this->isSuperAdmin() || $this->isAdmin()) ? true : false;
    }

    public function isStaff()
    {
        return !$this->isCustomer();
    }

    public function isCustomer()
    {
        return $this->level_id == 4 ? true : false;
    }

    public function isSupplier()
    {
        return $this->level_id == 5 && $this->supplier_id ? true : false;
    }

    //blocked
    public function isBlocked()
    {
        $row = UserBlock::where('user_id', $this->id)
            ->first();
        return ($row) ? true : false;
    }

    public function blockReason()
    {
        $row = UserBlock::where('user_id', $this->id)
            ->first();
        return ($row && !empty($row->reason)) ? $row->reason : "";
    }

    //info
    public function getTitle()
    {
        return $this->name;
    }

    public function getHref($fe = false)
    {
        $URL = url('admin/profile/') . '/' . $this->href;
        if ($fe) {
        }
        return $URL;
    }

    public function getLevel()
    {
        return UserLevel::find($this->level_id);
    }

    public function toHTML($params = [])
    {
        $class = (count($params) && isset($params['class'])) ? $params['class'] : "";
        $href = (count($params) && isset($params['href']) && $params['href']) ? $this->getHref(true) : $this->getHref();
        $avatar = (count($params) && isset($params['avatar']) && $params['avatar']) ? true : false;
        if ($this->deleted) {
            return $this->getTitle();
        }
        $htmlAvatar = '';
        if ($avatar) {
            $img = $this->getAvatar('profile');
            $htmlAvatar = '<div class="margin-right-5 c-avatar-href" style="background-image:url(\'' . $img . '\')"></div>';
        }
        $htmlTitle = "<div class='c-title-href'>{$this->getTitle()}</div>";
        return '<a target="_blank" class="c-href-item ' . $class . '" title="' . $this->getTitle() . '" href="' . $href . '">' . $htmlAvatar . $htmlTitle . '</a>';
    }

    public function getWishlist()
    {
        $user = $this;

        $select = Product::where('deleted', 0)
            ->where('active', 1)
            ->whereIn("id", function ($q1) use ($user) {
                $q1->select('product_id')
                    ->from('user_wishlists')
                    ->where('user_id', $user->id);
            })
            ->orderByDesc('id');

        return $select->get();
    }

    public function getCart()
    {
        $cart = UserCart::where('user_id', $this->id)
            ->where('status', 'moi_tao')
            ->where('deleted', 0)
            ->first();

        if (!$cart) {
            $cart = UserCart::create([
                'user_id' => $this->id,
            ]);
        }

        return $cart;
    }

    public function getFirstTimeCart()
    {


        // if (!$cart) {
        //     $cart = UserCart::create([
        //         'user_id' => $this->id,
        //     ]);
        // }

        $text = "Chưa có";

        $cart = UserCart::where('user_id', $this->id)
            ->whereNotNull('href')
            ->orderBy('created_at', 'ASC')
            ->first();

        if ($cart) {
            $text = $cart->created_at;
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

    public function getPersons()
    {
        $select = UserPerson::where('user_id', $this->id)
            ->orderByRaw('TRIM(LOWER(title))');
        return $select->get();
    }

    public function getRelationships()
    {
        $select = UserRelationship::where('deleted', 0)
            ->where('user_id', $this->id)
            ->orderByRaw('TRIM(LOWER(title))');
        return $select->get();
    }

    public function createRelationships()
    {
        $arr = [];

        $row = UserRelationship::create([
            'user_id' => $this->id,
            'title' => 'Gia Đình',
        ]);
        $arr[] = $row;

        $row = UserRelationship::create([
            'user_id' => $this->id,
            'title' => 'Bạn Bè',
        ]);
        $arr[] = $row;

        $row = UserRelationship::create([
            'user_id' => $this->id,
            'title' => 'Đồng Nghiệp',
        ]);
        $arr[] = $row;

        $row = UserRelationship::create([
            'user_id' => $this->id,
            'title' => 'Đối Tác',
        ]);
        $arr[] = $row;

        $row = UserRelationship::create([
            'user_id' => $this->id,
            'title' => 'Khách Hàng',
        ]);
        $arr[] = $row;

        return $arr;
    }

    //avatar
    public function getAvatar($thumb = "")
    {
        $URL = url('public/images/default_user.png');

        $row = Photo::where('item_type', 'user')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'avatar')
            ->first();
        if ($row) {
            $URL = $row->getPhoto($thumb);
        }

        return $URL;
    }

    public function removeAvatar()
    {
        $row = Photo::where('item_id', $this->id)
            ->where('item_type', 'user')
            ->where('type', 'avatar')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }

    public function uploadAvatar($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $rows = Photo::where('item_type', 'user')
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
            'item_type' => 'user',
            'item_id' => $this->id,
            'type' => 'avatar',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    //allowed
    public function isAllowed($actionName, $params = array())
    {
        $can = false;

        if ($this->fullPermissions()) {
            return true;
        }

        $row = LevelPermission::query("level_permissions")
            ->leftJoin("level_actions", "level_actions.id", "=", "level_permissions.action_id")
            ->select("level_permissions.is_allowed")
            ->where("level_actions.title", "=", $actionName)
            ->where("level_permissions.level_id", "=", $this->level_id)
            ->first();
        if ($row) {
            $can = ((int)$row->is_allowed) ? true : false;
        } else {
            $action = LevelAction::where("title", "=", $actionName)->first();
            if ($action) {
                return $action->getAllowed($this->level_id);
            }
        }

        return $can;
    }

    //notify
    public function getNewNotifications()
    {
        if (empty($this->time_notification)) {
            $this::update([
                'time_notification' => date("Y-m-d H:i:s"),
            ]);
        }

        $rows = Notification::query("notifications")
            ->where("user_id", "=", $this->id)
            ->where("created_at", ">=", $this->time_notification)
            ->orderByDesc("id")
            ->get();
        return $rows;
    }

    public function getNotifications($limit = 20, $maxId = 0)
    {
        $select = Notification::query("notifications")
            ->where("user_id", "=", $this->id);
        if ($maxId) {
            $select->where("id", "<", $maxId);
        }
        $rows = $select
            ->orderByDesc("id")
            ->limit($limit)
            ->get();
        return $rows;
    }

    //delete
    public function delItem()
    {
        $apiCore = new Core();
        $apiCore->clearNotifications('user', $this->id);

        $this->update([
            'password' => Hash::make("del888"),
            'deleted' => 1,
            'email' => $this->email . "_" . time(),
            'phone' => $this->phone . "_" . time(),
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }
}

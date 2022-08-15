<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class News extends Item
{
    public $table = 'news';

    protected $fillable = [
        'title', 'href', 'mo_ta', 'mo_ta_text', 'active', 'deleted', 'view_count',
        'mo_ta_ngan', 'featured',
    ];

    //info
    public function getHref($fe = false)
    {
        return url('tin') . '/' . $this->href;
    }

    public function getShortTitle($short = 20)
    {
        $length = $short - 5;
        return strlen($this->getTitle()) >= $short ? mb_substr($this->getTitle(), 0, $length) . "..." : $this->getTitle();
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

    public function isViewed()
    {
        $this->update([
            'view_count' => $this->view_count + 1,
        ]);
    }

    //avatar
    public function getAvatar($thumb = "")
    {
        $URL = url('public/images/default_blog.webp');

        $row = Photo::where('item_type', 'news')
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

        $rows = Photo::where('item_type', 'news')
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
            'item_type' => 'news',
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
            ->where('item_type', 'news')
            ->where('type', 'avatar')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }

    //banner
    public function getBanner($thumb = "")
    {
        $URL = ""; //url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'news')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'banner')
            ->first();
        if ($row) {
            $URL = $row->getPhoto($thumb);
        }

        return $URL;
    }

    public function uploadBanner($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $rows = Photo::where('item_type', 'news')
            ->where('item_id', $this->id)
            ->where('type', 'banner')
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
            'item_type' => 'news',
            'item_id' => $this->id,
            'type' => 'banner',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function removeBanner()
    {
        $row = Photo::where('item_id', $this->id)
            ->where('item_type', 'news')
            ->where('type', 'banner')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
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
}

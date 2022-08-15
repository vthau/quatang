<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserSupplier extends Item
{
    public $table = 'user_suppliers';

    protected $fillable = [
        'title', 'href', 'sort', 'deleted', 'active', 'view_count'
    ];

    //info
    public function getHref($fe = false)
    {
        return url('nha-cung-cap') . '/' . $this->href;
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
        $URL = ""; //url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'user_supplier')
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

        $rows = Photo::where('item_type', 'user_supplier')
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
            'item_type' => 'user_supplier',
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
            ->where('item_type', 'user_supplier')
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

        $row = Photo::where('item_type', 'user_supplier')
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

        $rows = Photo::where('item_type', 'user_supplier')
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
            'item_type' => 'user_supplier',
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
            ->where('item_type', 'user_supplier')
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
        //nhan vien
        User::where('supplier_id', $this->id)
            ->update([
                'deleted' => 1,
            ]);

        //thuong hieu
        ProductBrand::where('supplier_id', $this->id)
            ->update([
                'deleted' => 1,
            ]);

        //san pham
        Product::where('product_supplier_id', $this->id)
            ->update([
                'active' => 0,
                'deleted' => 1,
            ]);

        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted ? true : false;
    }
}

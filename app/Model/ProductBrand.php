<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class ProductBrand extends Item
{
    public $table = 'product_brands';

    protected $fillable = [
        'title', 'href', 'sort', 'deleted', 'active', 'view_count', 'supplier_id',
    ];

    //info
    public function getHref($fe = false)
    {
        return url('thuong-hieu') . '/' . $this->href;
    }

    public function getSupplier()
    {
        return UserSupplier::find($this->supplier_id);
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

        $row = Photo::where('item_type', 'product_brand')
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

        $rows = Photo::where('item_type', 'product_brand')
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
            'item_type' => 'product_brand',
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
            ->where('item_type', 'product_brand')
            ->where('type', 'avatar')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }

    //delete
    public function delItem()
    {
        //update related
        Product::where('product_brand_id', $this->id)
            ->update([
                'product_brand_id' => 0,
            ]);

        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted ? true : false;
    }

    //banner
    public function getBanner($thumb = "")
    {
        $URL = ""; //url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'product_brand')
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

        $rows = Photo::where('item_type', 'product_brand')
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
            'item_type' => 'product_brand',
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
            ->where('item_type', 'product_brand')
            ->where('type', 'banner')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }


}

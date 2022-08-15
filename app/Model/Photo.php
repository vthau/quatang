<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Intervention\Image\ImageManagerStatic as Image;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Photo extends Item
{
    public $table = 'photos';

    protected $fillable = [
        'item_type', 'item_id', 'parent_id', 'thumb', 'type', 'sort', 'path', 'name'
    ];

    public function createThumb($params = [])
    {
        $apiCore = new Core();

        $storagePath = str_replace($this->name, '', $this->path);

        $diskPath = $apiCore->platformSlashes(public_path('/') . $this->path);
        if (is_file($diskPath)) {
            //thumb normal
            $thumbName = "n_" . $this->name;
            $thumbNormal = Image::make($diskPath);
            $thumbNormal->resize(400, 400, function($constraint) {
                $constraint->aspectRatio();
            });
            $thumbNormal->save(public_path($storagePath . $thumbName));
            Photo::create([
                'item_type' => $this->item_type,
                'item_id' => $this->item_id,
                'type' => $this->type,
                'parent_id' => $this->id,
                'thumb' => 'normal',
                'name' => $thumbName,
                'path' => $storagePath . $thumbName,
            ]);

            //thumb profile
            $thumbName = "p_" . $this->name;
            $thumbProfile = Image::make($diskPath);
            $thumbProfile->resize(150, 150, function($constraint) {
                $constraint->aspectRatio();
            });
            $thumbNormal->save(public_path($storagePath . $thumbName));
            Photo::create([
                'item_type' => $this->item_type,
                'item_id' => $this->item_id,
                'type' => $this->type,
                'parent_id' => $this->id,
                'thumb' => 'profile',
                'name' => $thumbName,
                'path' => $storagePath . $thumbName,
            ]);
        }
    }

    public function getPhoto($thumb = "")
    {
        $URL = url('public') . $this->path;

        if (!empty($thumb)) {
            $thumb = Photo::where('item_type', $this->item_type)
                ->where('item_id', $this->item_id)
                ->where('type', $this->type)
                ->where('thumb', $thumb)
                ->where('parent_id', $this->id)
                ->first();
            if ($thumb) {
                $URL = url('public') . $thumb->path;
            }
        }

        return $URL;
    }

    public function delItem()
    {
        $apiCore = new Core();

        $subs = Photo::where('item_type', $this->item_type)
            ->where('item_id', $this->item_id)
            ->where('parent_id', $this->id)
            ->where('type', $this->type)
            ->get();
        if (count($subs)) {
            foreach ($subs as $sub) {
                $sub->delItem();
            }
        }

        $storagePath = public_path('/' . $this->path);
        $storagePath = $apiCore->platformSlashes($storagePath);
        if (is_file($storagePath)) {
            unlink($storagePath);
        }

        $this->delete();
    }
}

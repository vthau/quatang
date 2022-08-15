<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class ProductCategory extends Item
{
    public $table = 'product_categories';

    protected $fillable = [
        'title', 'href', 'parent_id', 'sort', 'deleted', 'level', 'is_menu',
        'view_count'
    ];

    //info
    public function getHref()
    {
        return url('danh-muc/') . '/' . $this->href;
    }

    public function getProducts()
    {
        $select = Product::where('deleted', 0)
            ->where('product_category_id', $this->id);
        return $select->get();
    }

    public function isViewed()
    {
        $this->update([
            'view_count' => $this->view_count + 1,
        ]);
    }

    public function getAllChildren()
    {
        $children = [];

        if ($this->level == 1) {
            $rows = ProductCategory::where('deleted', 0)
                ->where('parent_id', $this->id)
                ->get();
            if (count($rows)) {
                foreach ($rows as $row) {
                    $children[] = $row->id;

                    $leaf = $row->getAllChildren();
                    if (count($leaf)) {
                        $children = array_merge($children, $leaf);
                    }
                }
            }
        } elseif ($this->level == 2) {
            $rows = ProductCategory::select('id')
                ->where('deleted', 0)
                ->where('parent_id', $this->id)
                ->get();
            if (count($rows)) {
                foreach ($rows as $row) {
                    $children[] = $row->id;
                }
            }
        }

        return $children;
    }

    public function getParent()
    {
        return ProductCategory::find($this->parent_id);
    }

    public function getProductOthers()
    {
        $products = [];

        $others = ProductCategoryOther::where('category_id', $this->id)
            ->get();
        if (count($others)) {
            foreach ($others as $other) {
                if (in_array($other->product_id, $products)) {
                    continue;
                }

                $products[] = $other->product_id;
            }
        }

        return $products;
    }

    public function getSubCategories()
    {
        $select = ProductCategory::where('deleted', 0)
            ->where("parent_id", $this->id)
            ->orderBy('sort', 'asc');
        return $select->get();
    }

    public function had2Childs()
    {
        $had = false;
        $subs = $this->getSubCategories();
        if (count($subs)) {
            foreach ($subs as $sub) {
                $childs = $sub->getSubCategories();
                if (count($childs)) {
                    $had = true;
                    break;
                }
            }
        }
        return $had;
    }

    //delete
    public function delItem()
    {
        //update related
        Product::where('product_category_id', $this->id)
            ->update([
                'product_category_id' => 0,
            ]);

        $subs = ProductCategory::where("parent_id", $this->id)->get();
        if ($subs) {
            foreach ($subs as $sub) {
                Product::where('product_category_id', $sub->id)
                    ->update([
                        'product_category_id' => 0,
                    ]);

                $sub->update([
                    'deleted' => 1,
                ]);

                $childs = ProductCategory::where("parent_id", $sub->id)->get();
                if ($childs) {
                    foreach ($childs as $child) {
                        Product::where('product_category_id', $child->id)
                            ->update([
                                'product_category_id' => 0,
                            ]);

                        $child->update([
                            'deleted' => 1,
                        ]);
                    }
                }
            }
        }

        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted ? true : false;
    }

    //avatar
    public function getAvatar()
    {
        $avatar = url('public') . '/images/we_care.png';

        $product = Product::where('product_category_id', $this->id)
            ->orderByRaw('RAND()')
            ->limit(1)
            ->first();
        if ($product) {
            $avatar = $product->getAvatar();
        } else {
            $cateId = 0;
            $cates = $this->getSubCategories();
            if (count($cates)) {
                foreach ($cates as $cate) {
                    $cateId = $cate->id;
                    break;
                }
            }

            $product = Product::where('product_category_id', $cateId)
                ->orderByRaw('RAND()')
                ->limit(1)
                ->first();

            if ($product) {
                $avatar = $product->getAvatar();
            }
        }

        return $avatar;
    }

    //banner
    public function getBanner($thumb = "")
    {
        $URL = ""; //url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'product_category')
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

        $rows = Photo::where('item_type', 'product_category')
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
            'item_type' => 'product_category',
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
            ->where('item_type', 'product_category')
            ->where('type', 'banner')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }

    //banner mobi
    public function getBannerMobi($thumb = "")
    {
        $URL = ""; //url('public/images/no_photo.jpg');

        $row = Photo::where('item_type', 'product_category')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'banner_mobi')
            ->first();
        if ($row) {
            $URL = $row->getPhoto($thumb);
        }

        return $URL;
    }

    public function uploadBannerMobi($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $rows = Photo::where('item_type', 'product_category')
            ->where('item_id', $this->id)
            ->where('type', 'banner_mobi')
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
            'item_type' => 'product_category',
            'item_id' => $this->id,
            'type' => 'banner_mobi',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function removeBannerMobi()
    {
        $row = Photo::where('item_id', $this->id)
            ->where('item_type', 'product_category')
            ->where('type', 'banner_mobi')
            ->where('parent_id', 0)
            ->first();
        if ($row) {
            $row->delItem();
        }
    }


}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserCategory extends Item
{
    public $table = 'user_categories';

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
        $select = User::where('deleted', 0)
            ->where('user_category_id', $this->id);
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
            $rows = UserCategory::where('deleted', 0)
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
            $rows = UserCategory::select('id')
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
        return UserCategory::find($this->parent_id);
    }

    public function getProductOthers()
    {
        $products = [];

        $others = UserCategoryOther::where('category_id', $this->id)
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
        $select = UserCategory::where('deleted', 0)
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
        User::where('user_category_id', $this->id)
            ->update([
                'user_category_id' => 0,
            ]);

        $subs = UserCategory::where("parent_id", $this->id)->get();
        if ($subs) {
            foreach ($subs as $sub) {
                User::where('user_category_id', $sub->id)
                    ->update([
                        'user_category_id' => 0,
                    ]);

                $sub->update([
                    'deleted' => 1,
                ]);

                $childs = UserCategory::where("parent_id", $sub->id)->get();
                if ($childs) {
                    foreach ($childs as $child) {
                        User::where('user_category_id', $child->id)
                            ->update([
                                'user_category_id' => 0,
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
}

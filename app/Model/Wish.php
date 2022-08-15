<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Wish extends Item
{
    public $table = 'wishes';

    protected $fillable = [
        'title', 'active', 'system_category_id', 'deleted',
    ];

    //info
    public function getCategory()
    {
        return SystemCategory::find($this->system_category_id);
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
        return $this->deleted ? true : false;
    }
}

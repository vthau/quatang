<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class SystemCategory extends Item
{
    public $table = 'system_categories';

    protected $fillable = [
        'title', 'active', 'deleted',
    ];

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

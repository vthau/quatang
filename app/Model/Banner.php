<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Banner extends Item
{
    public $table = 'banners';

    protected $fillable = [
        'title', 'href', 'img', 'img', 'img_mobi', 'display',
    ];
}

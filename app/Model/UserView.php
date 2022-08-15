<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserView extends Item
{
    public $table = 'user_views';

    protected $fillable = [
        'user_id', 'product_id', 'view_count'
    ];

}

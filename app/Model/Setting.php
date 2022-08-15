<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Setting extends Item
{
    public $table = 'settings';

    protected $fillable = [
        'title', 'value'
    ];

}

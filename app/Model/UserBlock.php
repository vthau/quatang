<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserBlock extends Item
{
    public $table = 'user_blocks';

    protected $fillable = [
        'user_id', 'reason', 'time_block',
    ];

}

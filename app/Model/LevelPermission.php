<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class LevelPermission extends Item
{
    public $table = 'level_permissions';

    protected $fillable = [
        'level_id', 'action_id', 'is_allowed', 'params'
    ];

}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class NotificationType extends Item
{
    public $table = 'notification_types';

    protected $fillable = [
        'title', 'body', 'icon'
    ];

}

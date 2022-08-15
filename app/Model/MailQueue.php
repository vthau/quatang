<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class MailQueue extends Item
{
    public $table = 'mail_queues';

    protected $fillable = [
        'user_id', 'item_type', 'item_id', 'type', 'params',
    ];


}

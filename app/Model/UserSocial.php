<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserSocial extends Model
{
    public $table = 'user_socials';

    protected $fillable = [
        'user_id', 'type', 'social_id', 'social_token', 'email',
    ];

}

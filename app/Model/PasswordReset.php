<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class PasswordReset extends Item
{
    public $table = 'password_resets';

    protected $fillable = [
        'email', 'token'
    ];

}

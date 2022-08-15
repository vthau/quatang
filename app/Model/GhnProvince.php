<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class GhnProvince extends Model
{
    public $table = 'ghn_provinces';

    protected $fillable = [
        'province_id', 'title', 'code',
    ];

}

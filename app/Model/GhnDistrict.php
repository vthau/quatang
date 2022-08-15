<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class GhnDistrict extends Model
{
    public $table = 'ghn_districts';

    protected $fillable = [
        'province_id', 'district_id', 'title', 'code',
    ];

}

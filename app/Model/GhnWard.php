<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class GhnWard extends Model
{
    public $table = 'ghn_wards';

    protected $fillable = [
        'district_id', 'title', 'code',
    ];

}

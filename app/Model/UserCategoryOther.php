<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserCategoryOther extends Model
{
    public $table = 'user_categories_other';

    protected $fillable = [
        'category_id', 'user_id',
    ];
}

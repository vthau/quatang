<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class ProductCategoryOther extends Model
{
    public $table = 'product_categories_other';

    protected $fillable = [
        'category_id', 'product_id',
    ];

}

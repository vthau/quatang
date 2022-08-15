<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserCartProduct extends Item
{
    public $table = 'user_cart_products';

    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'price_main',
        'discount', 'price_pay', 'sale_id', 'sale_discount',
        'price_end', 'deleted_by', 'deleted'
    ];

}

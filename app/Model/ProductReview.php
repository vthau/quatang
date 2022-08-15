<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class ProductReview extends Item
{
    public $table = 'product_reviews';

    protected $fillable = [
        'user_id', 'product_id', 'phone', 'email', 'note', 'star',
        'active',
    ];

    public function getUser()
    {
        return User::find($this->user_id);
    }

    public function getProduct()
    {
        return Product::find($this->product_id);
    }
}

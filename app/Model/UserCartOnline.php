<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserCartOnline extends Item
{
    public $table = 'user_carts_online';

    protected $fillable = [
        'user_id', 'ip_address', 'cart_id', 'type', 'amount', 'status',

        'vp_transaction_number', 'vp_bank_code', 'vp_bank_transaction_number', 'vp_card_type', 'vp_pay_date',

        'zp_app_transaction_id', 'zp_transaction_id', 'zp_server_time', 'zp_channel', 'zp_merchant_user_id',
        'zp_user_fee_amount', 'zp_discount_amount',

        'params',
    ];

    public function getCart()
    {
        return UserCart::find($this->cart_id);
    }

    public function getZalopayProducts()
    {
        $arr = [];
        $params = (array)json_decode($this->params);
        if (isset($params['products'])) {
            foreach ($params['products'] as $ite) {
                $ite = (array)$ite;
                $product = Product::find((int)$ite['product_id']);

                $arr[] = [
                    'itemid' => $this->id,
                    'itemname' => $product->title,
                    'itemquantity' => $ite['quantity'],
                    'itemprice' => $ite['price_pay'],
                ];
            }
        }

        return $arr;
    }


}

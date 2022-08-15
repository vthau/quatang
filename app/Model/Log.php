<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Log extends Item
{
    public $table = 'logs';

    protected $fillable = [
        'user_id', 'action', 'item_type', 'item_id', 'params'
    ];

    //user_level_add, user_level_edit, user_level_delete, user_level_config_
    //user_edit, user_add, user_delete, user_block, user_unblock, user_update_info, user_update_password, user_update_avatar
    //product_category_add, product_category_edit, product_category_delete, product_category_update
    //product_brand_add, product_brand_edit, product_brand_delete, product_brand_update
    //product_add, product_edit, product_update, product_delete, product_review_update, product_excel_update_size, product_excel_import, product_excel_export
    //product_review_edit, product_review_add, product_review_update, product_review_delete
    //order_config, order_delete, order_ghn, order_manual, order_confirm_paid, order_confirm_delete, order_manual_confirm
    //test_question_edit, test_question_add, test_question_delete, partner_config
    //partner_edit, partner_add, partner_update
    //event_add, event_edit, event_update, event_delete
    //report_excel_export
    //client_add
    //text_update
    //contact_delete
    //setting_update
    //news_edit, news_add, news_update, news_delete

}

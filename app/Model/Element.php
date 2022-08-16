<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Element extends Item
{
    public $table = 'elements';

    protected $fillable = [
        'title', 'key', 'order', 'display',
    ];

    public static function sort($element_ids)
    {
        foreach ($element_ids as $key =>  $element_id) {
            Element::where('id', $element_id)->update(['order' =>  $key]);
        }
    }
}

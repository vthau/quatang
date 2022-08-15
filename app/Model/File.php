<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class File extends Item
{
    public $table = 'files';

    protected $fillable = [
        'item_type', 'item_id', 'name', 'path'
    ];

}

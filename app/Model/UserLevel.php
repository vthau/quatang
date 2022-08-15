<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserLevel extends Item
{
    public $table = 'user_levels';

    protected $fillable = [
        'title'
    ];

    public function delItem()
    {
        //update related
        User::where("level_id", $this->id)
            ->update(array(
                'level_id' => 3, //default
            ));

        LevelPermission::where("level_id", $this->id)
            ->delete();

        $this->delete();
    }
}

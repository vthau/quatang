<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserRelationship extends Item
{
    public $table = 'user_relationships';

    protected $fillable = [
        'user_id', 'title', 'deleted',
    ];


    //info
    public function getUser()
    {
        return User::find($this->user_id);
    }

    //delete
    public function delItem()
    {
        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }
}

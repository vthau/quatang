<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserPersonDate extends Item
{
    public $table = 'user_persons_date';

    protected $fillable = [
        'user_id', 'title', 'deleted', 'user_person_id',
        'day', 'month', 'budget', 'note',
    ];


    //info
    public function getUser()
    {
        return User::find($this->user_id);
    }

    public function getPerson()
    {
        return UserPerson::find($this->user_person_id);
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

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Contact extends Item
{
    public $table = 'contacts';

    protected $fillable = [
        'name', 'email', 'phone', 'body', 'deleted',
    ];

    public function delItem()
    {
        $apiCore = new Core();

        $apiCore->clearNotifications('contact', $this->id);

        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }
}

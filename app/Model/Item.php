<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Item extends Model
{
    public function getTitle()
    {
        $title = "";
        if (isset($this->title)) {
            $title = $this->title;
        } elseif (isset($this->name)) {
            $title = $this->name;
        }
        return $title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHref()
    {
        return "";
    }

    public function toHTML()
    {
        return '<a target="_blank" data-toggle="tooltip" data-placement="top" title="' . $this->getTitle() . '" data-original-title="' . $this->getTitle() . '" class="my-item" href="' . $this->getHref() . '">' . $this->getTitle() . '</a>';
    }
}

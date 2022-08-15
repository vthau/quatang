<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class Notification extends Model
{
    public $table = 'notifications';

    protected $fillable = [
        'user_id', 'type', 'subject_id', 'subject_type',
        'object_id', 'object_type', 'params', 'seen'
    ];

    public function getSubject()
    {
        $apiCore = new Core();
        return $apiCore->getItem($this->subject_type, $this->subject_id);
    }

    public function getObject()
    {
        $apiCore = new Core();
        return $apiCore->getItem($this->object_type, $this->object_id);
    }

    public function getNotify()
    {
        return NotificationType::where("title", "=", $this->type)->first();
    }

    public function getDate()
    {
        $apiCore = new Core();
        return $apiCore->time_elapsed_string($this->created_at);
    }

    public function getNotification()
    {
        $html = "";
        $notify = $this->getNotify();
        $object = $this->getObject();
        $subject = $this->getSubject();
        $params = json_decode($this->params);
        if ($notify) {
            $html = $notify->body;
            if ($object) {
                $html = str_replace('{$object}', $object->toHTML(), $html);
            }
            if ($subject) {
                $html = str_replace('{$subject}', $subject->toHTML(), $html);
            }

            if ($this->type == 'review_new') {
                $html = str_replace('{$star}', "<b>{$params->star}</b>", $html);
            }
        }

        return $html;
    }
}

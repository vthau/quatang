<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\User;

use App\Api\Core;

use App\Model\Notification;

class BENotificationController extends Controller
{
    public function index()
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();

        $values = [
            'page_title' => 'Tất Cả Thông Báo',
        ];

        $select = Notification::query("notifications")
            ->where("user_id", "=", $viewer->id)
            ->orderByDesc("id")
        ;

        $values['items'] = $select->paginate(30);

        return view("pages.be.notifications.index", $values);
    }

    public function refresh()
    {
        $apiCore = new Core();
        $viewer = $apiCore->getViewer();

        if ($viewer) {
            $viewer->update([
                'time_notification' => date("Y-m-d H:i:s")
            ]);
        }

        return response()->json([]);
    }


}

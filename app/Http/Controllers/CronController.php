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
use App\Api\Email;

use App\Model\MailQueue;
use App\Model\UserCart;
use App\Model\Product;

use App\Mail\CartPaid;

class CronController extends Controller
{
    // chay moi 5ph
    public function index()
    {
        $apiMail = new Email();
        $apiCore = new Core();

        if ($apiCore->testMode()) {
            die;
        }

        $text = "CRON_at_" . date('d_m_Y_H_i_s') . "\n";

        $rows = MailQueue::orderByDesc('id')->get();
        if (count($rows)) {
            foreach ($rows as $row) {
                $user = User::find((int)$row->user_id);
                $params = json_decode($row->params);
                $removed = false;

                $item = $apiCore->getItem($row->item_type, $row->item_id);
                if ($item->deleted) {
                    $row->delete();
                    continue;
                }

                switch ($row->type) {
                    case 'user_signup':
                        $removed = true;
                        $apiMail->userSignup($user);

                        $text .= "user_signup_id_" . $user->id . "_email_" . $user->email . "\n";

                        break;

                    case 'cart_booked':
                        $removed = true;
                        $email = $item->email;
                        $sent = [];

                        $userId = 0;
                        if ($user) {
                            $userId = $user->id;
                            $email = $user->email;
                        }

                        if (!empty($email)) {
                            $sent[] = $email;

                            $apiMail->cartBooked($email, $item);

                            $text .= "cart_booked_cart_id_" . $item->id . "_user_id_" . $userId . "_email_" . $email . "\n";
                        }

                        //thong bao all staffs + nha cung cap lien quan

                        //settings
                        $arr = $apiCore->getSetting('cart_booked_notify_to');
                        if (!empty($arr)) {
                            $arr = array_filter(explode(';', $arr));
                            if (count($arr)) {
                                foreach ($arr as $email) {
                                    if (in_array($email, $sent)) {
                                        continue;
                                    }

                                    $apiMail->cartBookedNotify($email, $item);

                                    $text .= "cart_booked_notify_cart_id_" . $item->id . "_notify_to_email_" . $email . "\n";

                                    $sent[] = $email;
                                }
                            }
                        }

                        //admin
                        $admins = $apiCore->getAdmins();
                        if (count($admins)) {
                            foreach ($admins as $admin) {
                                if (in_array($admin->email, $sent)) {
                                    continue;
                                }

                                $apiMail->cartBookedNotify($admin->email, $item);

                                $text .= "cart_booked_notify_cart_id_" . $item->id . "_notify_to_email_" . $admin->email . "\n";
                            }
                        }


                        break;

                    case 'cart_paid':
                        $removed = true;
//                        $apiMail->cartPaid($user, $item);

                        $email = $item->email;
                        if ($item->user_id) {
                            $user = User::find($item->user_id);
                            if ($user) {
                                $email = $user->email;
                            }
                        }

                        if (!empty($email)) {
                            $obj = new \stdClass();
                            $obj->url = url('/don-hang') . '/' . $item->href;
                            $obj->sender = $apiMail->getSender();
                            $obj->receiver = $email;
                            $obj->order_percent = $item->refer_percent + 0;
                            $obj->order_money = number_format($item->refer_money, 0, '', ',') . ' VND';
                            $obj->order_title = $item->href;

                            Mail::to($email)->send(new CartPaid($obj));

                            $text .= "cart_paid_cart_id_" . $item->id . "_to_email_" . $email . "\n";
                        }

                        break;

                }

                if ($removed) {
                    $row->delete();
                }
            }
        }

        return response()->json(['TEXT' => $text]);
    }

}

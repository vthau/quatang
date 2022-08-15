<?php

namespace App\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Intervention\Image\ImageManagerStatic as Image;

use \DateTime;

use App\User;

use App\Mail\Signup;
use App\Mail\CartBooked;
use App\Mail\CartBookedNotify;
use App\Mail\CartLostCommission;
use App\Mail\CartNext;

use App\Api\Core;

class Email
{
    public function getSender()
    {
        $apiCore = new Core();
        return (!empty($apiCore->getSetting('site_title'))) ? $apiCore->getSetting('site_title') : "Administrator";
    }

    public function getContact()
    {
        $apiCore = new Core();
        return (!empty($apiCore->getSetting('site_email'))) ? $apiCore->getSetting('site_email') : "support@geckoso.com";
    }

    public function userSignup($user)
    {
        //send mail
        $obj = new \stdClass();
        $obj->url = url('/tai-khoan');
        $obj->sender = $this->getSender();
        $obj->receiver = $user->email;

        Mail::to($user->email)->send(new Signup($obj));
    }

    public function cartBooked($email, $cart)
    {
        //send mail
        $obj = new \stdClass();
        $obj->url = url('/don-hang') . '/' . $cart->href;
        $obj->sender = $this->getSender();
        $obj->receiver = $email;

        Mail::to($email)->send(new CartBooked($obj));
    }

    public function cartBookedNotify($email, $cart)
    {
        //send mail
        $obj = new \stdClass();
        $obj->url = url('/don-hang') . '/' . $cart->href;
        $obj->sender = $this->getSender();
        $obj->receiver = $email;

        Mail::to($email)->send(new CartBookedNotify($obj));
    }

    public function cartLostCommission($email, $cart, $params = NULL)
    {
        //send mail
        $obj = new \stdClass();
        $obj->url = url('/don-hang') . '/' . $cart->href;
        $obj->sender = $this->getSender();
        $obj->receiver = $email;

        if ($params) {
            $obj->url = url('/tai-khoan');
            $obj->order_percent = $params->percent + 0;
            $obj->order_money = number_format($params->money, 0, '', ',') . ' VND';
            $obj->order_title = $cart->href;
        }

        Mail::to($email)->send(new CartLostCommission($obj));
    }

    public function cartNext($email, $cart)
    {
        //send mail
        $obj = new \stdClass();
        $obj->url = url('/don-hang') . '/' . $cart->href;
        $obj->sender = $this->getSender();
        $obj->receiver = $email;

        Mail::to($email)->send(new CartNext($obj));
    }

}

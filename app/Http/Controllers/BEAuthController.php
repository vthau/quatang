<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Api\Core;
use App\User;

use App\Model\PasswordReset;
use App\Model\UserBlock;

use App\Mail\ResetPassword;

use \Session;

class BEAuthController extends Controller
{
    protected $_apiCore = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
    }

    public function login()
    {
        $user = Auth::user();
        if ($user) {
            return redirect('/admin');
        }

        $err = (Session::get('ERR_LOGIN'));
        if ((int)$err) {
            Session::forget('ERR_LOGIN');
        }

        $values = [
            'page_title' => 'Quản Lý | Đăng Nhập',
            'err_login' => ((int)$err) ? true : false,
        ];


        return view("pages.be.auth.login", $values);
    }

    public function doLogin(Request $request)
    {
        if (!count($request->post())) {
            return redirect('/invalid');
        }
        $values = $request->post();

        $remember = (isset($values['remember']) && $values['remember'] == "on") ? true : false;
        unset($values['_token']);
        unset($values['remember']);

        $values['email'] = trim(strip_tags($values['email']));
        $values['password'] = trim(strip_tags($values['password']));
//        echo '<pre>';var_dump($values);die;

        $user = User::where('deleted', 0)
            ->where('email', $values['email'])
            ->first();

        if (!$user) {
            $user = User::where('deleted', 0)
                ->where('phone', $values['email'])
                ->first();
            if ($user) {
                $values['email'] = $user->email;
            }
        }

        if ($user) {
            //prevent block user
            $blocked = UserBlock::where('user_id', $user->id)->first();
            if ($blocked) {
                return redirect('/invalid');
            }

            //prevent customer login
            if ($user->isCustomer()) {
                return redirect('/invalid');
            }
        }

        // attempt to do the login
        if (Auth::attempt($values, $remember)) {
            return redirect('/admin');
        } else {
            Session::put('ERR_LOGIN', '1');
            return redirect('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function checkEmail(Request $request)
    {
        $values = $request->post();

        $user = User::where('deleted', 0)
            ->where("email", "{$values['email']}")
            ->first();

        return response()->json([
            'VALID' => !$user ? false : true,
        ]);
    }

    public function forgot()
    {
        $values = [
            'page_title' => 'Quên Mật Khẩu',
        ];

        return view("pages.be.auth.forgot", $values);
    }

    public function doForgot(Request $request)
    {
        if (!count($request->post())) {
            return response()->json(['VALID' => false]);
        }
        $values = $request->post();
        $token = $values['_token'] . time();
//        echo '<pre>';var_dump($values);die;
        $values['email'] = trim(strip_tags($values['email']));

        $row = PasswordReset::where("email", "=", "{$values['email']}")->first();

        if ($row) {
            $row->update(array(
                'token' => $token,
            ));
        } else {
            PasswordReset::create(array(
                'email' => $values['email'],
                'token' => $token,
            ));
        }

        //blocked
        $stop = false;
        $user = User::where('deleted', 0)
            ->where('email', $values['email'])
            ->first();
        if ($user) {
            $blocked = UserBlock::where('user_id', $user->id)->first();
            if ($blocked) {
                $stop = true;
            }

            if ($user->isCustomer()) {
                $stop = true;
            }
        }

        if ($stop) {
            return response()->json(['VALID' => false]);
        }

        //send mail
        $obj = new \stdClass();
        $obj->url = url('/') . '/auth/reset/' . $token;
        $obj->sender = 'Administrator';
        $obj->receiver = $values['email'];

        Mail::to($values['email'])->send(new ResetPassword($obj));

        return response()->json(['VALID' => true]);
    }

    public function reset($token)
    {
        $values = [
            'page_title' => 'Tạo Mật Khẩu Mới',
            'user_token' => $token,
        ];

        return view("pages.be.auth.reset", $values);
    }

    public function resetPassword(Request $request)
    {
        if (!count($request->post())) {
            return response()->json(['VALID' => false]);
        }
        $values = $request->post();
        $values['key'] = trim(strip_tags($values['key']));
        $values['password'] = (string)trim($values['password']);

        $row = PasswordReset::where("token", "{$values['key']}")->first();
        $user = User::where('deleted', 0)
            ->where("email", $row->email)
            ->first();

        if ($row && $user) {
            $user->update(array(
                'password' => Hash::make($values['password']),
            ));

            $this->_apiCore->addLog([
                'user_id' => $user->id,
                'action' => 'reset_password',
                'item_id' => $user->id,
                'item_type' => 'user',
            ]);

            $row->delete();
        }

        return response()->json(['VALID' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Model\UserSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Api\Core;
use App\Api\FE;
use App\User;

use App\Model\PasswordReset;
use App\Model\UserBlock;
use App\Model\MailQueue;

use App\Mail\ResetPassword;

use \Session;

class FEAuthController extends Controller
{
    protected $_apiCore = null;
    protected $_apiFE = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiFE = new FE();
    }

    public function login(Request $request)
    {
        $params = $request->all();
        $ref = (isset($params['ref'])) ? $params['ref'] : '';
        $previousURL = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";

        if ($this->_apiCore->getViewer()) {
            return redirect('/');
        }

        $errLogin = (Session::get('ERR_LOGIN')) ? true : false;
        $errSignup = (Session::get('ERR_SIGNUP')) ? true : false;
        $message = (Session::get('ERR_WAY')) ? Session::get('ERR_WAY') : "";
        if ($errLogin || $errSignup) {
            Session::forget('ERR_LOGIN');

            $message = (Session::get('ERR_WAY')) ? Session::get('ERR_WAY') : "Thông tin đăng nhập không chính xác.";
            if ($errSignup) {
                $message = (Session::get('ERR_WAY')) ? Session::get('ERR_WAY') : "Đã có lỗi xảy ra, vui lòng thử lại.";
            }
            if (!empty($message)) {
                Session::forget('ERR_WAY');
                Session::forget('ERR_SIGNUP');

                switch ($message) {
                    case 'blocked':
                        $message = "Tài khoản của bạn đã bị khóa, vui lòng liên hệ chúng tôi để mở khóa.";
                        break;
                    case 'email':
                        $message = "Tài khoản email này đã có người sử dụng.";
                        break;
                }
            }
        }

        $errEmail = (Session::get('ERR_EMAIL'));
        if (!empty($errEmail)) {
            Session::forget('ERR_EMAIL');
        }

        $dkName = (Session::get('DK_NAME'));
        if (!empty($dkName)) {
            Session::forget('DK_NAME');

            $previousURL = url('/');
        }

        $dkEmail = (Session::get('DK_EMAIL'));
        if (!empty($dkEmail)) {
            Session::forget('DK_EMAIL');
        }

        //
        $dkDT = (Session::get('USR_DK_DT'));
//        var_dump($dkDT);die;

        $values = [
            'page_title' => 'Xác Thực Truy Cập',
            'err_login' => $errLogin,
            'err_signup' => $errSignup,
            'err_message' => $message,

            'referer' => $previousURL,
            'ref' => $ref,

            'login_page' => true,

            'params' => $params,

            'err_email' => $errEmail,

            'dk_name' => $dkName,
            'dk_email' => $dkEmail,

            'dk_dt' => $dkDT,
        ];

        return view("pages.fe.auth.login", $values);
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

        $to = $values['referer'];
        unset($values['referer']);

        $values['email'] = trim(strip_tags($values['email']));
        $values['password'] = trim(strip_tags($values['password']));
//        echo '<pre>';var_dump($values);die;

        $user = User::where('deleted', 0)
            ->where('email', $values['email'])
            ->first();

//        if (!$user) {
//            $user = User::where('deleted', 0)
//                ->where('phone', $values['email'])
//                ->first();
//            if ($user) {
//                $values['email'] = $user->email;
//            }
//        }

        if ($user) {
            //prevent block user
            $blocked = UserBlock::where('user_id', $user->id)->first();
            if ($blocked) {
                Session::put('ERR_LOGIN', '1');
                Session::put('ERR_WAY', 'blocked');

                return redirect('/dang-nhap');
            }
        }

        // attempt to do the login
        if (Auth::attempt($values, true)) {
            //
            $this->_apiFE->sessionLovedCart();

            //
            $user = Auth::user();
            if (!$user->isCustomer()) {
                return redirect('admin');
            }

            //
            Session::put('LOGIN_SUCCESS', $user->name);

            if (!empty($to)) {
                return redirect($to);
            }

            return redirect('/');
        } else {
            Session::put('ERR_EMAIL', $values['email']);

            Session::put('ERR_LOGIN', '1');
            return redirect('/dang-nhap');
        }
    }

    public function doSignup(Request $request)
    {
        if (!count($request->post())) {
            return redirect('/invalid');
        }
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;

        unset($values['_token']);

        $to = $values['referer'];
        unset($values['referer']);

        //href
        $title = $this->_apiCore->stripVN($values['name']);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('user', array(
            'id' => 0,
            'name' => $title,
        ));

        //check again
        $row1 = User::select()
            ->where('deleted', 0)
            ->where('email', trim($values['email']))
            ->first();
        if ($row1) {
            Session::put('ERR_SIGNUP', '1');
            Session::put('ERR_WAY', 'email');

            return redirect('/dang-nhap');
        }

        $login['email'] = $values['email'];
        $login['password'] = $values['password'];

        $values['level_id'] = 4;
        $values['password'] = Hash::make($values['password']);

        $user = User::create($values);

        $this->_apiCore->addLog([
            'user_id' => $user->id,
            'action' => 'register_user',
            'item_id' => $user->id,
            'item_type' => 'user',
        ]);

        if (Auth::attempt($login, true)) {
            //
            $this->_apiFE->sessionLovedCart(true);

            //notify
            $this->_apiCore->notifyAllStaffs('client_new', [
                'subject_type' => 'user',
                'subject_id' => $user->id,
            ]);

            //send mail
            MailQueue::create([
                'user_id' => $user->id,
                'item_id' => $user->id,
                'item_type' => 'user',
                'type' => 'user_signup',
            ]);

            //social
            UserSocial::where('email', $user->email)
                ->where('user_id', 0)
                ->update([
                    'user_id' => $user->id,
                ]);

            //
            Session::put('SIGNUP_SUCCESS', $user->name);

            if (!empty($to)) {
                return redirect($to);
            }

            return redirect('/');
        }

        Session::put('ERR_SIGNUP', '1');
        return redirect('/dang-nhap');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function checkEmail(Request $request)
    {
        $values = $request->post();
        $email = (isset($values['email'])) ? $this->_apiCore->cleanStr($values['email']) : NULL;
        $phone = (isset($values['phone'])) ? $this->_apiCore->cleanStr($values['phone']) : NULL;

        $user1 = User::where('deleted', 0)
            ->where("email", $email)
            ->first();

        $user2 = User::where('deleted', 0)
            ->where("phone", $phone)
            ->first();

        return response()->json([
            'VALID_EMAIL' => !$user1 ? false : true,
            'VALID_PHONE' => false, //tam thoi ko bat phone //!$user2 ? false : true,
        ]);
    }

    public function forgot()
    {
        $values = [
            'page_title' => 'Quên Mật Khẩu',
        ];

        return view("pages.fe.auth.forgot", $values);
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
        }

        if ($stop) {
            return response()->json(['VALID' => false]);
        }

        //send mail
        $obj = new \stdClass();
        $obj->url = url('/') . '/auth/lay-lai-mat-khau/' . $token;
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

        return view("pages.fe.auth.reset", $values);
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

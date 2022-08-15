<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use \Session;

use App\User;
use App\Api\Core;

use Socialite;
use App\Model\UserSocial;

class FESocialController extends Controller
{
    public function redirect($provider, Request $request)
    {
        $params = $request->all();
        if (isset($params['f'])) {
            Session::put('REDIRECT_FROM', $params['f']);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider, Request $request)
    {
        $info = Socialite::driver($provider)->user();
//        echo '<pre>';var_dump($info, $request->all());die;
        $from = (Session::get('REDIRECT_FROM'));

        //id, name, email, token
        if ($info && isset($info->email) && !empty($info->email)) {
            $sEmail = $info->email;
            $sId = isset($info->id) ? $info->id : NULL;
            $sToken = isset($info->token) ? $info->token : NULL;

            //tim
            $user = User::where('email', $sEmail)
                ->where('deleted', 0)
                ->first();
            if ($user) {
                //login
                $social = UserSocial::where('user_id', $user->id)
                    ->where('type', $provider)
                    ->first();
                if ($social) {
                    $social->update([
                        'social_id' => $sId,
                        'social_token' => $sToken,
                    ]);

                } else {
                    UserSocial::create([
                        'user_id' => $user->id,
                        'type' => $provider,
                        'email' => $user->email,
                        'social_id' => $sId,
                        'social_token' => $sToken,
                    ]);
                }

                auth()->login($user);

                //
                Session::put('LOGIN_SUCCESS', $user->name);

                if (!empty($from)) {
                    Session::forget('REDIRECT_FROM');

                    if ($from == 'ctv') {
                        $user->update([
                            'hop_tac' => 1,
                        ]);

                        Session::put('CTV_HOPTAC', 1);

                        return redirect('/tai-khoan?t=dtkd');

                    } elseif ($from == 'ctvq') {
                        $user->troThanhDoiTac();

                        Session::put('CTV_HOPTAC', 1);

                        return redirect('/tai-khoan?t=gioi_thieu');
                    }
                }

                return redirect('/');

            } else {
                //register
                $social = UserSocial::where('email', $sEmail)
                    ->where('type', $provider)
                    ->first();

                if (!$social) {
                    UserSocial::create([
                        'user_id' => 0,
                        'type' => $provider,
                        'email' => $sEmail,
                        'social_id' => $sId,
                        'social_token' => $sToken,
                    ]);
                }

                Session::put('DK_NAME', isset($info->name) ? $info->name : NULL);
                Session::put('DK_EMAIL', isset($info->email) ? $info->email : NULL);

                if (!empty($from)) {
                    Session::forget('REDIRECT_FROM');

                    if ($from == 'ctv') {
                        return redirect('/tro-thanh-doi-tac');
                    } elseif ($from == 'ctvq') {
                        return redirect('/tro-thanh-cong-tac-vien');
                    }
                }

                return redirect('/dang-nhap?v=dk');
            }
        }

        return redirect('/dang-nhap');
    }


}

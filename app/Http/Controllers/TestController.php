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
use \Artisan;
use \DateTime;

use App\User;
use App\Api\Core;
use App\Api\FE;
use App\Api\Permission;

use App\Model\Product;
use App\Model\ProductSale;
use App\Model\ProductCategory;
use App\Model\Test;
use App\Model\GhnProvince;
use App\Model\GhnDistrict;
use App\Model\GhnWard;
use App\Model\UserCart;
use App\Model\UserCartProduct;
use App\Model\MailQueue;
use App\Model\Setting;
use App\Model\UserLevel;

class TestController extends Controller
{
    public function test()
    {
        echo '<pre>';
        $apiCore = new Core();
        $apiFE = new FE();
        $apiPermission = new Permission();

        $apiPermission->addPermissions();


//        $products = Product::get();
//        foreach ($products as $product) {
//            if (!empty($product->video_link)) {
//                $video = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $product->video_link);
//
//                $product->update([
//                    'video_link' => $video,
//                ]);
//            }
//        }


        echo '<br />';
        die('testing...');
    }

    //deploy
    public function test1()
    {
        echo '<pre>';
        $apiCore = new Core();
        $apiFE = new FE();

        DB::table('users')->insert(array(
            array(
                'password' => Hash::make('g1888'),
                'email' => 'support@geckoso.com',
                'name' => 'Support',
                'href' => 'boss',
                'level_id' => 1,
            ),
            array(
                'password' => Hash::make('geckoso'),
                'email' => 'admin@geckoso.com',
                'name' => 'Admin',
                'href' => 'admin',
                'level_id' => 2,
            ),
        ));

        DB::table('user_levels')->insert(array(
            array(
                'title' => 'Super Admin',
            ),
            array(
                'title' => 'Admin',
            ),
            array(
                'title' => 'Nhân Viên',
            ),
            array(
                'title' => 'Khách Hàng',
            ),
            array(
                'title' => 'Nhà Cung Cấp',
            ),
        ));

        echo '<br />';
        die('testing...');
    }

    public function localhost()
    {
        //users
        $users = User::where('deleted', 0)
            ->where('id', '>', 1)
            ->get();
        if (count($users)) {
            foreach ($users as $user) {
                $arr = array_filter(explode('@', $user->email));

                $firstEmail = $arr[0];
                $count = 0;

                do {
                    $email = $firstEmail . '@mailinator.com';
                    if ($count) {
                        $email = $firstEmail . $count . '@mailinator.com';
                    }

                    $row = User::where('email', $email)
                        ->where('deleted', 0)
                        ->first();
                    if (!$row) {
                        break;
                    }

                    $count++;
                } while(1);

                $user->update([
                    'email' => $email,
                ]);
            }
        }

        die('safe...');
    }
}

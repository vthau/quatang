<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Api\Core;

use App\User;
use File as File;

use App\Model\Log;
use App\Model\Banner;


use \Session;

class BEBannerController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            //
            if (
                $this->_viewer &&
                ($this->_viewer->isDeleted() || $this->_viewer->isBlocked() || !$this->_viewer->isStaff())
            ) {
                return redirect('/invalid');
            }

            return $next($request);
        });

        $this->middleware('auth');
    }


    public function index()
    {
        $pageTitle = 'Danh sách banner';
        $banners = Banner::all();

        $values = [
            "page_title" => $pageTitle,
            "banners" => $banners,
        ];

        return view("pages.back_end.banner.index", $values);
    }

    public function add(Request $request)
    {
        $params = $request->all();
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $banner = Banner::find($itemId);

        $pageTitle = 'Tạo Banner';
        if ($banner) {
            $pageTitle = 'Sửa Thông Tin Banner';
        }

        $values = [
            'page_title' => $pageTitle,
            'banner' => $banner,
        ];

        return view("pages.back_end.banner.add", $values);
    }


    public function save(Request $request)
    {
        if (!count($request->post())) {
            return redirect('/admin/banners');
        }
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $values['title'] = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $banner = Banner::find($itemId);

        $imagePath = "";
        $imageMobiPath = "";

        if ($banner) {
            $imagePath = $banner->img;
            $imageMobiPath = $banner->img_mobi;
        }

        if (!empty($request->file('img'))) {
            $imageName = 'home_banner_' . substr(md5(time()), 0, 10) . rand(0, 99) . $request->file('img')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys/" . $imageName;
            $request->file('img')->move(public_path('/uploaded/sys/'), $imageName);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            };
        }

        if (!empty($request->file('img_mobi'))) {
            $imageMobiName = 'home_banner_mobi_' . substr(md5(time()), 0, 10) . rand(0, 99) . $request->file('img_mobi')->getClientOriginalExtension();
            $imageMobiPath = "/uploaded/sys/" . $imageMobiName;
            $request->file('img_mobi')->move(public_path('/uploaded/sys/'), $imageMobiName);

            if (File::exists($imageMobiPath)) {
                File::delete($imageMobiPath);
            };
        }


        if ($banner) {
            $banner->update([
                "title" => $values["title"],
                "href" => $values["href"],
                "display" => $values["display"],
                "img" => $imagePath,
                "img_mobi" => $imageMobiPath,
            ]);
        } else {
            Banner::create([
                "title" => $values["title"],
                "href" => $values["href"],
                "display" => $values["display"],
                "img" => $imagePath,
                "img_mobi" => $imageMobiPath,
            ]);
        }


        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/banners');
    }

    public function delete(Request $request)
    {
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        Banner::find($itemId)->delete();
        return response()->json([]);
    }
}

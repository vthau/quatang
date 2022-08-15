<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\User;

use App\Model\Photo;

class BESettingController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();

        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            //
            if ($this->_viewer &&
                ($this->_viewer->isDeleted() || $this->_viewer->isBlocked() || !$this->_viewer->isStaff())
            ) {
                return redirect('/invalid');
            }

            return $next($request);
        });

        $this->middleware('auth');
    }

    public function config()
    {
        if (!$this->_viewer->isAllowed('setting_home')) {
            return redirect('/private');
        }

        $saved = (Session::get('SAVED'));
        if ((int)$saved) {
            Session::forget('SAVED');
        }

        $values = [
            'page_title' => 'Tùy Chỉnh Trang Chủ',

            'saved' => $saved,
        ];

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.settings.home", $values);
    }

    public function configSave(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_home')) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values, $request->file('site_logo'));die;
        unset($values['_token']);

        for ($i=1;$i<=4;$i++) {
            if (!empty($request->file('banner_' . $i))) {
                $imageName = 'home_banner_' . $i . '.' . $request->file('banner_' . $i)->getClientOriginalExtension();
                $imagePath = "/uploaded/sys/" . $imageName;
                $request->file('banner_' . $i)->move(public_path('/uploaded/sys/'), $imageName);

                $values['banner_bg_' . $i] = $imagePath;
            }

            unset($values['banner_' . $i]);

            if (!empty($request->file('mobi_banner_' . $i))) {
                $imageName = 'home_banner_mobi_' . $i . '.' . $request->file('mobi_banner_' . $i)->getClientOriginalExtension();
                $imagePath = "/uploaded/sys/" . $imageName;
                $request->file('mobi_banner_' . $i)->move(public_path('/uploaded/sys/'), $imageName);

                $values['mobi_banner_bg_' . $i] = $imagePath;
            }

            unset($values['mobi_banner_' . $i]);
        }

        for ($i=1;$i<=3;$i++) {
            if (!empty($request->file('cate_' . $i))) {
                $imageName = 'home_cate_' . $i . '.' . $request->file('cate_' . $i)->getClientOriginalExtension();
                $imagePath = "/uploaded/sys/" . $imageName;
                $request->file('cate_' . $i)->move(public_path('/uploaded/sys/'), $imageName);

                $values['cate_bg_' . $i] = $imagePath;
            }

            unset($values['cate_' . $i]);
        }

        //save setting home
        if (count($values)) {
            $this->_apiCore->updateSettings($values);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'setting_update',
            'item_id' => 0,
            'item_type' => 'setting',
            'params' => json_encode([
                'type' => 'config_home',
            ])
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/config');
    }

    public function index()
    {
        if (!$this->_viewer->isAllowed('setting_config')) {
            return redirect('/private');
        }

        $saved = (Session::get('SAVED'));
        if ((int)$saved) {
            Session::forget('SAVED');
        }

        $values = [
            'page_title' => 'Tùy Chỉnh Thông Tin',

            'saved' => $saved,

            'logo' => Photo::where('type', 'site_logo')->where('parent_id', 0)->first(),
            'logo2' => Photo::where('type', 'site_logo_ngang')->where('parent_id', 0)->first(),
        ];

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.settings.index", $values);
    }

    public function save(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_config')) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values, $request->file('site_logo'));die;
        unset($values['_token']);

        //logo
        if (!empty($request->file('site_logo'))) {
            $imageName = 'sys_logo.' . $request->file('site_logo')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys/" . $imageName;
            $request->file('site_logo')->move(public_path('/uploaded/sys/'), $imageName);
            $this->_apiCore->uploadLogo($imageName, $imagePath);
        }

        //logo ngang
        if (!empty($request->file('site_logo_ngang'))) {
            $imageName = 'sys_backdrop.' . $request->file('site_logo_ngang')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys/" . $imageName;
            $request->file('site_logo_ngang')->move(public_path('/uploaded/sys/'), $imageName);
            $this->_apiCore->uploadLogo2($imageName, $imagePath);
        }

        unset($values['site_logo']);
        unset($values['site_logo_ngang']);

        //save setting home
        if (count($values)) {
            $this->_apiCore->updateSettings($values);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'setting_update',
            'item_id' => 0,
            'item_type' => 'setting',
            'params' => json_encode([
                'type' => 'config',
            ])
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/settings');
    }

    public function setting(Request $request)
    {
        $saved = (Session::get('SAVED'));
        if ((int)$saved) {
            Session::forget('SAVED');
        }
        $params = $request->all();
        if (!isset($params['s'])
            && !in_array($params['s'], ['about_us', 'policy_client', 'policy_shipment', 'policy_refund', 'policy_payment', 'policy_security'])
        ) {
            return redirect('/invalid');
        }

        $pageTitle = "Tùy Chỉnh Thông Tin";
        switch ($params['s']) {
            case 'about_us':
                $pageTitle = "Tùy Chỉnh Giới Thiệu";

                if (!$this->_viewer->isAllowed('setting_about')) {
                    return redirect('/private');
                }
                break;

            case 'policy_client':
                $pageTitle = "Tùy Chỉnh Chính Sách Thành Viên";

                if (!$this->_viewer->isAllowed('setting_policy')) {
                    return redirect('/private');
                }
                break;

            case 'policy_shipment':
                $pageTitle = "Tùy Chỉnh Chính Sách Giao Hàng";

                if (!$this->_viewer->isAllowed('setting_policy')) {
                    return redirect('/private');
                }
                break;

            case 'policy_refund':
                $pageTitle = "Tùy Chỉnh Chính Sách Đổi Trả";

                if (!$this->_viewer->isAllowed('setting_policy')) {
                    return redirect('/private');
                }
                break;

            case 'policy_payment':
                $pageTitle = "Tùy Chỉnh Chính Sách Thanh Toán";

                if (!$this->_viewer->isAllowed('setting_policy')) {
                    return redirect('/private');
                }
                break;

            case 'policy_security':
                $pageTitle = "Tùy Chỉnh Chính Sách Bảo Mật";

                if (!$this->_viewer->isAllowed('setting_policy')) {
                    return redirect('/private');
                }
                break;
        }

        $values = [
            'page_title' => $pageTitle,

            'saved' => $saved,

            'key' => $params['s'],
        ];

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.settings.tinymce", $values);
    }

    public function settingUpdate(Request $request)
    {
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        unset($values['_token']);
        if (!isset($values['title']) || !isset($values['body'])) {
            return redirect('/invalid');
        }

        $this->_apiCore->updateSetting($values['title'], $values['body']);

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'setting_update',
            'item_id' => 0,
            'item_type' => 'setting',
            'params' => json_encode([
                'type' => 'config_' . $values['title'],
            ])
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/setting?s=' . $values['title']);
    }


}

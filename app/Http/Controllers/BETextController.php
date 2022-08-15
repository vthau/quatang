<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Api\Core;

use App\User;

use App\Model\Log;
use App\Model\Photo;
use App\Model\Setting;

use \Session;

class BETextController extends Controller
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

    public function index()
    {
        if (!$this->_viewer->isAllowed('setting_text')) {
            return redirect('/private');
        }

        $saved = (Session::get('SAVED'));
        if ((int)$saved) {
            Session::forget('SAVED');
        }

        $values = [
            'page_title' => 'Tùy Chỉnh Text Chữ',

            'saved' => $saved,

        ];

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.texts.index", $values);

    }

    public function save(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_text')) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values, $request->file('site_logo'));die;
        unset($values['_token']);

        //save setting text
        if (count($values)) {
            $this->_apiCore->updateSettings($values);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'text_update',
            'item_id' => 0,
            'item_type' => 'text',
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/texts');
    }

}

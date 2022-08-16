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
use App\Model\Element;


use \Session;

class BEElementController extends Controller
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
        $pageTitle = 'Danh sách Element';
        $elements = Element::oldest('order')->get();

        $values = [
            "page_title" => $pageTitle,
            "elements" => $elements,
        ];

        return view("pages.back_end.element.index", $values);
    }

    public function sort(Request $request)
    {
        Element::sort($request->ids);

        return response()->json([]);
    }

    public function update(Request $request)
    {
        Element::where('id', $request->id)->update(['display' => $request->isChecked]);

        return response()->json([]);
    }
}

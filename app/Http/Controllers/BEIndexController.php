<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Api\Core;

use App\User;

use App\Model\Log;


use \Session;

class BEIndexController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->middleware(function ($request, $next) {
            $this->_viewer = $this->_apiCore->getViewer();

            //khach hang
            if ($this->_viewer && $this->_viewer->isCustomer()) {
                return redirect('/');
            }

            //block + delete
            if ($this->_viewer &&
                ($this->_viewer->isDeleted() || $this->_viewer->isBlocked())
            ) {
                return redirect('/invalid');
            }

            return $next($request);
        });

        $this->middleware('auth');
    }

    public function index()
    {
        $values = [

        ];

        return view("pages.be.index", $values);
    }

}

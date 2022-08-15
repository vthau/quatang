<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Api\Core;
use App\Api\Permission;


class BEPermissionController extends Controller
{
    protected $_apiCore = null;
    protected $_apiPermission = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
        $this->_apiPermission = new Permission();

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

    public function setPermissions()
    {
        if (!$this->_viewer->isSuperAdmin() && !$this->_viewer->isAdmin()) {
            die ("PRIVATE");
        }

        $this->_apiPermission->addPermissions();

        die ("DONE...");
    }
}

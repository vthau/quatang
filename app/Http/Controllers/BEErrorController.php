<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Api\Core;


class BEErrorController extends Controller
{
    public function invalid()
    {
        die('Bạn không có quyền truy cập.');
    }

    public function err403()
    {
        $values = [];

        return view("pages.be.error.403", $values);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\User;
use App\Api\Core;

use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportCart;

use App\Model\UserCart;

class ExcelController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
    }

    public function cartExcel($id)
    {
        $cart = UserCart::find((int)$id);
        if (!$cart) {
            return redirect('invalid');
        }

        $fileName = 'don_hang_' . $cart->href . '.xlsx';

        $export = new ExportCart();
        $export->setCart($cart);

        return Excel::download($export, $fileName);
    }


}

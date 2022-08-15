<?php

namespace App\Http\Controllers;

use App\Model\UserCart;
use Illuminate\Http\Request;

use Carbon\Carbon;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\User;
use App\Api\Core;



class PdfController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
    }

    public function cartPdf($id)
    {
        $cart = UserCart::find((int)$id);
        if (!$cart) {
            return redirect('invalid');
        }

        $fileName = "don_hang_" . $cart->href . ".pdf";

        $html = view('pdf.cart_info')
            ->with('cart', $cart)
            ->render();

//        echo $html;die;

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $pdf = new Dompdf($options);
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $pdf->setHttpContext($contxt);
        $pdf->loadHtml($html,'UTF-8');
        $pdf->setPaper('A5', 'portrait');
        $pdf->render();

        return $pdf->stream($fileName, array("Attachment" => false));
    }

}

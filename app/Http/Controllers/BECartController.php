<?php

namespace App\Http\Controllers;

use App\Api\Core;
use App\Model\MailQueue;
use App\Model\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\User;


class BECartController extends Controller
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

    public function updateStatus(Request $request)
    {
        if (!($this->_viewer->isAllowed('order_confirm_paid') || $this->_viewer->isAllowed('order_confirm_delete'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $key = (isset($values['item_key'])) ? $values['item_key'] : NULL;
        $value = (isset($values['item_value'])) ? $values['item_value'] : NULL;

        $cart = UserCart::find($itemId);
        if ($cart) {
            if ($key == 'status') {
                if ((int)$value) {
                    if (!$this->_viewer->isAllowed('order_confirm_paid')) {
                        return redirect('/private');
                    }

                    $cart->onPaymentSuccess([
                        'confirm_success' => $this->_viewer->id,
                    ]);

//                    if ($cart->shipping == 'manual' && $cart->shipping_status != NULL) {
//                        $cart->update([
//                            'shipping_status' => 'delivered',
//                        ]);
//                    }

                    $this->_apiCore->addLog([
                        'user_id' => $this->_viewer->id,
                        'action' => 'order_confirm_paid',
                        'item_id' => $cart->id,
                        'item_type' => 'cart',
                    ]);

                } else {
                    if (!$this->_viewer->isAllowed('order_confirm_delete')) {
                        return redirect('/private');
                    }

                    $cart->update([
                        'status' => 'chua_thanh_toan',
                        'confirm_success' => 0,
                    ]);

                    $this->_apiCore->addLog([
                        'user_id' => $this->_viewer->id,
                        'action' => 'order_confirm_delete',
                        'item_id' => $cart->id,
                        'item_type' => 'cart',
                    ]);
                }
            }

            $html = view('modals.back_end.order_index_tr')
                ->with('order', $cart)
                ->render();


            return response()->json(['VALID' => true, 'BODY' => $html]);
        }

        return response()->json(['VALID' => false]);
    }

    public function updateShipped(Request $request)
    {
        if (!$this->_viewer->isAllowed('order_manual')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $cart = UserCart::find($itemId);
        if ($cart) {
            $cart->update([
                'shipping_status' => 'delivered',
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'order_manual_confirm',
                'item_id' => $cart->id,
                'item_type' => 'cart',
            ]);
        }

        return response()->json();
    }

}

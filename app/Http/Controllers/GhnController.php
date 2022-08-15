<?php

namespace App\Http\Controllers;

use App\Model\UserCart;
use Illuminate\Http\Request;

use App\User;
use App\Api\Core;


class GhnController extends Controller
{
    protected $_apiCore = null;
    protected $_viewer = null;

    public function __construct()
    {
        $this->_apiCore = new Core();
    }

    public function callback(Request $request)
    {
        $values = $request->all();
//        echo '<pre>';var_dump($values);die;
        $ghnCode = (isset($values['OrderCode'])) ? $this->_apiCore->cleanStr($values['OrderCode']) : NULL;
        $ghnStatus = (isset($values['Status'])) ? $this->_apiCore->cleanStr($values['Status']) : NULL;
        $ghnPrice = (isset($values['CODAmount'])) ? $this->_apiCore->parseToInt($values['CODAmount']) : 0;
        $ghnFee = (isset($values['TotalFee'])) ? $this->_apiCore->parseToInt($values['TotalFee']) : 0;

        $cart = UserCart::where('ghn_code', $ghnCode)
            ->limit(1)
            ->first();
        if ($cart) {
            if (!empty($ghnStatus) && $ghnStatus != $cart->shipping_status) {
                $cart->update([
                    'shipping_status' => $ghnStatus,
                ]);

                if ($ghnStatus == 'delivered') {
                    $cart->onPaymentSuccess([
                        'confirm_success' => 1
                    ]);
                }

                if ($ghnStatus == 'cancel') {
                    $cart->update([
                        'ghn_code' => NULL,
                        'expected_delivery_time' => NULL,
                    ]);
                }
            }

            if ($ghnFee && $ghnFee != $cart->total_ship) {
                $oldShip = $cart->total_ship;

                $cart->update([
                    'total_ship' => $ghnFee,
                ]);

                if (!$cart->free_ship) {
                    $cart->update([
                        'total_price' => $cart->total_price - $cart->total_discount - $oldShip + $ghnFee,
                    ]);
                }
            }

            $cart = UserCart::find($cart->id);
            if ($ghnPrice && $ghnPrice != $cart->total_price) {
                $cart->update([
                    'total_price_ghn' => $ghnPrice,
                ]);
            }

            return response()->json([
                'message' => "Da cap nhat don hang " . $ghnCode . " thanh cong!"
            ], 200);
        }

        return response()->json([
            'message' => "Khong tim thay don hang OrderCode hay don hang phu hop."
        ], 400);
    }

    public function createOrder(Request $request)
    {
        $viewer = $this->_apiCore->getViewer();
        if (!$viewer->isAllowed('order_ghn')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['id'])) ? (int)$values['id'] : 0;
        $cart = UserCart::find($itemId);
        if ($cart) {
            $cart->createGHN();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'order_ghn',
                'item_id' => $cart->id,
                'item_type' => 'cart',
            ]);
        }

        return response()->json([]);
    }

    public function shipManual(Request $request)
    {
        $viewer = $this->_apiCore->getViewer();
        if (!$viewer->isAllowed('order_manual')) {
            return redirect('/private');
        }

        $values = $request->post();
        $note = (isset($values['note'])) ? $this->_apiCore->cleanStr($values['note']) : NULL;
        $shippingFee = (isset($values['shipping_fee'])) ? $this->_apiCore->parseToInt($values['shipping_fee']) : 0;
        $itemId = (isset($values['id'])) ? (int)$values['id'] : 0;
        $cart = UserCart::find($itemId);
        if ($cart) {
            $oldShip = $cart->total_ship;

            $cart->update([
                'ghn_code' => NULL,
                'trans_type' => NULL,
                'expected_delivery_time' => NULL,
                'shipping_status' => !$cart->confirm_success ? 'delivering' : 'delivered',

                'shipping' => 'manual',
                'note_manual' => $note,
                'total_ship' => $shippingFee,
            ]);

            if (!$cart->free_ship) {
                $cart->update([
                    'total_price' => $cart->total_cart - $cart->total_discount - $oldShip + $shippingFee,
                ]);
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'order_manual',
                'item_id' => $cart->id,
                'item_type' => 'cart',
            ]);
        }

        return response()->json([]);
    }

}

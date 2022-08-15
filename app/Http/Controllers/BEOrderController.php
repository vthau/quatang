<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Api\Core;

use App\User;

use App\Model\UserCart;
use App\Model\UserCartProduct;
use App\Model\Log;
use App\Model\Photo;
use App\Model\Setting;
use App\Model\Product;
use App\Model\MailQueue;

use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportOrder;
use \Session;

class BEOrderController extends Controller
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

    public function index(Request $request)
    {
        if (!($this->_viewer->isAllowed('order_view') || $this->_viewer->isSupplier())) {
            return redirect('/private');
        }

        $params = $request->all();

        $dateFrom = date('Y-m-01');
        if (isset($params['date_from']) && !empty($params['date_from'])) {
            $dateFrom = $params['date_from'];
        }
        $dateTo = date('Y-m-t');
        if (isset($params['date_to']) && !empty($params['date_to'])) {
            $dateTo = $params['date_to'];
        }

        $values = [
            'page_title' => 'Danh Sách Đơn Hàng',

            'params' => $params,

            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        $select = UserCart::query('user_carts')
            ->select('user_carts.*')
            ->distinct()
            ->leftJoin('users', 'users.id', '=', 'user_carts.user_id')
            ->leftJoin("user_cart_products", "user_cart_products.cart_id", "=", "user_carts.id")
            ->leftJoin("products", "user_cart_products.product_id", "=", "products.id")
            ->where('user_carts.deleted', 0)
            ->where('user_carts.status', '<>', 'moi_tao')

            ->whereDate('user_carts.created_at', '>=', $dateFrom)
            ->whereDate('user_carts.created_at', '<=', $dateTo)

            //valid
            ->where('total_cart', '>', 0)
        ;

        if ($this->_viewer->isSupplier()) {
            $select->where('products.product_supplier_id', $this->_viewer->supplier_id);
        }

        //order
        $order = "user_carts.id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("user_carts.href", "LIKE", $search);
                } elseif ($filter == "phone") {
                    $select->where(function ($q) use ($search) {
                        $q->where("users.phone", "LIKE", $search)
                            ->orWhere("user_carts.phone", "LIKE", $search);
                    });
                }
            }

            if (isset($params['ref']) && !empty($params['ref']) && (int)$params['ref']) {
                $select->where("user_carts.refer_id", (int)$params['ref']);
            }
            if (isset($params['user']) && !empty($params['user'])) {
                if ($params['user'] == 'out') {
                    $select->where("user_carts.user_id", 0);
                } else {
                    $select->where("user_carts.user_id", (int)$params['user']);
                }
            }
            if (isset($params['product']) && !empty($params['product']) && (int)$params['product']) {
                $select
                    ->where("user_cart_products.deleted", 0)
                    ->where("user_cart_products.product_id", (int)$params['product']);
            }
            if (isset($params['sale']) && !empty($params['sale']) && (int)$params['sale']) {
                $select->leftJoin("user_cart_products", "user_cart_products.cart_id", "=", "user_carts.id")
                    ->where("user_cart_products.deleted", 0)
                    ->where("user_cart_products.sale_id", (int)$params['sale']);
            }

            if (isset($params['status']) && !empty($params['status'])) {
                $select->where("user_carts.status", $params['status']);
            }
            if (isset($params['shipping']) && !empty($params['shipping'])) {
                $select->where("user_carts.shipping", $params['shipping']);
            }
            if (isset($params['payment']) && !empty($params['payment'])) {
                $select->where("user_carts.payment_by", $params['payment']);
            }
            if (isset($params['shipping_status']) && !empty($params['shipping_status'])) {
                $select->where("user_carts.shipping_status", $params['shipping_status']);
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        $select->orderBy("{$order}", $orderBy);

        $selectSum = clone $select;

        $values['items'] = $select->select('user_carts.*')->paginate(20);

        //sum
        $totalCart = $selectSum->sum("user_carts.total_cart");
        $values['totalCart'] = $totalCart;
        $totalCart = $selectSum->sum("user_carts.total_discount");
        $values['totalDiscount'] = $totalCart;
        $totalCart = $selectSum->sum("user_carts.total_price");
        $values['totalPrice'] = $totalCart;
        $totalCart = $selectSum
            ->where('user_carts.status', 'da_thanh_toan')
            ->sum("user_carts.total_price");
        $values['totalPriced'] = $totalCart;
        $totalShip = $selectSum
//            ->where('user_carts.free_ship', 0)
            ->sum("user_carts.total_ship");
        $values['totalShip'] = $totalShip;

        //products
        $select = Product::where('deleted', 0);
        if ($this->_viewer->isSupplier()) {
            $select->where('product_supplier_id', $this->_viewer->supplier_id);
        }
        $values['products'] = $select->get();

        //users
        $values['users'] = User::where('id', '>', 1)
            ->where('deleted', 0)
            ->get();

        return view("pages.back_end.orders.index", $values);
    }

    public function setting(Request $request)
    {
        if (!$this->_viewer->isAllowed('order_config')) {
            return redirect('/private');
        }

        $saved = (Session::get('SAVED'));
        if ((int)$saved) {
            Session::forget('SAVED');
        }

        $pageTitle = "Tùy Chỉnh Đơn Hàng";

        $values = [
            'page_title' => $pageTitle,

            'saved' => $saved,
        ];

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.orders.setting", $values);

    }

    public function settingUpdate(Request $request)
    {
        if (!$this->_viewer->isAllowed('order_config')) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        unset($values['_token']);

        $row = Setting::where('title', 'payment_ship_free_city')->first();
        if (!isset($values['payment_ship_free_city'])) {
            if ($row) {
                $row->update([
                    'value' => '',
                ]);
            }
        } else {
            if ($row) {
                $row->update([
                    'value' => json_encode($values['payment_ship_free_city']),
                ]);
            } else {
                Setting::create([
                    'title' => 'payment_ship_free_city',
                    'value' => json_encode($values['payment_ship_free_city']),
                ]);
            }

            unset($values['payment_ship_free_city']);
        }

        if (count($values)) {
            $values['payment_ship_free_cart'] = (int)str_replace(',', '', $values['payment_ship_free_cart']);
            $values['payment_ship_fee'] = (int)str_replace(',', '', $values['payment_ship_fee']);

            $this->_apiCore->updateSettings($values);
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'order_config',
            'item_id' => $this->_viewer->id,
            'item_type' => 'user',
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/order-settings');
    }

    public function updateShipment(Request $request)
    {
        if (!$this->_viewer->isAllowed('order_shipment')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $status = (isset($values['status'])) ? $this->_apiCore->cleanStr($values['status']) : NULL;

        $cart = UserCart::find($itemId);
        if ($cart) {
            $cart->update([
                'shipping_status' => $status,
            ]);
        }

        return response()->json([]);
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('order_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $cart = UserCart::find($itemId);
        if ($cart) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'order_delete',
                'item_id' => $cart->id,
                'item_type' => 'cart',
            ]);

            $cart->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function exportItem(Request $request)
    {
        $params = $request->all();
//        echo '<pre>';var_dump($params);die;
        $dateFrom = (isset($params['date_from'])) ? date('Y-m-d', strtotime($params['date_from'])) : NULL;
        $dateTo = (isset($params['date_to'])) ? date('Y-m-d', strtotime($params['date_to'])) : NULL;

        $select = UserCart::query('user_carts')
            ->select('user_carts.*')
            ->leftJoin('users', 'users.id', '=', 'user_carts.user_id')
            ->where('user_carts.deleted', 0)
            ->where('user_carts.status', '<>', 'moi_tao')

            //valid
            ->where('total_cart', '>', 0)
        ;

        if (!empty($dateFrom)) {
            $select->whereDate('user_carts.created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $select->whereDate('user_carts.created_at', '<=', $dateTo);
        }

        //order
        $order = "user_carts.id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("user_carts.href", "LIKE", $search);
                } elseif ($filter == "phone") {
                    $select->where(function ($q) use ($search) {
                        $q->where("users.phone", "LIKE", $search)
                            ->orWhere("user_carts.phone", "LIKE", $search);
                    });
                }
            }

            if (isset($params['ref']) && !empty($params['ref']) && (int)$params['ref']) {
                $select->where("user_carts.refer_id", (int)$params['ref']);
            }
            if (isset($params['user']) && !empty($params['user'])) {
                if ($params['user'] == 'out') {
                    $select->where("user_carts.user_id", 0);
                } else {
                    $select->where("user_carts.user_id", (int)$params['user']);
                }
            }
            if (isset($params['product']) && !empty($params['product']) && (int)$params['product']) {
                $select->leftJoin("user_cart_products", "user_cart_products.cart_id", "=", "user_carts.id")
                    ->where("user_cart_products.deleted", 0)
                    ->where("user_cart_products.product_id", (int)$params['product']);
            }
            if (isset($params['sale']) && !empty($params['sale']) && (int)$params['sale']) {
                $select->leftJoin("user_cart_products", "user_cart_products.cart_id", "=", "user_carts.id")
                    ->where("user_cart_products.deleted", 0)
                    ->where("user_cart_products.sale_id", (int)$params['sale']);
            }

            if (isset($params['status']) && !empty($params['status'])) {
                $select->where("user_carts.status", $params['status']);
            }
            if (isset($params['shipping']) && !empty($params['shipping'])) {
                $select->where("user_carts.shipping", $params['shipping']);
            }
            if (isset($params['payment']) && !empty($params['payment'])) {
                $select->where("user_carts.payment_by", $params['payment']);
            }
            if (isset($params['shipping_status']) && !empty($params['shipping_status'])) {
                $select->where("user_carts.shipping_status", $params['shipping_status']);
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        $select->orderBy("{$order}", $orderBy);

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'order_excel_export',
            'item_id' => 0,
            'item_type' => 'product',
        ]);

        $excel = new ExportOrder();
        $excel->setItems($select->get());
        return Excel::download($excel, 'export_don_hang.xlsx');

    }
}

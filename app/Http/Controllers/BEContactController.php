<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use \Session;

use App\User;

use App\Api\Core;

use App\Model\Contact;

class BEContactController extends Controller
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
        if (!$this->_viewer->isAllowed('setting_contact')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Liên Hệ',

            'params' => $params,
        ];

        $select = Contact::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("name", "LIKE", $search);
                } elseif ($filter == "phone") {
                    $select->where("phone", "LIKE", $search);
                } elseif ($filter == "email") {
                    $select->where("email", "LIKE", $search);
                } elseif ($filter == "body") {
                    $select->where("body", "LIKE", $search);
                }

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

        $values['items'] = $select->paginate(20);

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.contacts.index", $values);
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_contact')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $contact = Contact::find($itemId);
        if ($contact) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'contact_delete',
                'item_id' => $contact->id,
                'item_type' => 'contact',
            ]);

            $contact->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

}

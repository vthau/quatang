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

use App\Model\Event;

class BEEventsController extends Controller
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
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Góc Tư Vấn',

            'params' => $params,
        ];

        $select = Event::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword']) && isset($params['filter'])) {
                $filter = trim($params['filter']);
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                if ($filter == "name") {
                    $select->where("title", "LIKE", $search);
                } elseif ($filter == "mo_ta") {
                    $select->where("mo_ta_text", "LIKE", $search);
                }
            }

            if (isset($params['active']) && !empty($params['active']) && (int)$params['active']) {
                if ((int)$params['active'] == 1) {
                    $select->where("active", 1);
                } else {
                    $select->where("active", 0);
                }
            }

            if (isset($params['order'])) {
                $order = $params['order'];
                switch ($order) {
                    case 'newest':
                        $order = "id";
                        break;
                    case 'alphabet':
                    case 'view_count':
                    default:
                }
            }
            if (isset($params['order-by'])) {
                $orderBy = $params['order-by'];
            }
        }

        if ($order == "alphabet") {
            $select->orderByRaw("TRIM(LOWER(title)) {$orderBy}");
        } else {
            $select->orderBy("{$order}", $orderBy);
        }

        $values['items'] = $select->paginate(20);

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.events.index", $values);
    }

    public function add(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        $params = $request->all();
        $pageTitle = 'Tạo Bài';
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $event = Event::find($itemId);
        if ($event) {
            $pageTitle = 'Sửa Thông Tin Bài';
        }

        $values = [
            'page_title' => $pageTitle,
            'item' => $event,
        ];

        return view("pages.back_end.events.add", $values);
    }

    public function save(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        if (!count($request->post())) {
            return redirect('/admin/events');
        }
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $event = Event::find($itemId);

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($values['title']);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('event', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $active = (!empty($values['active']) && $values['active'] == 'on') ? 1 : 0;

        $values['mo_ta_text'] = html_entity_decode(trim(strip_tags($values['mo_ta'])));
        $values['mo_ta_text'] = filter_var($values['mo_ta_text'], FILTER_SANITIZE_STRING);

        $updates = [
            'title' => trim(strip_tags($values['title'])),
            'href' => $values['href'],
            'active' => $active,
            'mo_ta' => $values['mo_ta'],
            'mo_ta_ngan' => $values['mo_ta_ngan'],
            'mo_ta_text' => $values['mo_ta_text'],
        ];

        if ($event) {

            $itemOLD = (array)$event->toArray();

            $event->update($updates);

            $event = Event::find($event->id);
            $itemNEW = (array)$event->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'event_edit',
                'item_id' => $event->id,
                'item_type' => 'event',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {

            $event = Event::create($updates);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'event_add',
                'item_id' => $event->id,
                'item_type' => 'event',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            //remove old
            $event->removeAvatar();

            $imageName = 'event_logo_' . $event->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys_event/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/sys_event/'), $imageName);
            $event->uploadAvatar($imageName, $imagePath);
        }

        //banner
        if (!empty($request->file('banner'))) {
            //remove old
            $event->removeBanner();

            $imageName = 'event_banner_' . $event->id . '.' . $request->file('banner')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys_event/" . $imageName;
            $request->file('banner')->move(public_path('/uploaded/sys_event/'), $imageName);
            $event->uploadBanner($imageName, $imagePath);
        }

        return redirect('/admin/events');
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $status = (isset($values['status'])) ? $this->_apiCore->cleanStr($values['status']) : NULL;
        $event = Event::find($itemId);
        if ($event) {
            switch ($status) {
                case 'active':
                    $event->update([
                        'active' => $value,
                    ]);
                    break;
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'event_update',
                'item_id' => $event->id,
                'item_type' => 'event',
                'params' => json_encode([
                    'type' => $status,
                    'value' => $value,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $event = Event::find($itemId);
        if ($event) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'event_delete',
                'item_id' => $event->id,
                'item_type' => 'event',
            ]);

            $event->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function featured(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tu_van')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;

        $event = Event::find($itemId);
        if ($event) {
            $event->update([
                'featured' => $value,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'event_update',
                'item_id' => $event->id,
                'item_type' => 'event',
                'params' => json_encode([
                    'type' => 'featured',
                    'value' => $value,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }

}

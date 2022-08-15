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

use App\Model\News;

class BENewsController extends Controller
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
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Tin Tức',

            'params' => $params,
        ];

        $select = News::where('deleted', 0);

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

        return view("pages.back_end.news.index", $values);
    }

    public function add(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        $params = $request->all();
        $pageTitle = 'Tạo Bài';
        $itemId = (isset($params['id'])) ? (int)$params['id'] : 0;
        $news = News::find($itemId);
        if ($news) {
            $pageTitle = 'Sửa Thông Tin Bài';
        }

        $values = [
            'page_title' => $pageTitle,

            'item' => $news,
        ];

        return view("pages.back_end.news.add", $values);
    }

    public function save(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        if (!count($request->post())) {
            return redirect('/admin/news');
        }
        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $news = News::find($itemId);

        unset($values['_token']);

        //href
        $title = $this->_apiCore->stripVN($itemTitle);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $values['href'] = $this->_apiCore->generateHref('news', array(
            'id' => $itemId,
            'name' => $title,
        ));

        $active = (!empty($values['active']) && $values['active'] == 'on') ? 1 : 0;

        $values['mo_ta_text'] = html_entity_decode(trim(strip_tags($values['mo_ta'])));
        $values['mo_ta_text'] = filter_var($values['mo_ta_text'], FILTER_SANITIZE_STRING);

        $updates = [
            'title' => $itemTitle,
            'href' => $values['href'],
            'active' => $active,
            'mo_ta' => $values['mo_ta'],
            'mo_ta_ngan' => $values['mo_ta_ngan'],
            'mo_ta_text' => $values['mo_ta_text'],
        ];

        if ($news) {
            $itemOLD = (array)$news->toArray();

            $news->update($updates);

            $news = News::find($news->id);
            $itemNEW = (array)$news->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'news_edit',
                'item_id' => $news->id,
                'item_type' => 'news',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {

            $news = News::create($updates);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'news_add',
                'item_id' => $news->id,
                'item_type' => 'news',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //avatar
        if (!empty($request->file('avatar'))) {
            //remove old
            $news->removeAvatar();

            $imageName = 'news_logo_' . $news->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys_news/" . $imageName;
            $request->file('avatar')->move(public_path('/uploaded/sys_news/'), $imageName);
            $news->uploadAvatar($imageName, $imagePath);
        }

        //banner
        if (!empty($request->file('banner'))) {
            //remove old
            $news->removeBanner();

            $imageName = 'news_banner_' . $news->id . '.' . $request->file('banner')->getClientOriginalExtension();
            $imagePath = "/uploaded/sys_news/" . $imageName;
            $request->file('banner')->move(public_path('/uploaded/sys_news/'), $imageName);
            $news->uploadBanner($imageName, $imagePath);
        }

        return redirect('/admin/news');
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $status = (isset($values['status'])) ? $this->_apiCore->cleanStr($values['status']) : NULL;
        $news = News::find($itemId);
        if ($news) {
            switch ($status) {
                case 'active':
                    $news->update([
                        'active' => $value,
                    ]);
                    break;
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'news_update',
                'item_id' => $news->id,
                'item_type' => 'news',
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
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $news = News::find($itemId);
        if ($news) {
            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'news_delete',
                'item_id' => $news->id,
                'item_type' => 'news',
            ]);

            $news->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function featured(Request $request)
    {
        if (!$this->_viewer->isAllowed('setting_tin_tuc')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;

        $news = News::find($itemId);
        if ($news) {
            $news->update([
                'featured' => $value,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'news_update',
                'item_id' => $news->id,
                'item_type' => 'news',
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

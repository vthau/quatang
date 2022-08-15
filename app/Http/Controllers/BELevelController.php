<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\LevelPermission;
use App\Model\UserLevel;

class BELevelController extends Controller
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
        if (!$this->_viewer->isAllowed('staff_level_view')) {
            return redirect('/private');
        }

        $values = [
            'page_title' => 'Quản Lý Quyền Truy Cập',

        ];

        $select = UserLevel::whereNotIn('id', [1, 2, 4])
            ->orderByDesc('id')
        ;

        $values['items'] = $select->get();

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.levels.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('staff_level_add') || $this->_viewer->isAllowed('staff_level_edit'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;

        unset($values['_token']);

        $level = UserLevel::find($itemId);
        if ($level) {
            if (!$this->_viewer->isAllowed('staff_level_edit')) {
                return redirect('/private');
            }

            $itemOLD = (array)$level->toArray();

            $level->update([
                'title' => $itemTitle,
            ]);

            $level = UserLevel::find($level->id);
            $itemNEW = (array)$level->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_level_edit',
                'item_id' => $level->id,
                'item_type' => 'level',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('staff_level_add')) {
                return redirect('/private');
            }

            $level = UserLevel::create([
                'title' => $itemTitle,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_level_add',
                'item_id' => $level->id,
                'item_type' => 'level',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        return redirect('/admin/levels');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('staff_level_delete')) {
            return redirect('/private');
        }
        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $level = UserLevel::find($itemId);
        if ($level && $level->id > 5) {
            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'user_level_delete',
                'item_id' => $level->id,
                'item_type' => 'level',
                'params' => json_encode([
                    'id' => $level->id,
                    'name' => $level->title,
                ])
            ]);

            $level->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function manage(Request $request)
    {
        if (!$this->_viewer->fullPermissions()) {
            return redirect('/private');
        }

        $params = $request->all();

        $itemId = (isset($params['level'])) ? (int)$params['level'] : 6;
        $level = UserLevel::find($itemId);
        if (!$level
            || ($level && $level->id <= 4)
        ) {
            return redirect('/private');
        }

        $values = [
            'page_title' => 'Tùy Chỉnh Quyền Truy Cập',

            'level' => $level,
        ];

        $select = UserLevel::where("id", ">", 5);
        $values['items'] = $select->get();

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        return view("pages.back_end.levels.manage", $values);
    }

    public function update(Request $request)
    {
        if (!$this->_viewer->fullPermissions()) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $levelId = (isset($values['level_id'])) ? (int)$values['level_id'] : 0;
        if ((int)$levelId <= 4) {
            return redirect('/private');
        }

        unset($values['_token']);
        unset($values['level_id']);

        LevelPermission::where("level_id", $levelId)
            ->update(array(
                'is_allowed' => 0,
            ));

        if (count($values)) {
            foreach ($values as $k => $v) {
                LevelPermission::query('level_permissions')
                    ->select('level_permissions.*')
                    ->leftJoin("level_actions", "level_actions.id", "=", "level_permissions.action_id")
                    ->where("level_permissions.level_id", $levelId)
                    ->where("level_actions.title", $k)
                    ->update(array(
                        'is_allowed' => 1,
                    ));
            }
        }

        $this->_apiCore->addLog([
            'user_id' => $this->_viewer->id,
            'action' => 'user_level_config_' . $levelId,
            'item_id' => $this->_viewer->id,
            'item_type' => 'user',
        ]);

        Session::put('MESSAGE', 'ITEM_UPDATED');

        return redirect('/admin/level/manage?level=' . $levelId);
    }
}

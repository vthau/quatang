<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \Session;

use App\Api\Core;

use App\Model\Photo;
use App\Model\SystemCategory;
use App\Model\CardTemplate;

class BECardController extends Controller
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
        if (!$this->_viewer->isAllowed('card_template_view')) {
            return redirect('/private');
        }

        $params = $request->all();
        $values = [
            'page_title' => 'Mẫu Thiệp',

            'params' => $params,
        ];

        $select = CardTemplate::where('deleted', 0);

        //order
        $order = "id";
        $orderBy = "desc";

        if (count($params)) {
            if (isset($params['keyword'])) {
                $search = '%' . str_replace(' ', '%', trim($params['keyword'])) . '%';

                $select->where("title", "LIKE", $search);
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

        $values['items'] = $select->paginate(999);

        //message
        $message = (Session::get('MESSAGE'));
        if (!empty($message)) {
            Session::forget('MESSAGE');
        }
        $values['message'] = $message;

        //system categories
        $select = SystemCategory::where('deleted', 0)
            ->orderByRaw('TRIM(LOWER(title))');
        $values['categories'] = $select->get();

        return view("pages.back_end.cards.index", $values);
    }

    public function save(Request $request)
    {
        if (!($this->_viewer->isAllowed('card_template_add') || $this->_viewer->isAllowed('card_template_edit'))
        ) {
            return redirect('/private');
        }

        $values = $request->post();
//        echo '<pre>';var_dump($values);die;
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $itemTitle = (isset($values['title'])) ? $this->_apiCore->cleanStr($values['title']) : NULL;
        $cateId = (isset($values['system_category_id'])) ? (int)$values['system_category_id'] : 0;
        $oldPhotos = (isset($values['old_photos'])) ? $values['old_photos'] : NULL;

        unset($values['_token']);

        $card = CardTemplate::find($itemId);
        if ($card) {
            if (!$this->_viewer->isAllowed('card_template_edit')) {
                return redirect('/private');
            }

            $itemOLD = (array)$card->toArray();

            $card->update([
                'title' => $itemTitle,
                'system_category_id' => $cateId,
            ]);

            $card = CardTemplate::find($card->id);
            $itemNEW = (array)$card->toArray();

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'card_template_edit',
                'item_id' => $card->id,
                'item_type' => 'card_template',
                'params' => json_encode([
                    'item_old' => $itemOLD,
                    'item_new' => $itemNEW,
                ])
            ]);

            //slides
            $idsOLD = [];
            if (!empty($oldPhotos)) {
                $olds = array_filter(explode(";", $oldPhotos));
                if (count($olds)) {
                    foreach ($olds as $old) {
                        $arr = explode("_", $old);
                        $idsOLD[] = (int)$arr[1];
                    }
                }
                //old
                if (count($idsOLD)) {
                    $olds = Photo::where('item_id', $card->id)
                        ->where('item_type', 'card_template')
                        ->where('type', 'slides')
                        ->where('parent_id', 0)
                        ->get();
                    foreach ($olds as $old) {
                        if (in_array($old->id, $idsOLD)) {
                            $old->delItem();
                        }
                    }
                }
            }

            Session::put('MESSAGE', 'ITEM_EDITED');
        } else {
            if (!$this->_viewer->isAllowed('card_template_add')) {
                return redirect('/private');
            }

            $card = CardTemplate::create([
                'title' => $itemTitle,
                'system_category_id' => $cateId,
            ]);

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'card_template_add',
                'item_id' => $card->id,
                'item_type' => 'card_template',
            ]);

            Session::put('MESSAGE', 'ITEM_ADDED');
        }

        //slides
        $slides = $request->file('slides');
        if (!empty($slides)) {
            $i = 0;
            foreach ($slides as $slide) {
                $i += 1;
                $imageName = 'card_slide_' . $card->id . '_' . time() . '_' . $i . '.' . $slide->getClientOriginalExtension();
                $imagePath = "/uploaded/card/" . $imageName;
                $slide->move(public_path('/uploaded/card/'), $imageName);
                $card->addSlides($imageName, $imagePath);
            }
        }

        return redirect('/admin/cards');
    }

    public function delete(Request $request)
    {
        if (!$this->_viewer->isAllowed('card_template_delete')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;

        $card = CardTemplate::find($itemId);
        if ($card) {

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'card_template_delete',
                'item_id' => $card->id,
                'item_type' => 'card_template',
            ]);

            $card->delItem();

            Session::put('MESSAGE', 'ITEM_DELETED');
        }

        return response()->json([]);
    }

    public function updateStatus(Request $request)
    {
        if (!$this->_viewer->isAllowed('card_template_edit')) {
            return redirect('/private');
        }

        $values = $request->post();
        $itemId = (isset($values['item_id'])) ? (int)$values['item_id'] : 0;
        $value = (isset($values['value'])) ? (int)$values['value'] : 0;
        $column = (isset($values['column'])) ? $this->_apiCore->cleanStr($values['column']) : NULL;

        $card = CardTemplate::find($itemId);
        if ($card && !empty($column)) {

            switch ($column) {
                case 'active':
                    $card->update([
                        'active' => $value,
                    ]);
                    break;

                case 'category':
                    $card->update([
                        'system_category_id' => $value,
                    ]);
                    break;
            }

            $this->_apiCore->addLog([
                'user_id' => $this->_viewer->id,
                'action' => 'card_template_update',
                'item_id' => $card->id,
                'item_type' => 'card_template',
                'params' => json_encode([
                    'type' => 'active',
                    'value' => $value,
                ])
            ]);

            Session::put('MESSAGE', 'ITEM_UPDATED');
        }

        return response()->json([]);
    }
}

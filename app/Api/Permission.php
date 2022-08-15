<?php

namespace App\Api;

use Illuminate\Support\Facades\Auth;

use Intervention\Image\ImageManagerStatic as Image;

use \DateTime;

use App\User;

use App\Model\LevelAction;
use App\Model\LevelPermission;

class Permission
{
    public function addPermissions()
    {
        //add new permission
        $action = new LevelAction();
        $actions = $action->getActions();

        foreach ($actions as $k => $v) {
            //check name
            $row = LevelAction::where("title", $k)->first();
            if ($row && $row->id) {
                continue;
            }
            $type = explode('_', $k);
            $type = $type[0];

            if ($type == 'user') {
                $type = 'staff';
            } elseif (in_array($type, ['wish', 'card', 'system'])) {
                $type = 'template';
            }

            $values = [
                'title' => $k,
                'type' => $type,
            ];

            //insert
            $action = LevelAction::create($values);
            $action->setPermissions();
        }
    }

    public function getTypeActions($type)
    {
        $select = LevelAction::where("type", $type);
        return $select->get();
    }
}

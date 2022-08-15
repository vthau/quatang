<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class UserPerson extends Item
{
    public $table = 'user_persons';

    protected $fillable = [
        'user_id', 'title', 'deleted', 'user_relationship_id',
        'phone', 'address', 'note', 'ward_id', 'district_id', 'province_id',
    ];


    //info
    public function getUser()
    {
        return User::find($this->user_id);
    }

    public function getRelationship()
    {
        return UserRelationship::find($this->user_relationship_id);
    }

    public function getEvents()
    {
        $select = UserPersonDate::where('deleted', 0)
            ->where('user_person_id', $this->id)
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc');
        return $select->get();
    }

    public function getFullAddress()
    {
        $text = $this->address;

        $ward = GhnWard::find($this->ward_id);
        $district = GhnDistrict::find($this->district_id);
        $province = GhnProvince::find($this->province_id);

        if ($ward) {
            $text .= ' ' . $ward->title;
        }
        if ($district) {
            $text .= ' ' . $district->title;
        }
        if ($province) {
            $text .= ' ' . $province->title;
        }

        return $text;
    }

    //delete
    public function delItem()
    {
        $this->update([
            'deleted' => 1,
        ]);
    }

    public function isDeleted()
    {
        return $this->deleted;
    }
}

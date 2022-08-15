<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class CardTemplate extends Item
{
    public $table = 'card_templates';

    protected $fillable = [
        'title', 'active', 'system_category_id', 'deleted',
    ];

    //info
    public function getCategory()
    {
        return SystemCategory::find($this->system_category_id);
    }

    //slides
    public function addSlides($name, $path)
    {
        $apiCore = new Core();
        //rotate image mobile upload
        $storagePath = public_path('/' . $path);
        $apiCore->rotateImage($storagePath);

        $row = Photo::create([
            'item_type' => 'card_template',
            'item_id' => $this->id,
            'type' => 'slides',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function getSlides()
    {
        $select = Photo::where('item_type', 'card_template')
            ->where('item_id', $this->id)
            ->where('parent_id', 0)
            ->where('type', 'slides')
            ->orderBy('id', 'ASC');
        return $select->get();
    }

    public function removeSlides()
    {
        $olds = Photo::where('item_id', $this->id)
            ->where('item_type', 'card_template')
            ->where('type', 'slides')
            ->where('parent_id', 0)
            ->get();
        foreach ($olds as $old) {
            $old->delItem();
        }
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
        return $this->deleted ? true : false;
    }
}

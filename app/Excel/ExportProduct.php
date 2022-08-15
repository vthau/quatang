<?php

namespace App\Excel;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\User;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportProduct implements FromView
{
    private $items;

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function view(): View
    {

        return view('excel.export_product', [
            'items' => $this->items,
        ]);
    }
}

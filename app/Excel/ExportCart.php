<?php

namespace App\Excel;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\User;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportCart implements FromView, WithEvents
{
    private $cart;

    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $sheet = $event->sheet;

//                $sheet->getStyle('B9:B' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
//                $sheet->getStyle('C9:C' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
//
//                $styleArray = [
//                    'borders' => [
//                        'outline' => [
//                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                            'color' => ['argb' => '00000000'],
//                        ],
//                        'inline' => [
//                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                            'color' => ['argb' => '00000000'],
//                        ],
//                    ],
//                    'alignment' => [
//                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                    ],
//                ];
//
//                $sheet->getStyle('A7:A8')->applyFromArray($styleArray);
//                $sheet->getStyle('B7:B8')->applyFromArray($styleArray);
//                $sheet->getStyle('C7:C8')->applyFromArray($styleArray);
//                $sheet->getStyle('D7:D8')->applyFromArray($styleArray);
//                $sheet->getStyle('E7:E8')->applyFromArray($styleArray);
//                $sheet->getStyle('F7:H7')->applyFromArray($styleArray);
//                $sheet->getStyle('F8')->applyFromArray($styleArray);
//                $sheet->getStyle('G8')->applyFromArray($styleArray);
//                $sheet->getStyle('H8')->applyFromArray($styleArray);
//                $sheet->getStyle('I7')->applyFromArray($styleArray);
//                $sheet->getStyle('I8')->applyFromArray($styleArray);
//                $sheet->getStyle('J7')->applyFromArray($styleArray);
//                $sheet->getStyle('J8')->applyFromArray($styleArray);
//                $sheet->getStyle('K7:K8')->applyFromArray($styleArray);
//                $sheet->getStyle('L7:L8')->applyFromArray($styleArray);
//                $sheet->getStyle('M7:M8')->applyFromArray($styleArray);
//                $sheet->getStyle('N7:N8')->applyFromArray($styleArray);
//
//                $sheet->getStyle('A9:A' . ((int)$sheet->getHighestRow() - 1))->applyFromArray($styleArray);
//                $sheet->getStyle('D9:M' . ((int)$sheet->getHighestRow() - 1))->applyFromArray($styleArray);

//                $styleArray = [
//                    'borders' => [
//                        'outline' => [
//                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                            'color' => ['argb' => '00000000'],
//                        ],
//                        'inline' => [
//                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                            'color' => ['argb' => '00000000'],
//                        ],
//                    ],
//                    'alignment' => [
//                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
//                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
//                    ],
//                ];
//
//                $sheet->getStyle('E8:E' . ((int)$sheet->getHighestRow() - 1))->applyFromArray($styleArray);

            },
        ];
    }

    public function view(): View
    {
        return view('excel.export_cart', [
            'cart' => $this->cart,
        ]);
    }
}

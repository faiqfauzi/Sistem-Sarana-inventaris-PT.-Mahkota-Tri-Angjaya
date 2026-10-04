<?php

namespace App\Exports\Sheets;

use App\Models\Inventory;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RepairSheet
implements
    FromCollection,
    WithHeadings,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    protected $inventory;


    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }


    public function title(): string
    {
        return 'Perbaikan';
    }


    public function headings(): array
    {
        return [

            'Tanggal',

            'Jenis Perbaikan',

            'Pelaksana',

        ];
    }


    public function collection()
    {
        return $this->inventory
            ->repairs
            ->map(function ($repair) {

                return [

                    $repair->tanggal
                        ?->format('d-m-Y'),

                    $repair->jenis_perbaikan,

                    $repair->pelaksana,

                ];
            });
    }


    public function styles(Worksheet $sheet)
    {
        return [

            1 => [

                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'F59F00',
                    ],
                ],

                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],

            ],

        ];
    }


    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();

                $highestColumn = $sheet->getHighestColumn();


                $sheet
                    ->getStyle(
                        'A1:' . $highestColumn . $highestRow
                    )
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    );


                $sheet
                    ->getStyle(
                        'A1:' . $highestColumn . $highestRow
                    )
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );


                $sheet
                    ->getStyle(
                        'A1:' . $highestColumn . $highestRow
                    )
                    ->getAlignment()
                    ->setWrapText(true);


                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(25);


                $sheet->freezePane('A2');


                $sheet->setAutoFilter(
                    'A1:' . $highestColumn . $highestRow
                );
            },

        ];
    }
}

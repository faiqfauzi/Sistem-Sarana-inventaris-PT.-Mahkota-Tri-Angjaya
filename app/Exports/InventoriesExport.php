<?php

namespace App\Exports;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class InventoriesExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithDrawings
{
    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    protected $inventories;

    protected $columns;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        Collection $inventories,
        array $columns
    ) {

        $this->inventories = $inventories;

        $this->columns = $columns;
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL KOLOM
    |--------------------------------------------------------------------------
    */

    private function columnLabels()
    {
        return [

            'nomor_inventaris' =>
            'Nomor Inventaris',

            'tanggal_perolehan' =>
            'Tanggal Perolehan',

            'sarana_inventaris' =>
            'Sarana Inventaris',

            'foto' =>
            'Foto Inventaris',

            'spesifikasi' =>
            'Spesifikasi',

            'lokasi' =>
            'Lokasi',

            'user' =>
            'User',

            'penanggung_jawab' =>
            'Penanggung Jawab',

            'tanggal_perawatan' =>
            'Tanggal Perawatan',

            'jenis_perawatan' =>
            'Jenis Perawatan',

            'tanggal_perbaikan' =>
            'Tanggal Perbaikan',

            'jenis_perbaikan' =>
            'Jenis Perbaikan',

            'foto_so' =>
            'Foto Stock Opname',

            'tanggal_so' =>
            'Tanggal Stock Opname',

            'status_kondisi' =>
            'Status / Kondisi',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        $labels = $this->columnLabels();


        /*
        |--------------------------------------------------------------------------
        | NO SELALU DI DEPAN
        |--------------------------------------------------------------------------
        */

        $headings = [

            'No'

        ];


        /*
        |--------------------------------------------------------------------------
        | KOLOM YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        foreach ($this->columns as $column) {

            if (isset($labels[$column])) {

                $headings[] =
                    $labels[$column];
            }
        }


        return $headings;
    }


    /*
    |--------------------------------------------------------------------------
    | DATA EXCEL
    |--------------------------------------------------------------------------
    */

    public function collection()
    {
        return $this->inventories

            ->values()

            ->map(function ($inventory, $index) {


                /*
                |--------------------------------------------------------------------------
                | RIWAYAT TERBARU
                |--------------------------------------------------------------------------
                */

                $maintenance =
                    $inventory->maintenances->first();


                $repair =
                    $inventory->repairs->first();


                $stockOpname =
                    $inventory->stockOpnames->first();


                /*
                |--------------------------------------------------------------------------
                | NOMOR
                |--------------------------------------------------------------------------
                */

                $row = [

                    $index + 1

                ];


                /*
                |--------------------------------------------------------------------------
                | KOLOM
                |--------------------------------------------------------------------------
                */

                foreach ($this->columns as $column) {

                    switch ($column) {


                        /*
                        |--------------------------------------------------------------------------
                        | NOMOR INVENTARIS
                        |--------------------------------------------------------------------------
                        */

                        case 'nomor_inventaris':

                            $row[] =
                                $inventory->nomor_inventaris
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL PEROLEHAN
                        |--------------------------------------------------------------------------
                        */

                        case 'tanggal_perolehan':

                            $row[] =
                                $inventory->tanggal_perolehan
                                ?->format('d-m-Y')
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | SARANA INVENTARIS
                        |--------------------------------------------------------------------------
                        */

                        case 'sarana_inventaris':

                            $row[] =
                                $inventory->masterSaranaInventaris
                                ?->nama_sarana_inventaris
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | FOTO INVENTARIS
                        |--------------------------------------------------------------------------
                        */

                        case 'foto':

                            /*
                            | Foto dimasukkan melalui
                            | method drawings()
                            */

                            $row[] = '';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | SPESIFIKASI
                        |--------------------------------------------------------------------------
                        */

                        case 'spesifikasi':

                            $row[] =
                                $inventory->spesifikasi
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | LOKASI
                        |--------------------------------------------------------------------------
                        */

                        case 'lokasi':

                            $row[] =
                                $inventory->location
                                ?->nama_lokasi
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | USER
                        |--------------------------------------------------------------------------
                        */

                        case 'user':

                            $row[] =
                                $inventory->user
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | PENANGGUNG JAWAB
                        |--------------------------------------------------------------------------
                        */

                        case 'penanggung_jawab':

                            $row[] =
                                $inventory->responsible
                                ?->nama_penanggung_jawab
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL PERAWATAN
                        |--------------------------------------------------------------------------
                        */

                        case 'tanggal_perawatan':

                            $row[] =
                                $maintenance
                                ?->tanggal_perawatan
                                ?->format('d-m-Y')
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | JENIS PERAWATAN
                        |--------------------------------------------------------------------------
                        */

                        case 'jenis_perawatan':

                            $row[] =
                                $maintenance
                                ?->jenis_perawatan
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL PERBAIKAN
                        |--------------------------------------------------------------------------
                        */

                        case 'tanggal_perbaikan':

                            $row[] =
                                $repair
                                ?->tanggal_pengajuan
                                ?->format('d-m-Y')
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | JENIS PERBAIKAN
                        |--------------------------------------------------------------------------
                        */

                        case 'jenis_perbaikan':

                            $row[] =
                                $repair
                                ?->jenis_perbaikan
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | FOTO STOCK OPNAME
                        |--------------------------------------------------------------------------
                        */

                        case 'foto_so':

                            /*
                            | Foto dimasukkan melalui
                            | method drawings()
                            */

                            $row[] = '';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL STOCK OPNAME
                        |--------------------------------------------------------------------------
                        */

                        case 'tanggal_so':

                            $row[] =
                                $stockOpname
                                ?->tanggal
                                ?->format('d-m-Y')
                                ?: '-';

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS KONDISI
                        |--------------------------------------------------------------------------
                        */

                        case 'status_kondisi':

                            $row[] =
                                $stockOpname
                                ?->kondisi
                                ?: (
                                    $inventory->status
                                    ?: '-'
                                );

                            break;
                    }
                }


                return $row;
            });
    }


    /*
    |--------------------------------------------------------------------------
    | FOTO EXCEL
    |--------------------------------------------------------------------------
    */

    public function drawings()
    {
        $drawings = [];


        /*
        |--------------------------------------------------------------------------
        | POSISI FOTO INVENTARIS
        |--------------------------------------------------------------------------
        */

        $fotoColumnIndex =
            array_search(
                'foto',
                $this->columns
            );


        /*
        |--------------------------------------------------------------------------
        | POSISI FOTO STOCK OPNAME
        |--------------------------------------------------------------------------
        */

        $fotoSoColumnIndex =
            array_search(
                'foto_so',
                $this->columns
            );


        /*
        |--------------------------------------------------------------------------
        | LOOP DATA
        |--------------------------------------------------------------------------
        */

        foreach (
            $this->inventories->values()
            as $index => $inventory
        ) {


            /*
            |--------------------------------------------------------------------------
            | DATA MULAI BARIS 2
            |--------------------------------------------------------------------------
            */

            $rowNumber =
                $index + 2;


            /*
            |--------------------------------------------------------------------------
            | FOTO INVENTARIS
            |--------------------------------------------------------------------------
            */

            if (
                $fotoColumnIndex !== false &&
                !empty($inventory->foto)
            ) {

                $path =
                    storage_path(
                        'app/public/' .
                            $inventory->foto
                    );


                if (file_exists($path)) {

                    /*
                    |--------------------------------------------------------------------------
                    | +2
                    |
                    | A = kolom No
                    |--------------------------------------------------------------------------
                    */

                    $columnNumber =
                        $fotoColumnIndex + 2;


                    $columnLetter =
                        Coordinate::stringFromColumnIndex(
                            $columnNumber
                        );


                    $drawing =
                        new Drawing();


                    $drawing->setName(
                        'Foto Inventaris'
                    );


                    $drawing->setDescription(
                        'Foto Inventaris'
                    );


                    $drawing->setPath(
                        $path
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UKURAN FOTO
                    |--------------------------------------------------------------------------
                    */

                    $drawing->setHeight(70);


                    /*
                    |--------------------------------------------------------------------------
                    | POSISI
                    |--------------------------------------------------------------------------
                    */

                    $drawing->setCoordinates(
                        $columnLetter .
                            $rowNumber
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | OFFSET
                    |--------------------------------------------------------------------------
                    */

                    $drawing->setOffsetX(10);

                    $drawing->setOffsetY(5);


                    $drawings[] =
                        $drawing;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | STOCK OPNAME TERBARU
            |--------------------------------------------------------------------------
            */

            $stockOpname =
                $inventory->stockOpnames->first();


            /*
            |--------------------------------------------------------------------------
            | FOTO STOCK OPNAME
            |--------------------------------------------------------------------------
            */

            if (
                $fotoSoColumnIndex !== false &&
                $stockOpname &&
                !empty($stockOpname->foto)
            ) {

                $path =
                    storage_path(
                        'app/public/' .
                            $stockOpname->foto
                    );


                if (file_exists($path)) {

                    $columnNumber =
                        $fotoSoColumnIndex + 2;


                    $columnLetter =
                        Coordinate::stringFromColumnIndex(
                            $columnNumber
                        );


                    $drawing =
                        new Drawing();


                    $drawing->setName(
                        'Foto Stock Opname'
                    );


                    $drawing->setDescription(
                        'Foto Stock Opname'
                    );


                    $drawing->setPath(
                        $path
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UKURAN FOTO
                    |--------------------------------------------------------------------------
                    */

                    $drawing->setHeight(70);


                    /*
                    |--------------------------------------------------------------------------
                    | POSISI
                    |--------------------------------------------------------------------------
                    */

                    $drawing->setCoordinates(
                        $columnLetter .
                            $rowNumber
                    );


                    $drawing->setOffsetX(10);

                    $drawing->setOffsetY(5);


                    $drawings[] =
                        $drawing;
                }
            }
        }


        return $drawings;
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT EXCEL
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [

            AfterSheet::class =>
            function (AfterSheet $event) {


                $sheet =
                    $event
                    ->sheet
                    ->getDelegate();


                $highestRow =
                    $sheet->getHighestRow();


                $highestColumn =
                    $sheet->getHighestColumn();


                /*
                |--------------------------------------------------------------------------
                | AREA SELURUH DATA
                |--------------------------------------------------------------------------
                */

                $tableRange =
                    'A1:' .
                    $highestColumn .
                    $highestRow;


                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                $headerRange =
                    'A1:' .
                    $highestColumn .
                    '1';


                /*
                |--------------------------------------------------------------------------
                | WARNA HEADER
                |
                | HIJAU ELEGAN
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setRGB(
                        '198754'
                    );


                /*
                |--------------------------------------------------------------------------
                | FONT HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle($headerRange)
                    ->getFont()
                    ->setBold(true)
                    ->setSize(11)
                    ->getColor()
                    ->setRGB(
                        'FFFFFF'
                    );


                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    )
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    )
                    ->setWrapText(true);


                /*
                |--------------------------------------------------------------------------
                | BORDER SELURUH TABEL
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle($tableRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    )
                    ->getColor()
                    ->setRGB(
                        'D1D5DB'
                    );


                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT SELURUH DATA
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle($tableRange)
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    )
                    ->setWrapText(true);


                /*
                |--------------------------------------------------------------------------
                | TINGGI HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(42);


                /*
                |--------------------------------------------------------------------------
                | TINGGI BARIS DATA
                |--------------------------------------------------------------------------
                */

                for (
                    $row = 2;
                    $row <= $highestRow;
                    $row++
                ) {

                    $sheet
                        ->getRowDimension($row)
                        ->setRowHeight(62);


                    /*
                    |--------------------------------------------------------------------------
                    | WARNA SELANG-SELING
                    |--------------------------------------------------------------------------
                    */

                    if ($row % 2 === 0) {

                        $sheet
                            ->getStyle(
                                'A' .
                                    $row .
                                    ':' .
                                    $highestColumn .
                                    $row
                            )
                            ->getFill()
                            ->setFillType(
                                Fill::FILL_SOLID
                            )
                            ->getStartColor()
                            ->setRGB(
                                'F8FAFC'
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | KOLOM NOMOR
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getColumnDimension('A')
                    ->setWidth(7);


                $sheet
                    ->getStyle(
                        'A2:A' .
                            $highestRow
                    )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );


                /*
                |--------------------------------------------------------------------------
                | ATUR LEBAR KOLOM
                |--------------------------------------------------------------------------
                */

                foreach (
                    $this->columns
                    as $index => $column
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | +2
                    |
                    | Karena:
                    | A = No
                    |--------------------------------------------------------------------------
                    */

                    $columnNumber =
                        $index + 2;


                    $columnLetter =
                        Coordinate::stringFromColumnIndex(
                            $columnNumber
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | DEFAULT WIDTH
                    |--------------------------------------------------------------------------
                    */

                    $width = 18;


                    switch ($column) {


                        /*
                        |--------------------------------------------------------------------------
                        | NOMOR INVENTARIS
                        |--------------------------------------------------------------------------
                        */

                        case 'nomor_inventaris':

                            $width = 18;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | TANGGAL
                        |--------------------------------------------------------------------------
                        */

                        case 'tanggal_perolehan':

                        case 'tanggal_perawatan':

                        case 'tanggal_perbaikan':

                        case 'tanggal_so':

                            $width = 15;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | SARANA
                        |--------------------------------------------------------------------------
                        */

                        case 'sarana_inventaris':

                            $width = 22;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | FOTO
                        |--------------------------------------------------------------------------
                        */

                        case 'foto':

                        case 'foto_so':

                            $width = 16;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | SPESIFIKASI
                        |--------------------------------------------------------------------------
                        */

                        case 'spesifikasi':

                            $width = 25;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | LOKASI
                        |--------------------------------------------------------------------------
                        */

                        case 'lokasi':

                            $width = 16;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | USER
                        |--------------------------------------------------------------------------
                        */

                        case 'user':

                            $width = 18;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | PENANGGUNG JAWAB
                        |--------------------------------------------------------------------------
                        */

                        case 'penanggung_jawab':

                            $width = 20;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | JENIS
                        |--------------------------------------------------------------------------
                        */

                        case 'jenis_perawatan':

                        case 'jenis_perbaikan':

                            $width = 20;

                            break;


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        case 'status_kondisi':

                            $width = 18;

                            break;
                    }


                    $sheet
                        ->getColumnDimension(
                            $columnLetter
                        )
                        ->setWidth(
                            $width
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | KOLOM FOTO DITENGAH
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $column === 'foto' ||
                        $column === 'foto_so'
                    ) {

                        $sheet
                            ->getStyle(
                                $columnLetter .
                                    '2:' .
                                    $columnLetter .
                                    $highestRow
                            )
                            ->getAlignment()
                            ->setHorizontal(
                                Alignment::HORIZONTAL_CENTER
                            )
                            ->setVertical(
                                Alignment::VERTICAL_CENTER
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | KOLOM TANGGAL DITENGAH
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $column === 'tanggal_perolehan' ||
                        $column === 'tanggal_perawatan' ||
                        $column === 'tanggal_perbaikan' ||
                        $column === 'tanggal_so'
                    ) {

                        $sheet
                            ->getStyle(
                                $columnLetter .
                                    '2:' .
                                    $columnLetter .
                                    $highestRow
                            )
                            ->getAlignment()
                            ->setHorizontal(
                                Alignment::HORIZONTAL_CENTER
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS DITENGAH
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $column === 'status_kondisi'
                    ) {

                        $sheet
                            ->getStyle(
                                $columnLetter .
                                    '2:' .
                                    $columnLetter .
                                    $highestRow
                            )
                            ->getAlignment()
                            ->setHorizontal(
                                Alignment::HORIZONTAL_CENTER
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->freezePane('A2');


                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->setAutoFilter(
                        'A1:' .
                            $highestColumn .
                            $highestRow
                    );
            }

        ];
    }
}

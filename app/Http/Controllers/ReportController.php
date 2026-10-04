<?php

namespace App\Http\Controllers;

use App\Exports\InventoriesExport;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\MasterSaranaInventaris;
use App\Models\Responsible;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR KOLOM LAPORAN
    |--------------------------------------------------------------------------
    */

    private function availableColumns()
    {
        return [

            'nomor_inventaris' =>
            'Nomor Inventaris',

            'tanggal_perolehan' =>
            'Tanggal Perolehan',

            'sarana_inventaris' =>
            'Sarana Inventaris',

            'foto' =>
            'Foto',

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
            'Foto SO',

            'tanggal_so' =>
            'Tanggal SO',

            'status_kondisi' =>
            'Status / Kondisi',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX LAPORAN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATA FILTER
        |--------------------------------------------------------------------------
        */

        $locations = Location::orderBy(
            'nama_lokasi'
        )->get();


        $responsibles = Responsible::orderBy(
            'nama_penanggung_jawab'
        )->get();


        $masterSaranaInventaris =
            MasterSaranaInventaris::orderBy(
                'nama_sarana_inventaris'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | QUERY INVENTARIS
        |--------------------------------------------------------------------------
        */

        $query = Inventory::with([

            'masterSaranaInventaris',

            'location',

            'responsible',

            /*
            |--------------------------------------------------------------------------
            | RIWAYAT PERAWATAN TERBARU
            |--------------------------------------------------------------------------
            */

            'maintenances' => function ($query) {

                $query
                    ->latest('tanggal_perawatan');
            },


            /*
            |--------------------------------------------------------------------------
            | RIWAYAT PERBAIKAN TERBARU
            |--------------------------------------------------------------------------
            */

            'repairs' => function ($query) {

                $query
                    ->latest('tanggal_pengajuan');
            },


            /*
            |--------------------------------------------------------------------------
            | STOCK OPNAME TERBARU
            |--------------------------------------------------------------------------
            */

            'stockOpnames' => function ($query) {

                $query
                    ->latest('tanggal');
            },

        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTER DARI TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_awal')) {

            $query->whereDate(

                'tanggal_perolehan',

                '>=',

                $request->tanggal_awal

            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER SAMPAI TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_akhir')) {

            $query->whereDate(

                'tanggal_perolehan',

                '<=',

                $request->tanggal_akhir

            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER SARANA INVENTARIS
        |--------------------------------------------------------------------------
        */

        if ($request->filled(
            'master_sarana_inventaris_id'
        )) {

            $query->where(

                'master_sarana_inventaris_id',

                $request->master_sarana_inventaris_id

            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER LOKASI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location_id')) {

            $query->where(

                'location_id',

                $request->location_id

            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER PENANGGUNG JAWAB
        |--------------------------------------------------------------------------
        */

        if ($request->filled('responsible_id')) {

            $query->where(

                'responsible_id',

                $request->responsible_id

            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA INVENTARIS
        |--------------------------------------------------------------------------
        */

        $inventories = $query

            ->orderBy('nomor_inventaris')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | DAFTAR KOLOM LAPORAN
        |--------------------------------------------------------------------------
        */

        $availableColumns =
            $this->availableColumns();


        return view(

            'report.index',

            compact(

                'locations',

                'responsibles',

                'masterSaranaInventaris',

                'inventories',

                'availableColumns'

            )

        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT LAPORAN
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'inventory_ids' => [

                'required',

                'array',

                'min:1',

            ],


            'inventory_ids.*' => [

                'integer',

                'exists:inventories,id',

            ],


            'columns' => [

                'required',

                'array',

                'min:1',

            ],


            'format' => [

                'required',

                'in:excel,pdf',

            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KOLOM
        |--------------------------------------------------------------------------
        */

        $availableColumns = array_keys(

            $this->availableColumns()

        );


        foreach ($request->columns as $column) {

            if (!in_array(
                $column,
                $availableColumns
            )) {

                abort(
                    422,
                    'Kolom laporan tidak valid.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL INVENTARIS TERPILIH
        |--------------------------------------------------------------------------
        */

        $inventories = Inventory::with([

            'masterSaranaInventaris',

            'location',

            'responsible',


            /*
            |--------------------------------------------------------------------------
            | PERAWATAN TERBARU
            |--------------------------------------------------------------------------
            */

            'maintenances' => function ($query) {

                $query
                    ->latest('tanggal_perawatan')
                    ->take(1);
            },


            /*
            |--------------------------------------------------------------------------
            | PERBAIKAN TERBARU
            |--------------------------------------------------------------------------
            */

            'repairs' => function ($query) {

                $query
                    ->latest('tanggal_pengajuan')
                    ->take(1);
            },


            /*
            |--------------------------------------------------------------------------
            | STOCK OPNAME TERBARU
            |--------------------------------------------------------------------------
            */

            'stockOpnames' => function ($query) {

                $query
                    ->latest('tanggal')
                    ->take(1);
            },

        ])

            ->whereIn(
                'id',
                $request->inventory_ids
            )

            ->orderBy(
                'nomor_inventaris'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | CEK DATA
        |--------------------------------------------------------------------------
        */

        if ($inventories->isEmpty()) {

            return back()->with(

                'error',

                'Tidak ada data inventaris untuk dibuat laporan.'

            );
        }


        /*
        |--------------------------------------------------------------------------
        | EXPORT EXCEL
        |--------------------------------------------------------------------------
        */

        if ($request->format === 'excel') {

            return Excel::download(

                new InventoriesExport(

                    $inventories,

                    $request->columns

                ),

                'laporan-inventaris-' .
                    now()->format('Ymd-His') .
                    '.xlsx'

            );
        }


        /*
        |--------------------------------------------------------------------------
        | EXPORT PDF
        |--------------------------------------------------------------------------
        */

        $columns =
            $request->columns;


        $columnLabels =
            $this->availableColumns();


        $pdf = Pdf::loadView(

            'report.pdf',

            compact(

                'inventories',

                'columns',

                'columnLabels'

            )

        );


        /*
        |--------------------------------------------------------------------------
        | UKURAN PDF
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(

            'A4',

            'landscape'

        );


        return $pdf->stream(

            'laporan-inventaris-' .
                now()->format('Ymd-His') .
                '.pdf'

        );
    }
}

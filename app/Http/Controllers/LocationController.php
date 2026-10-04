<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LocationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES ADMIN
    |--------------------------------------------------------------------------
    */

    private function authorizeAdmin()
    {
        if (
            !auth()->check() ||
            auth()->user()->role !== 'admin'
        ) {
            abort(
                403,
                'Hanya Administrator yang dapat mengelola lokasi.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $locations = Location::withCount('inventories')
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(
                        'nama_lokasi',
                        'like',
                        "%{$search}%"
                    );
                }
            )
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        return view(
            'locations.index',
            compact('locations', 'search')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Location $location)
    {
        /*
    |--------------------------------------------------------------------------
    | RINGKASAN SARANA INVENTARIS
    |--------------------------------------------------------------------------
    */

        $summary = $location->inventories()
            ->with('masterSaranaInventaris')
            ->get()
            ->groupBy('master_sarana_inventaris_id')
            ->map(function ($items) {

                return [
                    'nama' => $items->first()
                        ->masterSaranaInventaris
                        ->nama_sarana_inventaris,

                    'jumlah' => $items->count(),
                ];
            })
            ->values();


        /*
    |--------------------------------------------------------------------------
    | DAFTAR INVENTARIS DI LOKASI
    |--------------------------------------------------------------------------
    */

        $inventories = $location->inventories()
            ->with([
                'masterSaranaInventaris',
                'responsible',
            ])
            ->orderBy('nomor_inventaris')
            ->paginate(10);


        return view(
            'locations.show',
            compact(
                'location',
                'summary',
                'inventories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->authorizeAdmin();

        return view('locations.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama_lokasi' =>
            'required|string|max:255|unique:locations,nama_lokasi',

            'keterangan' =>
            'nullable|string',
        ]);

        Location::create([
            'nama_lokasi' => $request->nama_lokasi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Lokasi berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Location $location)
    {
        $this->authorizeAdmin();

        return view(
            'locations.edit',
            compact('location')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Location $location
    ) {
        $this->authorizeAdmin();

        $request->validate([
            'nama_lokasi' =>
            'required|string|max:255|unique:locations,nama_lokasi,' .
                $location->id,

            'keterangan' =>
            'nullable|string',
        ]);

        $location->update([
            'nama_lokasi' => $request->nama_lokasi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Lokasi berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Location $location)
    {
        $this->authorizeAdmin();

        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Lokasi berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | QR
    |--------------------------------------------------------------------------
    */

    public function qr(Location $location)
    {
        $inventories = $location->inventories()
            ->with('masterSaranaInventaris')
            ->orderBy('nomor_inventaris')
            ->get();

        $summary = $inventories
            ->groupBy(function ($inventory) {
                return $inventory->masterSaranaInventaris?->nama_sarana_inventaris ?? '-';
            })
            ->map(function ($items, $nama) {
                return [
                    'nama' => $nama,
                    'jumlah' => $items->count(),
                ];
            })
            ->values();

        return view(
            'locations.qr',
            compact(
                'location',
                'inventories',
                'summary'
            )
        );
    }

    public function document(Location $location)
    {
        $summary = $location->inventories()
            ->with('masterSaranaInventaris')
            ->get()
            ->groupBy('master_sarana_inventaris_id')
            ->map(function ($items) {
                return [
                    'nama' => $items->first()
                        ->masterSaranaInventaris
                        ->nama_sarana_inventaris,

                    'jumlah' => $items->count(),
                ];
            })
            ->values();

        $inventories = $location->inventories()
            ->with([
                'masterSaranaInventaris',
                'responsible',
            ])
            ->orderBy('nomor_inventaris')
            ->get();

        $pdf = Pdf::loadView(
            'locations.document',
            compact(
                'location',
                'summary',
                'inventories'
            )
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream(
            'lokasi-' . $location->id . '.pdf'
        );
    }


    public function printQr()
    {
        $locations = Location::orderBy('nama_lokasi')->get();

        return view(
            'locations.print-qr',
            compact('locations')
        );
    }
}

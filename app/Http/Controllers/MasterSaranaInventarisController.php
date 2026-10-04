<?php

namespace App\Http\Controllers;

use App\Models\MasterSaranaInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterSaranaInventarisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $masterSaranaInventaris = MasterSaranaInventaris::withCount('inventories')
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(
                        'nama_sarana_inventaris',
                        'like',
                        "%{$search}%"
                    );
                }
            )
            ->orderBy('nama_sarana_inventaris')
            ->paginate(10);

        return view(
            'master-sarana-inventaris.index',
            compact(
                'masterSaranaInventaris',
                'search'
            )
        );
    }

    public function show(
        MasterSaranaInventaris $masterSaranaInventaris
    ) {
        /*
    |--------------------------------------------------------------------------
    | RINGKASAN BERDASARKAN PENANGGUNG JAWAB
    |--------------------------------------------------------------------------
    */

        $summary = $masterSaranaInventaris
            ->inventories()
            ->with('responsible')
            ->get()
            ->groupBy('responsible_id')
            ->map(function ($items) {

                $responsible = $items
                    ->first()
                    ->responsible;

                return [
                    'nama' => $responsible
                        ? $responsible->nama_penanggung_jawab
                        : '-',

                    'jumlah' => $items->count(),
                ];
            })
            ->values();


        /*
    |--------------------------------------------------------------------------
    | DAFTAR INVENTARIS
    |--------------------------------------------------------------------------
    */

        $inventories = $masterSaranaInventaris
            ->inventories()
            ->with([
                'responsible',
                'location',
            ])
            ->orderBy('nomor_inventaris')
            ->paginate(10);


        return view(
            'master-sarana-inventaris.show',
            compact(
                'masterSaranaInventaris',
                'summary',
                'inventories'
            )
        );
    }

    public function create()
    {
        return view('master-sarana-inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sarana_inventaris' => 'required|string|max:255|unique:master_sarana_inventaris,nama_sarana_inventaris',
        ]);

        MasterSaranaInventaris::create($request->all());

        return redirect()
            ->route('master-sarana-inventaris.index')
            ->with('success', 'Master Sarana Inventaris berhasil ditambahkan.');
    }

    public function edit(MasterSaranaInventaris $masterSaranaInventaris)
    {
        return view('master-sarana-inventaris.edit', compact('masterSaranaInventaris'));
    }

    public function update(Request $request, MasterSaranaInventaris $masterSaranaInventaris)
    {
        $request->validate([
            'nama_sarana_inventaris' => 'required|string|max:255|unique:master_sarana_inventaris,nama_sarana_inventaris,' . $masterSaranaInventaris->id,
        ]);

        $masterSaranaInventaris->update($request->all());

        return redirect()
            ->route('master-sarana-inventaris.index')
            ->with('success', 'Master Sarana Inventaris berhasil diperbarui.');
    }

    public function destroy(MasterSaranaInventaris $masterSaranaInventaris)
    {
        $masterSaranaInventaris->delete();

        return redirect()
            ->route('master-sarana-inventaris.index')
            ->with('success', 'Master Sarana Inventaris berhasil dihapus.');
    }

    public function updateHeader(Request $request)
    {
        $request->validate([
            'header' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $disk = Storage::disk('public');

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $oldFile = 'images/daftar-sarana-inventaris.' . $extension;

            if ($disk->exists($oldFile)) {
                $disk->delete($oldFile);
            }
        }

        $extension = $request->file('header')->getClientOriginalExtension();

        $request->file('header')->storeAs(
            'images',
            'daftar-sarana-inventaris.' . $extension,
            'public'
        );

        return back()->with(
            'success',
            'Gambar header berhasil diperbarui.'
        );
    }
}

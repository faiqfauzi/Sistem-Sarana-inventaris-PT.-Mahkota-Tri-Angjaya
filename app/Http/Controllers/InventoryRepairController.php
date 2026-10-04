<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryRepairController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | CEK AKSES INVENTARIS
    |----------------------------------------------------------------------
    */

    private function authorizeInventory(Inventory $inventory)
    {
        $user = auth()->user();

        if (!$user) {

            abort(
                403,
                'Silakan login terlebih dahulu.'
            );
        }


        /*
        | Admin bebas akses
        */

        if ($user->role === 'admin') {

            return;
        }


        /*
        | Responsible hanya miliknya
        */

        if (
            !$user->responsible_id ||
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses ke riwayat inventaris ini.'
            );
        }
    }

    private function authorizeManageInventory(Inventory $inventory)
    {
        $user = auth()->user();

        if (!$user) {

            abort(
                403,
                'Silakan login terlebih dahulu.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

        if ($user->role === 'admin') {

            return;
        }


        /*
    |--------------------------------------------------------------------------
    | RESPONSIBLE HANYA BOLEH MENGELOLA INVENTARIS MILIKNYA
    |--------------------------------------------------------------------------
    */

        if (
            !$user->responsible_id ||
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses untuk mengelola riwayat perbaikan inventaris ini.'
            );
        }
    }


    /*
    |----------------------------------------------------------------------
    | CREATE
    |----------------------------------------------------------------------
    */

    public function create(Inventory $inventory)
    {
        $this->authorizeManageInventory($inventory);

        return view(
            'repairs.create',
            compact('inventory')
        );
    }


    /*
    |----------------------------------------------------------------------
    | STORE
    |----------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Inventory $inventory
    ) {

        $this->authorizeManageInventory($inventory);


        $request->validate([

            'tanggal_pengajuan' =>
            'required|date',

            'tanggal_tindak_lanjut' =>
            'nullable|date',

            'dokumen' =>
            'nullable|file|max:10240',

            'jenis_perbaikan' =>
            'required|string|max:255',

            'pelaksana' =>
            'required|string|max:255',

        ]);


        /*
        | Upload dokumen jika ada
        */

        $dokumenPath = null;

        if ($request->hasFile('dokumen')) {

            $dokumenPath = $request
                ->file('dokumen')
                ->store(
                    'repair-documents',
                    'public'
                );
        }


        /*
        | Simpan data perbaikan
        */

        $inventory->repairs()->create([

            'tanggal_pengajuan' =>
            $request->tanggal_pengajuan,

            'tanggal_tindak_lanjut' =>
            $request->tanggal_tindak_lanjut,

            'dokumen' =>
            $dokumenPath,

            'jenis_perbaikan' =>
            $request->jenis_perbaikan,

            'pelaksana' =>
            $request->pelaksana,

        ]);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perbaikan berhasil ditambahkan.'
            );
    }


    /*
    |----------------------------------------------------------------------
    | EDIT
    |----------------------------------------------------------------------
    */

    public function edit(
        InventoryRepair $repair
    ) {

        $inventory = $repair->inventory;

        $this->authorizeManageInventory($inventory);

        return view(
            'repairs.edit',
            compact('repair')
        );
    }


    /*
    |----------------------------------------------------------------------
    | UPDATE
    |----------------------------------------------------------------------
    */

    public function update(
        Request $request,
        InventoryRepair $repair
    ) {

        $inventory = $repair->inventory;

        $this->authorizeManageInventory($inventory);


        $request->validate([

            'tanggal_pengajuan' =>
            'required|date',

            'tanggal_tindak_lanjut' =>
            'nullable|date',

            'dokumen' =>
            'nullable|file|max:10240',

            'jenis_perbaikan' =>
            'required|string|max:255',

            'pelaksana' =>
            'required|string|max:255',

        ]);


        /*
        | Data dasar
        */

        $data = [

            'tanggal_pengajuan' =>
            $request->tanggal_pengajuan,

            'tanggal_tindak_lanjut' =>
            $request->tanggal_tindak_lanjut,

            'jenis_perbaikan' =>
            $request->jenis_perbaikan,

            'pelaksana' =>
            $request->pelaksana,

        ];


        /*
        | Jika upload dokumen baru
        */

        if ($request->hasFile('dokumen')) {

            /*
            | Hapus dokumen lama
            */

            if ($repair->dokumen) {

                Storage::disk('public')
                    ->delete($repair->dokumen);
            }


            /*
            | Upload dokumen baru
            */

            $data['dokumen'] = $request
                ->file('dokumen')
                ->store(
                    'repair-documents',
                    'public'
                );
        }


        /*
        | Update
        */

        $repair->update($data);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perbaikan berhasil diperbarui.'
            );
    }


    /*
    |----------------------------------------------------------------------
    | DESTROY
    |----------------------------------------------------------------------
    */

    public function destroy(
        InventoryRepair $repair
    ) {

        /*
        | Hanya admin
        */

        if (
            !auth()->check() ||
            auth()->user()->role !== 'admin'
        ) {

            abort(
                403,
                'Hanya Administrator yang dapat menghapus data perbaikan.'
            );
        }


        $inventory = $repair->inventory;


        /*
        | Hapus dokumen jika ada
        */

        if ($repair->dokumen) {

            Storage::disk('public')
                ->delete($repair->dokumen);
        }


        /*
        | Hapus data
        */

        $repair->delete();


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perbaikan berhasil dihapus.'
            );
    }
}

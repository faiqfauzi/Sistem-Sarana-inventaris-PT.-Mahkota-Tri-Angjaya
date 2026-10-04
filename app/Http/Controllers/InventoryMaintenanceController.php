<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMaintenance;
use Illuminate\Http\Request;

class InventoryMaintenanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES INVENTARIS
    |--------------------------------------------------------------------------
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
        | Responsible hanya boleh akses inventaris miliknya
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
    | RESPONSIBLE HANYA INVENTARIS MILIKNYA
    |--------------------------------------------------------------------------
    */

        if (
            !$user->responsible_id ||
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses untuk mengelola riwayat perawatan inventaris ini.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Inventory $inventory)
    {
        $this->authorizeManageInventory($inventory);


        return view(
            'maintenances.create',
            compact('inventory')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Inventory $inventory
    ) {

        $this->authorizeManageInventory($inventory);


        $request->validate([

            'tanggal_perawatan' => 'required|date',

            'tanggal_perawatan_selanjutnya' =>
            'nullable|date',

            'jenis_perawatan' =>
            'required|string|max:255',

            'pelaksana' =>
            'required|string|max:255',

        ]);


        $inventory->maintenances()->create([
            'tanggal_perawatan' =>
            $request->tanggal_perawatan,

            'tanggal_perawatan_selanjutnya' =>
            $request->tanggal_perawatan_selanjutnya,

            'jenis_perawatan' =>
            $request->jenis_perawatan,

            'pelaksana' =>
            $request->pelaksana,

        ]);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perawatan berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        InventoryMaintenance $maintenance
    ) {

        $inventory = $maintenance->inventory;


        $this->authorizeManageInventory($inventory);


        return view(
            'maintenances.edit',
            compact('maintenance')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        InventoryMaintenance $maintenance
    ) {

        $inventory = $maintenance->inventory;


        $this->authorizeManageInventory($inventory);


        $request->validate([

            'tanggal_perawatan' => 'required|date',

            'tanggal_perawatan_selanjutnya' =>
            'nullable|date',

            'jenis_perawatan' =>
            'required|string|max:255',

            'pelaksana' =>
            'required|string|max:255',

        ]);


        $maintenance->update([

            'tanggal_perawatan' =>
            $request->tanggal_perawatan,

            'tanggal_perawatan_selanjutnya' =>
            $request->tanggal_perawatan_selanjutnya,

            'jenis_perawatan' =>
            $request->jenis_perawatan,

            'pelaksana' =>
            $request->pelaksana,

        ]);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perawatan berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        InventoryMaintenance $maintenance
    ) {

        /*
        | Hanya admin yang boleh menghapus
        */

        if (
            !auth()->check() ||
            auth()->user()->role !== 'admin'
        ) {

            abort(
                403,
                'Hanya Administrator yang dapat menghapus data perawatan.'
            );
        }


        $inventory = $maintenance->inventory;


        $maintenance->delete();


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data perawatan berhasil dihapus.'
            );
    }
}

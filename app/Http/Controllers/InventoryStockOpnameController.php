<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryStockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryStockOpnameController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES INVENTARIS
    |--------------------------------------------------------------------------
    */

    private function authorizeInventory(Inventory $inventory)
    {
        $user = auth()->user();


        /*
        | Harus login
        */

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
        | Responsible hanya boleh akses
        | inventaris miliknya sendiri
        */

        if (
            !$user->responsible_id ||
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses ke inventaris ini.'
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
        $this->authorizeInventory($inventory);


        return view(
            'stock-opnames.create',
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

        /*
        | Cek akses inventaris
        */

        $this->authorizeInventory($inventory);


        /*
        | Validasi
        */

        $request->validate([

            'tanggal' =>
            'required|date',

            'kondisi' =>
            'required|string|max:255',

            'auditor' =>
            'required|string|max:255',

            'foto' =>
            'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);


        /*
        | Data utama
        */

        $data = $request->except([
            'foto',
        ]);


        /*
        | Upload foto
        */

        if ($request->hasFile('foto')) {

            $data['foto'] =
                $request
                ->file('foto')
                ->store(
                    'stock-opnames',
                    'public'
                );
        }


        /*
        | Simpan
        */

        $inventory->stockOpnames()->create($data);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Stok opname berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        InventoryStockOpname $stockOpname
    ) {

        $inventory = $stockOpname->inventory;


        /*
        | Cek akses inventaris
        */

        $this->authorizeInventory($inventory);


        return view(
            'stock-opnames.edit',
            compact('stockOpname')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        InventoryStockOpname $stockOpname
    ) {

        $inventory = $stockOpname->inventory;


        /*
        | Cek akses inventaris
        */

        $this->authorizeInventory($inventory);


        /*
        | Validasi
        */

        $request->validate([

            'tanggal' =>
            'required|date',

            'kondisi' =>
            'required|string|max:255',

            'auditor' =>
            'required|string|max:255',

            'foto' =>
            'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);


        /*
        | Data utama
        */

        $data = $request->except([
            'foto',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FOTO BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            /*
            | Hapus foto lama
            */

            if ($stockOpname->foto) {

                Storage::disk('public')
                    ->delete($stockOpname->foto);
            }


            /*
            | Simpan foto baru
            */

            $data['foto'] =
                $request
                ->file('foto')
                ->store(
                    'stock-opnames',
                    'public'
                );
        }



        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $stockOpname->update($data);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Stok opname berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        InventoryStockOpname $stockOpname
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
                'Hanya Administrator yang dapat menghapus Stok Opname.'
            );
        }


        $inventory = $stockOpname->inventory;


        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO
        |--------------------------------------------------------------------------
        */

        if ($stockOpname->foto) {

            Storage::disk('public')
                ->delete($stockOpname->foto);
        }



        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */

        $stockOpname->delete();


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Stok opname berhasil dihapus.'
            );
    }
}

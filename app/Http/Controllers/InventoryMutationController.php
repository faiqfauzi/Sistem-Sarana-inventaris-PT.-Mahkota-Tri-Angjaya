<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryMutationController extends Controller
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
        | Responsible hanya inventaris miliknya
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
                'Anda tidak memiliki akses untuk mengelola riwayat mutasi inventaris ini.'
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
            'mutations.create',
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

        $this->authorizeInventory($inventory);


        $request->validate([

            'jenis' =>
            'required|in:Pemindahan,Pemusnahan',

            'tanggal' =>
            'required|date',

            'alasan' =>
            'required|string',

            'dokumen' =>
            'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',

        ]);


        $data = [

            'jenis' =>
            $request->jenis,

            'tanggal' =>
            $request->tanggal,

            'alasan' =>
            $request->alasan,

        ];


        if ($request->hasFile('dokumen')) {

            $data['dokumen'] =
                $request
                ->file('dokumen')
                ->store(
                    'mutations',
                    'public'
                );
        }


        $inventory->mutations()->create($data);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data mutasi berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        InventoryMutation $mutation
    ) {

        $inventory = $mutation->inventory;


        $this->authorizeManageInventory($inventory);


        return view(
            'mutations.edit',
            compact('mutation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        InventoryMutation $mutation
    ) {

        $inventory = $mutation->inventory;


        $this->authorizeManageInventory($inventory);


        $request->validate([

            'jenis' =>
            'required|in:Pemindahan,Pemusnahan',

            'tanggal' =>
            'required|date',

            'alasan' =>
            'required|string',

            'dokumen' =>
            'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',

        ]);


        $data = [

            'jenis' =>
            $request->jenis,

            'tanggal' =>
            $request->tanggal,

            'alasan' =>
            $request->alasan,

        ];


        /*
        | Dokumen baru
        */

        if ($request->hasFile('dokumen')) {

            /*
            | Hapus dokumen lama
            */

            if ($mutation->dokumen) {

                Storage::disk('public')
                    ->delete($mutation->dokumen);
            }


            /*
            | Simpan dokumen baru
            */

            $data['dokumen'] =
                $request
                ->file('dokumen')
                ->store(
                    'mutations',
                    'public'
                );
        }


        $mutation->update($data);


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data mutasi berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        InventoryMutation $mutation
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
                'Hanya Administrator yang dapat menghapus data mutasi.'
            );
        }


        $inventory = $mutation->inventory;


        /*
        | Hapus dokumen
        */

        if ($mutation->dokumen) {

            Storage::disk('public')
                ->delete($mutation->dokumen);
        }


        $mutation->delete();


        return redirect()
            ->route('inventories.show', $inventory)
            ->with(
                'success',
                'Data mutasi berhasil dihapus.'
            );
    }
}

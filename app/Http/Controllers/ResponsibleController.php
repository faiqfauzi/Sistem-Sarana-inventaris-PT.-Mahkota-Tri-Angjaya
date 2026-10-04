<?php

namespace App\Http\Controllers;

use App\Models\Responsible;
use App\Models\InventoryStockOpname;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ResponsibleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK ADMIN
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
                'Hanya Administrator yang dapat mengelola Penanggung Jawab.'
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

        $responsibles = Responsible::withCount('inventories')
            ->when($search, function ($query) use ($search) {

                $query->where(
                    'nama_penanggung_jawab',
                    'like',
                    "%{$search}%"
                );
            })
            ->orderBy('nama_penanggung_jawab')
            ->paginate(10)
            ->withQueryString();

        return view(
            'responsibles.index',
            compact(
                'responsibles',
                'search'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Responsible $responsible)
    {
        $inventories = $responsible->inventories()
            ->with([
                'masterSaranaInventaris',
                'location',
            ])
            ->orderBy('nomor_inventaris')
            ->paginate(10)
            ->withQueryString();


        $summary = $responsible->inventories()
            ->with('masterSaranaInventaris')
            ->get()
            ->groupBy('master_sarana_inventaris_id')
            ->map(function ($inventories) {

                return [

                    'nama' => $inventories->first()
                        ->masterSaranaInventaris
                        ->nama_sarana_inventaris,

                    'jumlah' => $inventories->count(),

                ];
            });


        return view(
            'responsibles.show',
            compact(
                'responsible',
                'inventories',
                'summary'
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

        return view('responsibles.create');
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
            'nama_penanggung_jawab' =>
            'required|string|max:255|unique:responsibles,nama_penanggung_jawab',
        ]);

        Responsible::create([
            'nama_penanggung_jawab' =>
            $request->nama_penanggung_jawab,
        ]);

        return redirect()
            ->route('responsibles.index')
            ->with(
                'success',
                'Penanggung jawab berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Responsible $responsible)
    {
        $this->authorizeAdmin();

        return view(
            'responsibles.edit',
            compact('responsible')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Responsible $responsible
    ) {

        $this->authorizeAdmin();

        $request->validate([
            'nama_penanggung_jawab' =>
            'required|string|max:255|unique:responsibles,nama_penanggung_jawab,' .
                $responsible->id,
        ]);

        $responsible->update([
            'nama_penanggung_jawab' =>
            $request->nama_penanggung_jawab,
        ]);

        return redirect()
            ->route('responsibles.index')
            ->with(
                'success',
                'Penanggung jawab berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Responsible $responsible)
    {
        $this->authorizeAdmin();

        $responsible->delete();

        return redirect()
            ->route('responsibles.index')
            ->with(
                'success',
                'Penanggung jawab berhasil dihapus.'
            );
    }

    /*
|--------------------------------------------------------------------------
| DOCUMENT STOCK OPNAME
|--------------------------------------------------------------------------
*/

    public function documentStockOpname(
        Responsible $responsible
    ) {

        /*
    |--------------------------------------------------------------------------
    | HANYA ADMIN
    |--------------------------------------------------------------------------
    */

        $this->authorizeAdmin();


        /*
    |--------------------------------------------------------------------------
    | AMBIL SELURUH STOCK OPNAME
    |--------------------------------------------------------------------------
    */

        $stockOpnames = InventoryStockOpname::whereHas(
            'inventory',
            function ($query) use ($responsible) {

                $query->where(
                    'responsible_id',
                    $responsible->id
                );
            }
        )
            ->with([
                'inventory.masterSaranaInventaris',
                'inventory.location',
            ])
            ->latest('tanggal')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | GENERATE PDF
    |--------------------------------------------------------------------------
    */

        $pdf = Pdf::loadView(
            'responsibles.document-stock-opname',
            compact(
                'responsible',
                'stockOpnames'
            )
        );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'Stock-Opname-' .
                $responsible->nama_penanggung_jawab .
                '.pdf'
        );
    }

    public function uploadStockOpname(
        Request $request,
        Responsible $responsible
    ) {
        $user = auth()->user();

        if (
            !$user ||
            (
                $user->role !== 'admin' &&
                $user->responsible_id !== $responsible->id
            )
        ) {
            abort(
                403,
                'Anda tidak memiliki akses untuk mengunggah dokumen Stock Opname ini.'
            );
        }

        $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $disk = Storage::disk('public');

        /*
    |--------------------------------------------------------------------------
    | HAPUS DOKUMEN LAMA
    |--------------------------------------------------------------------------
    */

        if (
            $responsible->stock_opname_document &&
            $disk->exists($responsible->stock_opname_document)
        ) {
            $disk->delete(
                $responsible->stock_opname_document
            );
        }

        /*
    |--------------------------------------------------------------------------
    | SIMPAN DOKUMEN BARU
    |--------------------------------------------------------------------------
    */

        $extension = $request
            ->file('document')
            ->getClientOriginalExtension();

        $filename =
            'responsible-' .
            $responsible->id .
            '.' .
            $extension;

        $path = $request
            ->file('document')
            ->storeAs(
                'stock-opnames',
                $filename,
                'public'
            );

        /*
    |--------------------------------------------------------------------------
    | UPDATE DATA RESPONSIBLE
    |--------------------------------------------------------------------------
    */

        $responsible->update([
            'stock_opname_document' => $path,
            'stock_opname_uploaded_at' => now(),
        ]);

        return back()->with(
            'success',
            'Dokumen Stock Opname berhasil diunggah dan dokumen lama telah diganti.'
        );
    }


    public function viewStockOpname(
        Responsible $responsible
    ) {
        $user = auth()->user();

        if (
            !$user ||
            (
                $user->role !== 'admin' &&
                $user->responsible_id !== $responsible->id
            )
        ) {
            abort(
                403,
                'Anda tidak memiliki akses untuk melihat dokumen Stock Opname ini.'
            );
        }

        if (
            !$responsible->stock_opname_document ||
            !Storage::disk('public')->exists(
                $responsible->stock_opname_document
            )
        ) {
            return back()->with(
                'error',
                'Dokumen Stock Opname belum tersedia.'
            );
        }

        return Storage::disk('public')->response(
            $responsible->stock_opname_document
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MasterSaranaInventaris;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Responsible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $sort = $request->get('sort', 'nomor_inventaris');
        $direction = $request->get('direction', 'asc');

        $query = Inventory::with([
            'masterSaranaInventaris',
            'location',
            'responsible',
            'latestStockOpname'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('nomor_inventaris', 'like', "%{$search}%")
                    ->orWhere('user', 'like', "%{$search}%")
                    ->orWhere('spesifikasi', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")

                    ->orWhereHas('masterSaranaInventaris', function ($q) use ($search) {
                        $q->where('nama_sarana_inventaris', 'like', "%{$search}%");
                    })

                    ->orWhereHas('location', function ($q) use ($search) {
                        $q->where('nama_lokasi', 'like', "%{$search}%");
                    })

                    ->orWhereHas('responsible', function ($q) use ($search) {
                        $q->where('nama_penanggung_jawab', 'like', "%{$search}%");
                    });
            });
        }

        if ($sort != 'stock_opname') {

            $query->orderBy($sort, $direction);
        } else {

            $query->withMax('stockOpnames', 'tanggal')
                ->orderBy('stock_opnames_max_tanggal', $direction);
        }

        $inventories = $query
            ->paginate(10)
            ->withQueryString();

        return view('inventories.index', compact(
            'inventories',
            'search'
        ));
    }

    public function create()
    {
        $masterSaranaInventaris =
            MasterSaranaInventaris::orderBy(
                'nama_sarana_inventaris'
            )->get();

        $locations =
            Location::orderBy('nama_lokasi')->get();

        $responsibles =
            Responsible::orderBy(
                'nama_penanggung_jawab'
            )->get();


        $user = Auth::user();


        return view('inventories.create', compact(
            'masterSaranaInventaris',
            'locations',
            'responsibles',
            'user'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();


        /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

        $request->validate([
            'nomor_inventaris' => 'required|unique:inventories',
            'master_sarana_inventaris_id' => 'required|exists:master_sarana_inventaris,id',
            'location_id' => 'required|exists:locations,id',

            'responsible_id' => 'nullable|exists:responsibles,id',

            'tanggal_perolehan' => 'required|date',
            'spesifikasi' => 'nullable',
            'user' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        /*
    |--------------------------------------------------------------------------
    | TENTUKAN PENANGGUNG JAWAB
    |--------------------------------------------------------------------------
    */

        if ($user->role === 'admin') {

            /*
        | Admin bebas memilih Penanggung Jawab
        */

            if (!$request->filled('responsible_id')) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'responsible_id' =>
                        'Penanggung Jawab wajib dipilih.'
                    ]);
            }

            $responsibleId = $request->responsible_id;
        } else {

            /*
        | User Penanggung Jawab:
        | otomatis memakai responsible_id dari akun login
        */

            if (!$user->responsible_id) {

                abort(
                    403,
                    'Akun ini belum terhubung dengan Penanggung Jawab.'
                );
            }

            $responsibleId = $user->responsible_id;
        }


        /*
    |--------------------------------------------------------------------------
    | DATA INVENTARIS
    |--------------------------------------------------------------------------
    */

        $data = $request->only([
            'nomor_inventaris',
            'master_sarana_inventaris_id',
            'location_id',
            'user',
            'status',
            'tanggal_perolehan',
            'spesifikasi',
        ]);


        /*
    |--------------------------------------------------------------------------
    | PAKSA RESPONSIBLE ID
    |--------------------------------------------------------------------------
    */

        $data['responsible_id'] = $responsibleId;


        /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('foto')) {

            $data['foto'] = $request
                ->file('foto')
                ->store('inventories', 'public');
        }


        /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

        Inventory::create($data);


        return redirect()
            ->route('inventories.index')
            ->with(
                'success',
                'Sarana Inventaris berhasil ditambahkan.'
            );
    }

    public function show(Inventory $inventory)
    {
        $inventory->load([
            'masterSaranaInventaris',
            'location',
            'responsible',
        ]);

        $maintenances = $inventory->maintenances()
            ->latest('tanggal_perawatan')
            ->take(3)
            ->get();

        $repairs = $inventory->repairs()
            ->latest('tanggal_pengajuan')
            ->take(3)
            ->get();

        $stockOpnames = $inventory->stockOpnames()
            ->latest('tanggal')
            ->take(3)
            ->get();

        $mutations = $inventory->mutations()
            ->latest('tanggal')
            ->take(3)
            ->get();

        return view('inventories.show', compact(
            'inventory',
            'maintenances',
            'repairs',
            'stockOpnames',
            'mutations'
        ));
    }

    public function maintenances(
        Request $request,
        Inventory $inventory
    ) {
        $inventory->load([
            'masterSaranaInventaris',
        ]);

        $query = $inventory->maintenances()
            ->latest('tanggal_perawatan');


        /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

        if ($request->filled('bulan')) {

            $bulan = $request->bulan;

            $query->whereYear(
                'tanggal_perawatan',
                substr($bulan, 0, 4)
            )->whereMonth(
                'tanggal_perawatan',
                substr($bulan, 5, 2)
            );
        }


        $maintenances = $query
            ->paginate(10)
            ->withQueryString();


        return view(
            'inventories.maintenances',
            compact(
                'inventory',
                'maintenances'
            )
        );
    }

    public function repairs(
        Request $request,
        Inventory $inventory
    ) {
        $inventory->load([
            'masterSaranaInventaris',
        ]);


        /*
    |--------------------------------------------------------------------------
    | HAK AKSES
    |--------------------------------------------------------------------------
    */

        $user = auth()->user();

        $isAdmin = $user && $user->role === 'admin';

        $canManageHistory = $isAdmin ||
            (
                $user &&
                $user->responsible_id &&
                $inventory->responsible_id === $user->responsible_id
            );


        /*
    |--------------------------------------------------------------------------
    | QUERY DATA PERBAIKAN
    |--------------------------------------------------------------------------
    */

        $query = $inventory->repairs();


        /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

        if ($request->filled('bulan')) {

            $bulan = $request->bulan;

            $query->whereYear(
                'tanggal_pengajuan',
                substr($bulan, 0, 4)
            )->whereMonth(
                'tanggal_pengajuan',
                substr($bulan, 5, 2)
            );
        }


        /*
    |--------------------------------------------------------------------------
    | DATA PERBAIKAN
    |--------------------------------------------------------------------------
    */

        $repairs = $query
            ->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();


        return view(
            'inventories.repairs',
            compact(
                'inventory',
                'repairs',
                'isAdmin',
                'canManageHistory'
            )
        );
    }

    public function stockOpnames(
        Request $request,
        Inventory $inventory
    ) {
        $inventory->load([
            'masterSaranaInventaris',
        ]);


        /*
    |--------------------------------------------------------------------------
    | HAK AKSES
    |--------------------------------------------------------------------------
    */

        $user = auth()->user();

        $isAdmin = $user && $user->role === 'admin';

        $canManageHistory = $isAdmin ||
            (
                $user &&
                $user->responsible_id &&
                $inventory->responsible_id === $user->responsible_id
            );


        /*
    |--------------------------------------------------------------------------
    | QUERY STOCK OPNAME
    |--------------------------------------------------------------------------
    */

        $query = $inventory->stockOpnames();


        /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

        if ($request->filled('bulan')) {

            $bulan = $request->bulan;

            $query->whereYear(
                'tanggal',
                substr($bulan, 0, 4)
            )->whereMonth(
                'tanggal',
                substr($bulan, 5, 2)
            );
        }


        /*
    |--------------------------------------------------------------------------
    | DATA STOCK OPNAME
    |--------------------------------------------------------------------------
    */

        $stockOpnames = $query
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();


        return view(
            'inventories.stock-opnames',
            compact(
                'inventory',
                'stockOpnames',
                'isAdmin',
                'canManageHistory'
            )
        );
    }

    public function mutations(Inventory $inventory)
    {
        $inventory->load([
            'masterSaranaInventaris',
        ]);


        /*
    |--------------------------------------------------------------------------
    | HAK AKSES
    |--------------------------------------------------------------------------
    */

        $user = auth()->user();

        $isAdmin = $user && $user->role === 'admin';

        $canManageHistory = $isAdmin ||
            (
                $user &&
                $user->responsible_id &&
                $inventory->responsible_id === $user->responsible_id
            );


        /*
    |--------------------------------------------------------------------------
    | DATA MUTASI
    |--------------------------------------------------------------------------
    */

        $mutations = $inventory->mutations()
            ->latest('tanggal')
            ->paginate(10);


        return view(
            'inventories.mutations',
            compact(
                'inventory',
                'mutations',
                'isAdmin',
                'canManageHistory'
            )
        );
    }

    public function edit(Inventory $inventory)
    {
        $user = Auth::user();


        /*
    |--------------------------------------------------------------------------
    | RESPONSIBLE HANYA BOLEH EDIT INVENTARIS MILIKNYA
    |--------------------------------------------------------------------------
    */

        if (
            $user->role !== 'admin' &&
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah inventaris ini.'
            );
        }


        $masterSaranaInventaris =
            MasterSaranaInventaris::orderBy(
                'nama_sarana_inventaris'
            )->get();


        $locations =
            Location::orderBy(
                'nama_lokasi'
            )->get();


        $responsibles =
            Responsible::orderBy(
                'nama_penanggung_jawab'
            )->get();


        return view('inventories.edit', compact(
            'inventory',
            'masterSaranaInventaris',
            'locations',
            'responsibles',
            'user'
        ));
    }

    public function update(
        Request $request,
        Inventory $inventory
    ) {

        $user = Auth::user();


        /*
    |--------------------------------------------------------------------------
    | CEK AKSES RESPONSIBLE
    |--------------------------------------------------------------------------
    */

        if (
            $user->role !== 'admin' &&
            $inventory->responsible_id !== $user->responsible_id
        ) {

            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah inventaris ini.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | VALIDASI DATA UTAMA
    |--------------------------------------------------------------------------
    */

        $request->validate([

            'nomor_inventaris' =>
            'required|unique:inventories,nomor_inventaris,' .
                $inventory->id,

            'master_sarana_inventaris_id' =>
            'required|exists:master_sarana_inventaris,id',

            'location_id' =>
            'required|exists:locations,id',

            'tanggal_perolehan' =>
            'required|date',

            'spesifikasi' =>
            'nullable',

            'user' =>
            'nullable|string|max:255',

            'status' =>
            'nullable|string|max:255',

            'foto' =>
            'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);


        /*
    |--------------------------------------------------------------------------
    | RESPONSIBLE
    |--------------------------------------------------------------------------
    */

        if ($user->role === 'admin') {

            $request->validate([

                'responsible_id' =>
                'required|exists:responsibles,id',

            ]);


            $responsibleId =
                $request->responsible_id;
        } else {

            /*
        | Responsible tidak boleh diubah
        */

            $responsibleId =
                $user->responsible_id;
        }


        /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

        $data = $request->only([

            'nomor_inventaris',

            'master_sarana_inventaris_id',

            'location_id',

            'user',

            'status',

            'tanggal_perolehan',

            'spesifikasi',

        ]);


        /*
    |--------------------------------------------------------------------------
    | SET RESPONSIBLE
    |--------------------------------------------------------------------------
    */

        $data['responsible_id'] =
            $responsibleId;


        /*
    |--------------------------------------------------------------------------
    | FOTO BARU
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('foto')) {

            if ($inventory->foto) {

                Storage::disk('public')
                    ->delete($inventory->foto);
            }


            $data['foto'] =
                $request
                ->file('foto')
                ->store(
                    'inventories',
                    'public'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

        $inventory->update($data);


        return redirect()
            ->route('inventories.index')
            ->with(
                'success',
                'Sarana Inventaris berhasil diperbarui.'
            );
    }

    public function destroy(Inventory $inventory)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {

            abort(
                403,
                'Hanya Administrator yang dapat menghapus Sarana Inventaris.'
            );
        }


        if ($inventory->foto) {

            Storage::disk('public')->delete(
                $inventory->foto
            );
        }


        $inventory->delete();


        return redirect()
            ->route('inventories.index')
            ->with(
                'success',
                'Sarana Inventaris berhasil dihapus.'
            );
    }

    public function bulkDestroy(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {

            abort(
                403,
                'Hanya Administrator yang dapat menghapus Sarana Inventaris.'
            );
        }


        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:inventories,id',
        ]);


        $inventories = Inventory::whereIn(
            'id',
            $request->ids
        )->get();


        foreach ($inventories as $inventory) {

            if ($inventory->foto) {

                Storage::disk('public')
                    ->delete($inventory->foto);
            }


            $inventory->delete();
        }


        return redirect()
            ->route('inventories.index')
            ->with(
                'success',
                count($inventories) .
                    ' data inventaris berhasil dihapus.'
            );
    }

    public function qr(Inventory $inventory)
    {
        return view('inventories.qr', compact('inventory'));
    }

    public function printQr()
    {
        $inventories = Inventory::orderBy('nomor_inventaris')->get();

        return view('inventories.print-qr', compact('inventories'));
    }

    public function document(Inventory $inventory)
    {
        $inventory->load([
            'masterSaranaInventaris',
            'location',
            'responsible',
        ]);

        $maintenances = $inventory->maintenances()
            ->latest('tanggal_perawatan')
            ->take(2)
            ->get();

        $repairs = $inventory->repairs()
            ->latest('tanggal_pengajuan')
            ->take(2)
            ->get();

        $stockOpnames = $inventory->stockOpnames()
            ->latest('tanggal')
            ->take(1)
            ->get();

        $mutations = $inventory->mutations()
            ->latest('tanggal')
            ->get();

        $pdf = Pdf::loadView(
            'inventories.document',
            compact(
                'inventory',
                'maintenances',
                'repairs',
                'stockOpnames',
                'mutations'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Inventaris-' .
            preg_replace(
                '/[\/\\\\:*?"<>|]/',
                '-',
                $inventory->nomor_inventaris
            ) .
            '.pdf';


        return $pdf->stream($filename);
    }
}

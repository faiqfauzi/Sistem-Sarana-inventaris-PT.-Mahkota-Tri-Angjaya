<?php

namespace App\Http\Controllers;

use App\Models\MasterSaranaInventaris;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Responsible;
use App\Models\InventoryMaintenance;
use App\Models\InventoryRepair;
use App\Models\InventoryStockOpname;
use App\Models\InventoryMutation;
use Carbon\Carbon;
// use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInventories = Inventory::count();
        $totalMasterSaranaInventaris = MasterSaranaInventaris::count();
        $totalLocations = Location::count();
        $totalResponsibles = Responsible::count();

        $activities = collect();

        // Maintenance
        InventoryMaintenance::with('inventory')
            ->latest('tanggal_perawatan')
            ->get()
            ->each(function ($item) use ($activities) {
                $activities->push([
                    'tanggal' => $item->tanggal_perawatan,
                    'jenis' => 'Perawatan',
                ]);
            });

        // Repair
        InventoryRepair::with('inventory')
            ->latest('tanggal_pengajuan')
            ->get()
            ->each(function ($item) use ($activities) {
                $activities->push([
                    'tanggal' => $item->tanggal_pengajuan,
                    'jenis' => 'Perbaikan',
                ]);
            });

        // Stok Opname
        InventoryStockOpname::with('inventory')
            ->latest('tanggal')
            ->get()
            ->each(function ($item) use ($activities) {
                $activities->push([
                    'tanggal' => $item->tanggal,
                    'jenis' => 'Stock Opname',
                ]);
            });

        // Mutation
        InventoryMutation::with('inventory')
            ->latest('tanggal')
            ->get()
            ->each(function ($item) use ($activities) {
                $activities->push([
                    'tanggal' => $item->tanggal,
                    'jenis' => $item->jenis,
                ]);
            });

        $activities = $activities
            ->sortByDesc('tanggal')
            ->take(5);

        $upcomingMaintenances = InventoryMaintenance::with('inventory')
            ->whereNotNull('tanggal_perawatan_selanjutnya')
            ->whereDate(
                'tanggal_perawatan_selanjutnya',
                '<=',
                Carbon::today()->addDays(30)
            )
            ->orderBy('tanggal_perawatan_selanjutnya')
            ->get();

        return view('dashboard.index', compact(
            'totalInventories',
            'totalMasterSaranaInventaris',
            'totalLocations',
            'totalResponsibles',
            'activities',
            'upcomingMaintenances'
        ));
    }
}

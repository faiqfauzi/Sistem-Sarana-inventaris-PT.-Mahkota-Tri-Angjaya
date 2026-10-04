<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\MasterSaranaInventarisController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ResponsibleController;
use App\Http\Controllers\InventoryController;

use App\Http\Controllers\InventoryMaintenanceController;
use App\Http\Controllers\InventoryRepairController;
use App\Http\Controllers\InventoryStockOpnameController;
use App\Http\Controllers\InventoryMutationController;
use App\Http\Controllers\BackupController;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| Guest bisa masuk tanpa login.
|
*/

Route::get(
    '/',
    [DashboardController::class, 'index']
)->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get(
    '/masuk',
    [AuthController::class, 'showLogin']
)->middleware('guest')
    ->name('login');


Route::post(
    '/masuk',
    [AuthController::class, 'login']
)->middleware('guest')
    ->name('login.process');


Route::post(
    '/keluar',
    [AuthController::class, 'logout']
)->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Akun
|--------------------------------------------------------------------------
|
| Hanya Admin.
|
*/

Route::resource('akun', UserController::class)
    ->middleware([
        'auth',
        'admin',
    ])
    ->parameters([
        'akun' => 'user',
    ])
    ->names('users');

/*
|--------------------------------------------------------------------------
| Backup & Restore Database
|--------------------------------------------------------------------------
|
| Hanya Admin.
|
*/

Route::middleware([
    'auth',
    'admin',
])->group(function () {

    Route::get(
        '/backup-restore',
        [BackupController::class, 'index']
    )->name('backups.index');


    Route::get(
        '/backup-restore/download',
        [BackupController::class, 'download']
    )->name('backups.download');


    Route::post(
        '/backup-restore/restore',
        [BackupController::class, 'restore']
    )->name('backups.restore');

    Route::post(
        '/master-sarana-inventaris/header',
        [MasterSaranaInventarisController::class, 'updateHeader']
    )->name('master-sarana-inventaris.update-header');
});


/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
|
| Guest boleh melihat.
| Export hanya user login.
|
*/

Route::get(
    '/laporan',
    [ReportController::class, 'index']
)->name('reports.index');


Route::middleware('auth')->group(function () {

    Route::post(
        '/reports/export',
        [ReportController::class, 'export']
    )->name('reports.export');
});


/*
|--------------------------------------------------------------------------
| SARANA INVENTARIS
|--------------------------------------------------------------------------
|
| PENTING:
|
| Semua route statis harus berada SEBELUM:
| /inventaris/{inventory}
|
*/


/*
|--------------------------------------------------------------------------
| Inventaris - READ
|--------------------------------------------------------------------------
|
| Guest boleh melihat.
|
*/

Route::get(
    '/inventaris',
    [InventoryController::class, 'index']
)->name('inventories.index');


/*
|--------------------------------------------------------------------------
| Inventaris - QR
|--------------------------------------------------------------------------
*/

Route::get(
    '/inventaris/cetak/qr',
    [InventoryController::class, 'printQr']
)->name('inventories.print.qr');


/*
|--------------------------------------------------------------------------
| Inventaris - CREATE & UPDATE
|--------------------------------------------------------------------------
|
| Admin dan Responsible.
|
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/inventaris/tambah',
        [InventoryController::class, 'create']
    )->name('inventories.create');


    Route::post(
        '/inventaris',
        [InventoryController::class, 'store']
    )->name('inventories.store');


    Route::get(
        '/inventaris/{inventory}/ubah',
        [InventoryController::class, 'edit']
    )->name('inventories.edit');


    Route::put(
        '/inventaris/{inventory}',
        [InventoryController::class, 'update']
    )->name('inventories.update');
});


/*
|--------------------------------------------------------------------------
| Inventaris - BULK DELETE
|--------------------------------------------------------------------------
|
| Admin saja.
|
*/

Route::delete(
    '/inventaris/hapus-massal',
    [InventoryController::class, 'bulkDestroy']
)->middleware([
    'auth',
    'admin',
])->name('inventories.bulk-destroy');


/*
|--------------------------------------------------------------------------
| Inventaris - QR PER ITEM
|--------------------------------------------------------------------------
*/

Route::get(
    '/inventaris/{inventory}/qr',
    [InventoryController::class, 'qr']
)->name('inventories.qr');

Route::get(
    '/inventaris/{inventory}/document',
    [InventoryController::class, 'document']
)->name('inventories.document');


/*
|--------------------------------------------------------------------------
| Riwayat Inventaris - READ
|--------------------------------------------------------------------------
|
| Guest boleh melihat.
|
*/

Route::get(
    '/inventaris/{inventory}/perawatan',
    [InventoryController::class, 'maintenances']
)->name('inventories.maintenances');


Route::get(
    '/inventaris/{inventory}/perbaikan',
    [InventoryController::class, 'repairs']
)->name('inventories.repairs');


Route::get(
    '/inventaris/{inventory}/stock-opname',
    [InventoryController::class, 'stockOpnames']
)->name('inventories.stock-opnames');


Route::get(
    '/inventaris/{inventory}/mutasi',
    [InventoryController::class, 'mutations']
)->name('inventories.mutations');


/*
|--------------------------------------------------------------------------
| Inventaris - DETAIL
|--------------------------------------------------------------------------
|
| Guest boleh melihat.
|
| HARUS setelah route statis.
|
*/

Route::get(
    '/inventaris/{inventory}',
    [InventoryController::class, 'show']
)->name('inventories.show');


/*
|--------------------------------------------------------------------------
| Inventaris - DELETE
|--------------------------------------------------------------------------
|
| Admin saja.
|
*/

Route::delete(
    '/inventaris/{inventory}',
    [InventoryController::class, 'destroy']
)->middleware([
    'auth',
    'admin',
])->name('inventories.destroy');


/*
|--------------------------------------------------------------------------
| PERAWATAN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/inventaris/{inventory}/perawatan/tambah',
        [InventoryMaintenanceController::class, 'create']
    )->name('maintenances.create');


    Route::post(
        '/inventaris/{inventory}/perawatan',
        [InventoryMaintenanceController::class, 'store']
    )->name('maintenances.store');


    Route::get(
        '/perawatan/{maintenance}/ubah',
        [InventoryMaintenanceController::class, 'edit']
    )->name('maintenances.edit');


    Route::put(
        '/perawatan/{maintenance}',
        [InventoryMaintenanceController::class, 'update']
    )->name('maintenances.update');


    Route::delete(
        '/perawatan/{maintenance}',
        [InventoryMaintenanceController::class, 'destroy']
    )->name('maintenances.destroy');
});


/*
|--------------------------------------------------------------------------
| PERBAIKAN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/inventaris/{inventory}/perbaikan/tambah',
        [InventoryRepairController::class, 'create']
    )->name('repairs.create');


    Route::post(
        '/inventaris/{inventory}/perbaikan',
        [InventoryRepairController::class, 'store']
    )->name('repairs.store');


    Route::get(
        '/perbaikan/{repair}/ubah',
        [InventoryRepairController::class, 'edit']
    )->name('repairs.edit');


    Route::put(
        '/perbaikan/{repair}',
        [InventoryRepairController::class, 'update']
    )->name('repairs.update');


    Route::delete(
        '/perbaikan/{repair}',
        [InventoryRepairController::class, 'destroy']
    )->name('repairs.destroy');
});


/*
|--------------------------------------------------------------------------
| STOK OPNAME
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/inventaris/{inventory}/stock-opname/tambah',
        [InventoryStockOpnameController::class, 'create']
    )->name('stock-opnames.create');


    Route::post(
        '/inventaris/{inventory}/stock-opname',
        [InventoryStockOpnameController::class, 'store']
    )->name('stock-opnames.store');


    Route::get(
        '/stock-opname/{stockOpname}/ubah',
        [InventoryStockOpnameController::class, 'edit']
    )->name('stock-opnames.edit');


    Route::put(
        '/stock-opname/{stockOpname}',
        [InventoryStockOpnameController::class, 'update']
    )->name('stock-opnames.update');


    Route::delete(
        '/stock-opname/{stockOpname}',
        [InventoryStockOpnameController::class, 'destroy']
    )->name('stock-opnames.destroy');
});

Route::middleware('auth')->group(function () {

    Route::post(
        '/penanggung-jawab/{responsible}/dokumen-stock-opname',
        [ResponsibleController::class, 'uploadStockOpname']
    )->name('responsibles.upload-stock-opname');

    Route::get(
        '/penanggung-jawab/{responsible}/dokumen-stock-opname',
        [ResponsibleController::class, 'viewStockOpname']
    )->name('responsibles.view-stock-opname');
});


/*
|--------------------------------------------------------------------------
| MUTASI
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/inventaris/{inventory}/mutasi/tambah',
        [InventoryMutationController::class, 'create']
    )->name('mutations.create');


    Route::post(
        '/inventaris/{inventory}/mutasi',
        [InventoryMutationController::class, 'store']
    )->name('mutations.store');


    Route::get(
        '/mutasi/{mutation}/ubah',
        [InventoryMutationController::class, 'edit']
    )->name('mutations.edit');


    Route::put(
        '/mutasi/{mutation}',
        [InventoryMutationController::class, 'update']
    )->name('mutations.update');


    Route::delete(
        '/mutasi/{mutation}',
        [InventoryMutationController::class, 'destroy']
    )->name('mutations.destroy');
});


/*
|--------------------------------------------------------------------------
| MASTER SARANA INVENTARIS
|--------------------------------------------------------------------------
|
| PENTING:
| Route /tambah harus sebelum /{masterSaranaInventaris}
|
*/


/*
|--------------------------------------------------------------------------
| Master Sarana - READ
|--------------------------------------------------------------------------
|
| Guest boleh membaca.
|
*/

Route::get(
    '/master-sarana-inventaris',
    [MasterSaranaInventarisController::class, 'index']
)->name('master-sarana-inventaris.index');


/*
|--------------------------------------------------------------------------
| Master Sarana - ADMIN CREATE
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])->group(function () {

    Route::get(
        '/master-sarana-inventaris/tambah',
        [MasterSaranaInventarisController::class, 'create']
    )->name('master-sarana-inventaris.create');


    Route::post(
        '/master-sarana-inventaris',
        [MasterSaranaInventarisController::class, 'store']
    )->name('master-sarana-inventaris.store');


    Route::get(
        '/master-sarana-inventaris/{masterSaranaInventaris}/ubah',
        [MasterSaranaInventarisController::class, 'edit']
    )->name('master-sarana-inventaris.edit');


    Route::put(
        '/master-sarana-inventaris/{masterSaranaInventaris}',
        [MasterSaranaInventarisController::class, 'update']
    )->name('master-sarana-inventaris.update');


    Route::delete(
        '/master-sarana-inventaris/{masterSaranaInventaris}',
        [MasterSaranaInventarisController::class, 'destroy']
    )->name('master-sarana-inventaris.destroy');
});


/*
|--------------------------------------------------------------------------
| Master Sarana - DETAIL
|--------------------------------------------------------------------------
|
| HARUS paling bawah karena wildcard.
|
*/

Route::get(
    '/master-sarana-inventaris/{masterSaranaInventaris}',
    [MasterSaranaInventarisController::class, 'show']
)->name('master-sarana-inventaris.show');


/*
|--------------------------------------------------------------------------
| LOKASI
|--------------------------------------------------------------------------
|
| PENTING:
| Semua route statis sebelum /lokasi/{location}
|
*/


/*
|--------------------------------------------------------------------------
| Lokasi - READ
|--------------------------------------------------------------------------
|
| Guest boleh membaca.
|
*/

Route::get(
    '/lokasi',
    [LocationController::class, 'index']
)->name('locations.index');


/*
|--------------------------------------------------------------------------
| Lokasi - QR
|--------------------------------------------------------------------------
*/

Route::get(
    '/lokasi/cetak/qr',
    [LocationController::class, 'printQr']
)->name('locations.print.qr');


/*
|--------------------------------------------------------------------------
| Lokasi - ADMIN CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])->group(function () {

    Route::get(
        '/lokasi/tambah',
        [LocationController::class, 'create']
    )->name('locations.create');


    Route::post(
        '/lokasi',
        [LocationController::class, 'store']
    )->name('locations.store');


    Route::get(
        '/lokasi/{location}/ubah',
        [LocationController::class, 'edit']
    )->name('locations.edit');


    Route::put(
        '/lokasi/{location}',
        [LocationController::class, 'update']
    )->name('locations.update');


    Route::delete(
        '/lokasi/{location}',
        [LocationController::class, 'destroy']
    )->name('locations.destroy');
});


/*
|--------------------------------------------------------------------------
| Lokasi - QR PER ITEM
|--------------------------------------------------------------------------
*/

Route::get(
    '/lokasi/{location}/document',
    [LocationController::class, 'document']
)->name('locations.document');

Route::get(
    '/lokasi/{location}/qr',
    [LocationController::class, 'qr']
)->name('locations.qr');


/*
|--------------------------------------------------------------------------
| Lokasi - DETAIL
|--------------------------------------------------------------------------
|
| HARUS terakhir.
|
*/

Route::get(
    '/lokasi/{location}',
    [LocationController::class, 'show']
)->name('locations.show');


/*
|--------------------------------------------------------------------------
| PENANGGUNG JAWAB
|--------------------------------------------------------------------------
|
| PENTING:
| Route /tambah sebelum /{responsible}
|
*/


/*
|--------------------------------------------------------------------------
| Responsible - READ
|--------------------------------------------------------------------------
|
| Guest boleh membaca.
|
*/

Route::get(
    '/penanggung-jawab',
    [ResponsibleController::class, 'index']
)->name('responsibles.index');


/*
|--------------------------------------------------------------------------
| Responsible - ADMIN CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])->group(function () {

    Route::get(
        '/penanggung-jawab/tambah',
        [ResponsibleController::class, 'create']
    )->name('responsibles.create');


    Route::post(
        '/penanggung-jawab',
        [ResponsibleController::class, 'store']
    )->name('responsibles.store');


    Route::get(
        '/penanggung-jawab/{responsible}/ubah',
        [ResponsibleController::class, 'edit']
    )->name('responsibles.edit');


    Route::put(
        '/penanggung-jawab/{responsible}',
        [ResponsibleController::class, 'update']
    )->name('responsibles.update');


    Route::delete(
        '/penanggung-jawab/{responsible}',
        [ResponsibleController::class, 'destroy']
    )->name('responsibles.destroy');

    Route::get(
        '/penanggung-jawab/{responsible}/document-stock-opname',
        [ResponsibleController::class, 'documentStockOpname']
    )->name('responsibles.document-stock-opname');
});


/*
|--------------------------------------------------------------------------
| Responsible - DETAIL
|--------------------------------------------------------------------------
|
| HARUS terakhir karena wildcard.
|
*/

Route::get(
    '/penanggung-jawab/{responsible}',
    [ResponsibleController::class, 'show']
)->name('responsibles.show');

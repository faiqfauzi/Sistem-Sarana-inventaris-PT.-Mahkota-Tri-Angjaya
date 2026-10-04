<?php

namespace App\Models;

use App\Models\MasterSaranaInventaris;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'nomor_inventaris',
        'master_sarana_inventaris_id',
        'location_id',
        'responsible_id',
        'user',
        'foto',
        'status',
        'tanggal_perolehan',
        'spesifikasi',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
    ];

    public function masterSaranaInventaris()
    {
        return $this->belongsTo(
            MasterSaranaInventaris::class,
            'master_sarana_inventaris_id'
        );
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function responsible()
    {
        return $this->belongsTo(Responsible::class);
    }

    public function maintenances()
    {
        return $this->hasMany(InventoryMaintenance::class);
    }

    public function repairs()
    {
        return $this->hasMany(InventoryRepair::class);
    }

    public function stockOpnames()
    {
        return $this->hasMany(InventoryStockOpname::class);
    }

    public function mutations()
    {
        return $this->hasMany(InventoryMutation::class);
    }

    public function latestStockOpname()
    {
        return $this->hasOne(InventoryStockOpname::class)
            ->latestOfMany('tanggal');
    }
}

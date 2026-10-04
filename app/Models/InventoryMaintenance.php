<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    protected $fillable = [
        'inventory_id',
        'tanggal_perawatan',
        'tanggal_perawatan_selanjutnya',
        'jenis_perawatan',
        'pelaksana'
    ];

    protected $casts = [
        'tanggal_perawatan' => 'date',
        'tanggal_perawatan_selanjutnya' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

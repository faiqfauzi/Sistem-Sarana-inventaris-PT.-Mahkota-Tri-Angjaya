<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStockOpname extends Model
{
    protected $fillable = [
        'inventory_id',
        'tanggal',
        'kondisi',
        'auditor',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

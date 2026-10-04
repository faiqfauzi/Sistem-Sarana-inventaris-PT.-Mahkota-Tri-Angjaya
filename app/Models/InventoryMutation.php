<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMutation extends Model
{
    protected $fillable = [
        'inventory_id',
        'jenis',
        'tanggal',
        'alasan',
        'dokumen'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

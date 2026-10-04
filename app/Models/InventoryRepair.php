<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRepair extends Model
{
    protected $fillable = [
        'inventory_id',
        'tanggal_pengajuan',
        'tanggal_tindak_lanjut',
        'dokumen',
        'jenis_perbaikan',
        'pelaksana',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_tindak_lanjut' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
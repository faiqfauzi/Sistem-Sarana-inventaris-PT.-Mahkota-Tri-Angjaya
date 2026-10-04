<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    protected $fillable = [
        'nama_penanggung_jawab',
        'stock_opname_document',
        'stock_opname_uploaded_at',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'stock_opname_uploaded_at' => 'datetime',
    ];

    public function inventories()
    {
        return $this->hasMany(
            Inventory::class
        );
    }
}

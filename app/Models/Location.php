<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'nama_lokasi',
        'keterangan'
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'location_id');
    }
}
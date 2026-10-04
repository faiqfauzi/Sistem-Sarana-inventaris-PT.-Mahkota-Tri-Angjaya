<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSaranaInventaris extends Model
{
    protected $table = 'master_sarana_inventaris';

    protected $fillable = [
        'nama_sarana_inventaris',
    ];

    public function inventories()
    {
        return $this->hasMany(
            Inventory::class,
            'master_sarana_inventaris_id'
        );
    }
}

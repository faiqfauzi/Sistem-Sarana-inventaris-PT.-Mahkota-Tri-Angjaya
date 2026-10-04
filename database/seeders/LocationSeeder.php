<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['nama_lokasi'=>'Ruang HCE'],
            ['nama_lokasi'=>'Ruang Produksi'],
            ['nama_lokasi'=>'Ruang Keuangan'],
            ['nama_lokasi'=>'Gudang'],
            ['nama_lokasi'=>'Ruang Meeting'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
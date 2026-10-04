<?php

namespace Database\Seeders;

use App\Models\MasterSaranaInventaris;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $masterSaranaInventaris = [
            'Kendaraan',
            'Komputer',
            'Laptop',
            'Printer',
            'Scanner',
            'Handhphone',
            'AC',
            'Mesin Produksi',
            'Genset',
        ];

        foreach ($masterSaranaInventaris as $masterSaranaInventaris) {
            MasterSaranaInventaris::create([
                'nama_sarana_inventaris' => $masterSaranaInventaris,
            ]);
        }
    }
}
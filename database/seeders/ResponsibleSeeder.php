<?php

namespace Database\Seeders;

use App\Models\Responsible;
use Illuminate\Database\Seeder;

class ResponsibleSeeder extends Seeder
{
    public function run(): void
    {
        $responsibles = [
            'HCE',
            'IT',
            'Keuangan',
            'Marketing',
        ];

        foreach ($responsibles as $responsible) {
            Responsible::create([
                'nama_penanggung_jawab' => $responsible,
            ]);
        }
    }
}
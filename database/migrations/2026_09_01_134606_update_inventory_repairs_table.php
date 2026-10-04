<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_repairs', function (Blueprint $table) {

            // Ganti nama tanggal lama
            $table->renameColumn(
                'tanggal',
                'tanggal_pengajuan'
            );

            // Tanggal tindak lanjut boleh kosong
            $table->date('tanggal_tindak_lanjut')
                ->nullable()
                ->after('tanggal_pengajuan');

            // Dokumen boleh kosong
            $table->string('dokumen')
                ->nullable()
                ->after('tanggal_tindak_lanjut');

        });
    }

    public function down(): void
    {
        Schema::table('inventory_repairs', function (Blueprint $table) {

            $table->dropColumn([
                'tanggal_tindak_lanjut',
                'dokumen',
            ]);

            $table->renameColumn(
                'tanggal_pengajuan',
                'tanggal'
            );

        });
    }
};
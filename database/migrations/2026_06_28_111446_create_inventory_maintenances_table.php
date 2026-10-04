<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('inventory_maintenances', function (Blueprint $table) {
        $table->id();

        $table->foreignId('inventory_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->date('tanggal_perawatan');
        $table->date('tanggal_perawatan_selanjutnya')->nullable();
        $table->string('jenis_perawatan');
        $table->string('pelaksana');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenances');
    }
};

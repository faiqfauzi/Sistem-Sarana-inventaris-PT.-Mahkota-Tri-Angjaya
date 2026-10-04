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
    Schema::create('inventory_repairs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('inventory_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->date('tanggal');
        $table->string('jenis_perbaikan');
        $table->string('pelaksana');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_repairs');
    }
};

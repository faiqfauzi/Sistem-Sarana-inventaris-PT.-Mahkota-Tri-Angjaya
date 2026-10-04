<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_inventaris')->unique();

            $table->foreignId('master_sarana_inventaris_id')
                ->constrained('master_sarana_inventaris')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('location_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('responsible_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('user')->nullable();

            $table->string('status')->nullable();

            $table->string('foto')->nullable();

            $table->date('tanggal_perolehan')->nullable();

            $table->text('spesifikasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
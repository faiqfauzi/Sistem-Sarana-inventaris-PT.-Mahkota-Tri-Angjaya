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
        Schema::table('users', function (Blueprint $table) {

            $table->string('role')
                ->default('responsible')
                ->after('password');

            $table->foreignId('responsible_id')
                ->nullable()
                ->after('role')
                ->constrained('responsibles')
                ->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['responsible_id']);

            $table->dropColumn([
                'role',
                'responsible_id',
            ]);
        });
    }
};

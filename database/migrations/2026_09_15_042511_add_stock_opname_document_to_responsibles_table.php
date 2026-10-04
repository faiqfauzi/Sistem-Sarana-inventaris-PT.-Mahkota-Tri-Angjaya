<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsibles', function (Blueprint $table) {
            $table->string('stock_opname_document')->nullable()->after('nama_penanggung_jawab');
            $table->timestamp('stock_opname_uploaded_at')->nullable()->after('stock_opname_document');
        });
    }

    public function down(): void
    {
        Schema::table('responsibles', function (Blueprint $table) {
            $table->dropColumn([
                'stock_opname_document',
                'stock_opname_uploaded_at',
            ]);
        });
    }
};

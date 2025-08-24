<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Guard biar aman kalau kolomnya udah ga ada
        if (Schema::hasColumn('surat_keluars', 'metode_kirim')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->dropColumn('metode_kirim');
            });
        }
    }

    public function down(): void
    {
        // Balikin lagi kalau di-rollback
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->string('metode_kirim', 50)->nullable()->after('tujuan');
        });
    }
};

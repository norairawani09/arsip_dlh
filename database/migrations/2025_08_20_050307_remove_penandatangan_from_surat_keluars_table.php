<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('surat_keluars', 'penandatangan')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->dropColumn('penandatangan');
            });
        }
    }

    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->string('penandatangan', 255)->nullable()->after('lampiran');
        });
    }
};

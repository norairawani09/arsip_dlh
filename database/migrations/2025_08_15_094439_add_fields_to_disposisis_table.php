<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('disposisis', function (Blueprint $t) {
            if (!Schema::hasColumn('disposisis','tanggal_disposisi')) {
                $t->date('tanggal_disposisi')->nullable()->after('surat_masuk_id');
            }
            if (!Schema::hasColumn('disposisis','prioritas')) {
                $t->string('prioritas', 20)->nullable()->after('batas_waktu');
            }
            if (!Schema::hasColumn('disposisis','status')) {
                $t->string('status', 20)->default('proses')->after('prioritas');
            }
        });
    }

    public function down(): void {
        Schema::table('disposisis', function (Blueprint $t) {
            $t->dropColumn(['tanggal_disposisi','prioritas','status']);
        });
    }
};

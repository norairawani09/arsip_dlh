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
    Schema::table('surat_masuks', function (Blueprint $table) {
        $table->date('tanggal_terima')->after('tanggal_surat');
        $table->string('prioritas')->after('indeks');
        $table->string('status')->after('prioritas')->default('Pending');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            //
        });
    }
};

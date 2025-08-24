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
    Schema::create('surat_keluars', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat')->unique();
        $table->date('tanggal_surat');
        $table->string('tujuan');
        $table->string('alamat_tujuan')->nullable();
        $table->text('perihal');
        $table->string('lampiran')->nullable();
        $table->string('metode_kirim')->nullable();
        $table->string('penandatangan');
        $table->string('prioritas'); // Tinggi/Sedang/Rendah
        $table->string('status');    // Draft/Kirim
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};

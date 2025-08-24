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
    Schema::create('surat_masuks', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat')->unique();
        $table->date('tanggal_surat');
        $table->string('pengirim');
        $table->string('perihal');
        $table->string('indeks');
        $table->text('ringkasan')->nullable();
        $table->string('file_path')->nullable(); // Untuk menyimpan lampiran surat
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};

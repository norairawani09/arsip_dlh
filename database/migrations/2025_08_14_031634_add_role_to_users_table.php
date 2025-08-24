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
            // TAMBAHKAN BARIS INI
            $table->string('username')->after('name')->unique();

            // Baris ini sudah ada sebelumnya
            $table->string('role')->after('password')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita buat agar bisa menghapus kedua kolom
            $table->dropColumn(['role', 'username']);
        });
    }
};
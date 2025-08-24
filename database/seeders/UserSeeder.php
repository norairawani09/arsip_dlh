<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            // Akun Administrator
            [
                'name' => 'Ahmad Sutrisno',
                'username' => 'admin',
                'email' => 'admin@dlh.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Akun User Biasa
            [
                'name' => 'Budi Santoso',
                'username' => 'user',
                'email' => 'user@dlh.go.id',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
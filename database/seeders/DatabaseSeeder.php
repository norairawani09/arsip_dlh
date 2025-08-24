<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hanya ada satu fungsi run, yang memanggil UserSeeder.
        $this->call([
            UserSeeder::class,
        ]);
    }
}
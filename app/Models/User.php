<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh di-mass assign.
     */
    protected $fillable = [
        'name',
        'username', 
        'email',
        'password',
        'role',
        'is_active',   // kalau tabelmu pakai boolean is_active
        'status',      // kalau tabelmu pakai enum/string status (aktif / non-aktif)
        'jabatan',     // opsional, kalau kolom ini ada di tabel
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // auto-hash by Eloquent (lihat catatan di bawah)
            'is_active'         => 'boolean',
        ];
    }
}

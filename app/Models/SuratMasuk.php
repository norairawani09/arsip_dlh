<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'perihal',
        'indeks',
        'prioritas',
        'status',
        'ringkasan',
        'file_path',
    ];
public function disposisi()
{
    return $this->hasOne(\App\Models\Disposisi::class, 'surat_masuk_id');
}
}

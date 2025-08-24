<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
     protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'alamat_tujuan',
        'perihal',
        'prioritas',
        'status',
        'lampiran',   // <- path file disimpan di kolom ini
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    // kasih tau ke Laravel nama tabel yang dipakai
    protected $table = 'disposisis';

    protected $fillable = [
        'surat_masuk_id',
        'tujuan',
        'catatan',
        'batas_waktu',
        'status',      // kalau kolom ada di DB
        'prioritas',   // kalau kolom ada di DB
    ];

    public function surat()
    {
        return $this->belongsTo(\App\Models\SuratMasuk::class, 'surat_masuk_id');
    }
}

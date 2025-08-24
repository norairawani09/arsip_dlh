<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{public function index()
{
    // surat yang status-nya disposisi (case-insensitive) DAN belum ada record disposisi
    $belumDisposisi = \App\Models\SuratMasuk::with('disposisi')
        ->whereRaw('LOWER(status) = ?', ['disposisi'])
        ->doesntHave('disposisi')
        ->latest()
        ->get();

    // surat yang sudah punya record disposisi (apapun status suratnya)
    $sudahDisposisi = \App\Models\SuratMasuk::with('disposisi')
        ->whereHas('disposisi')
        ->latest()
        ->get();

    return view('admin.disposisi.index', compact('belumDisposisi', 'sudahDisposisi'));
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'surat_masuk_id'      => 'required|exists:surat_masuks,id',
            'tanggal_disposisi'   => 'required|date',
            'tujuan'              => 'required|string|max:255',
            'batas_waktu'         => 'nullable|date',
            'prioritas_disposisi' => 'required|in:Penting',
            'status_disposisi'    => 'required|in:Proses,Selesai',
            'catatan'             => 'nullable|string',
        ]);

        // upsert (kalau sudah ada -> update; kalau belum -> create)
        Disposisi::updateOrCreate(
            ['surat_masuk_id' => $data['surat_masuk_id']],
            [
                'tanggal_disposisi' => $data['tanggal_disposisi'],
                'tujuan'            => $data['tujuan'],
                'batas_waktu'       => $data['batas_waktu'] ?? null,
                'prioritas'         => $data['prioritas_disposisi'],
                'status'            => strtolower($data['status_disposisi']), // konsisten
                'catatan'           => $data['catatan'] ?? null,
            ]
        );

        // BIARKAN status surat tetap 'disposisi' supaya selalu tampil
        // (kalau mau pakai huruf besar di DB, silakan ganti ke 'Disposisi')
        SuratMasuk::whereKey($data['surat_masuk_id'])->update(['status' => 'disposisi']);

        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Surat berhasil didisposisikan!');
    }
}

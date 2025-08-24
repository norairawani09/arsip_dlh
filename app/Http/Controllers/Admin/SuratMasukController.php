<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <- penting utk hapus file

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $suratMasuk = SuratMasuk::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nomor_surat', 'like', "%{$q}%")
                      ->orWhere('pengirim',   'like', "%{$q}%")
                      ->orWhere('perihal',    'like', "%{$q}%")
                      ->orWhere('indeks',     'like', "%{$q}%")
                      ->orWhere('status',     'like', "%{$q}%")
                      ->orWhere('prioritas',  'like', "%{$q}%");
                });
            })
            ->latest('tanggal_terima') // urut terbaru by tgl terima
            ->get();

        // total keseluruhan (buat info "ditemukan X dari Y")
        $totalAll = SuratMasuk::count();

        return view('admin.surat-masuk.index', [
            'suratMasuk' => $suratMasuk,
            'q'          => $q,
            'totalAll'   => $totalAll,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat'    => 'required|string|max:255|unique:surat_masuks,nomor_surat',
            'tanggal_terima' => 'required|date',
            'tanggal_surat'  => 'required|date',
            'pengirim'       => 'required|string|max:255',
            'perihal'        => 'required|string',
            'indeks'         => 'required|string',
            'prioritas'      => 'required|string',
            'status'         => 'required|in:pending,disposisi,selesai,arsip',
            'lampiran'       => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf|max:2048',
        ]);

        $validated['status'] = strtolower($validated['status']);

        if ($request->hasFile('lampiran')) {
            $validated['file_path'] = $request->file('lampiran')->store('surat_masuk', 'public');
        }

        $surat = SuratMasuk::create($validated);

        return $surat->status === 'disposisi'
            ? redirect()->route('admin.disposisi.index')->with('success', 'Surat masuk ditambahkan & siap didisposisikan!')
            : redirect()->route('admin.surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan!');
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat'    => 'required|string|max:255|unique:surat_masuks,nomor_surat,' . $suratMasuk->id,
            'tanggal_terima' => 'required|date',
            'tanggal_surat'  => 'required|date',
            'pengirim'       => 'required|string|max:255',
            'perihal'        => 'required|string',
            'indeks'         => 'required|string',
            'prioritas'      => 'required|string',
            'status'         => 'required|in:pending,disposisi,selesai,arsip',
            'lampiran'       => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf|max:2048',
        ]);

        $before = strtolower($suratMasuk->status);
        $validated['status'] = strtolower($validated['status']);

        if ($request->hasFile('lampiran')) {
            if ($suratMasuk->file_path) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            $validated['file_path'] = $request->file('lampiran')->store('surat_masuk', 'public');
        }

        $suratMasuk->update($validated);

        if ($before !== 'disposisi' && $suratMasuk->status === 'disposisi') {
            return redirect()->route('admin.disposisi.index')->with('success', 'Status diubah ke Disposisi.');
        }

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui!');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }

        $suratMasuk->delete();

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $suratKeluar = SuratKeluar::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nomor_surat',   'like', "%{$q}%")
                      ->orWhere('tujuan',       'like', "%{$q}%")
                      ->orWhere('alamat_tujuan','like', "%{$q}%")
                      ->orWhere('perihal',      'like', "%{$q}%")
                      ->orWhere('prioritas',    'like', "%{$q}%")
                      ->orWhere('status',       'like', "%{$q}%");
                });
            })
            ->orderByDesc('tanggal_surat')
            ->get();

        $totalAll = SuratKeluar::count();

        return view('admin.surat-keluar.index', [
            'suratKeluar' => $suratKeluar,
            'q'           => $q,
            'totalAll'    => $totalAll,
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nomor_surat'   => 'required|string|max:255|unique:surat_keluars,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
            'perihal'       => 'required|string',
            'lampiran'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'prioritas'     => 'required|string|in:Penting',
            'status'        => 'required|string|in:Draft,Kirim',
        ]);

        if ($r->hasFile('lampiran')) {
            $data['lampiran'] = $r->file('lampiran')->store('surat-keluar', 'public');
        }

        SuratKeluar::create($data);

        return redirect()->route('admin.surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan!');
    }

    // >>> FIX DI SINI: pakai $id, bukan type-hint model
    public function destroy($id)
    {
        $suratKeluar = SuratKeluar::findOrFail($id);

        // (opsional) authorize jika pakai policy
        // $this->authorize('delete', $suratKeluar);

        if ($suratKeluar->lampiran && Storage::disk('public')->exists($suratKeluar->lampiran)) {
            Storage::disk('public')->delete($suratKeluar->lampiran);
        }

        // Kalau model kamu pakai SoftDeletes & mau hapus permanen:
        // $suratKeluar->forceDelete();
        // Kalau cukup soft delete (biasanya sudah hilang dari list default):
        $suratKeluar->delete();

        return redirect()->route('admin.surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus!');
    }
}

<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;

class DashboardController extends Controller
{
    public function index()
    {
        $u = Auth::user();
        if (!$u) return redirect()->route('login');

        // Admin biar ke dashboard admin yang sudah ada
        if ($u->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // === Angka untuk Dashboard User ===
        // (global count; kalau mau milik user saja, filter pakai created_by/user_id)
        $jumlahSuratMasuk  = SuratMasuk::count();
        $jumlahSuratKeluar = SuratKeluar::count();
        $jumlahDisposisi   = Disposisi::count();

        return view('user.dashboard', [
            'jumlahSuratMasuk'  => $jumlahSuratMasuk,
            'jumlahSuratKeluar' => $jumlahSuratKeluar,
            'jumlahDisposisi'   => $jumlahDisposisi,
        ]);
    }
}

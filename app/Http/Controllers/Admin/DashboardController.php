<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $now    = Carbon::now();
        $today  = Carbon::today(); // ✅ INI YANG KURANG

        // ===== STAT BOX =====
        $totalSuratMasuk     = SuratMasuk::count();
        $bulanIniSuratMasuk  = SuratMasuk::whereYear('created_at', $now->year)
                                         ->whereMonth('created_at', $now->month)
                                         ->count();
        $pendingSuratMasuk   = SuratMasuk::where('status', 'pending')->count();

        $totalSuratKeluar    = SuratKeluar::count();
        $bulanIniSuratKeluar = SuratKeluar::whereYear('created_at', $now->year)
                                          ->whereMonth('created_at', $now->month)
                                          ->count();
        $draftSuratKeluar    = SuratKeluar::where('status', 'draft')->count();

        $totalUser  = User::count();
        $userAktif  = User::where('status', 1)->count();   // pakai kolom 'status' sesuai skema kamu
        $userAdmin  = User::where('role', 'admin')->count();

        $totalArsip = $totalSuratMasuk + $totalSuratKeluar;

        // ===== DATA HARI INI UNTUK TABEL =====
        $todaySuratMasuk = SuratMasuk::whereDate('created_at', $today)
                            ->orderByDesc('created_at')
                            ->limit(10)
                            ->get();

        $todaySuratKeluar = SuratKeluar::whereDate('created_at', $today)
                            ->orderByDesc('created_at')
                            ->limit(10)
                            ->get();

        $jmMasukHariIni  = $todaySuratMasuk->count();
        $jmKeluarHariIni = $todaySuratKeluar->count();

        return view('admin.dashboard', compact(
            'totalSuratMasuk', 'bulanIniSuratMasuk', 'pendingSuratMasuk',
            'totalSuratKeluar', 'bulanIniSuratKeluar', 'draftSuratKeluar',
            'totalUser', 'userAktif', 'userAdmin', 'totalArsip',
            'todaySuratMasuk', 'todaySuratKeluar', 'jmMasukHariIni', 'jmKeluarHariIni'
        ));
    }
}

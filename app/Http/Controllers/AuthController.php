<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Periksa role dan arahkan
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            
            // Jika user biasa, arahkan ke dashboard user (misalnya)
            return redirect()->intended('/dashboard');
        }

        // 4. Jika gagal, kembali ke login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
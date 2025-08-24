<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login' => now()]);
            
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\Indeks;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'users_aktif' => User::where('status', 'aktif')->count(),
            'total_surat' => Surat::count(),
            'surat_masuk' => Surat::masuk()->count(),
            'surat_keluar' => Surat::keluar()->count(),
            'disposisi_pending' => Disposisi::pending()->count(),
            'disposisi_proses' => Disposisi::where('status', 'proses')->count(),
            'disposisi_selesai' => Disposisi::selesai()->count(),
        ];

        $recent_activities = $this->getRecentActivities();
        
        return view('admin.dashboard', compact('stats', 'recent_activities'));
    }

    public function users()
    {
        $users = User::with('suratDibuat', 'disposisiDiterima')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $user->update($validated);
        
        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    private function getRecentActivities()
    {
        // Implementasi untuk mendapatkan aktivitas terbaru
        return collect([
            [
                'action' => 'Login',
                'user' => 'Siti Rahayu',
                'time' => now()->subMinutes(15),
                'detail' => 'Login ke sistem'
            ],
            [
                'action' => 'Tambah Surat',
                'user' => 'Budi Santoso',
                'time' => now()->subHours(2),
                'detail' => 'Menambah surat masuk #001/DLH/2024'
            ]
        ]);
    }
}

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'surat_saya' => Surat::where('user_id', $user->id)->count(),
            'surat_masuk_saya' => Surat::masuk()->where('user_id', $user->id)->count(),
            'surat_keluar_saya' => Surat::keluar()->where('user_id', $user->id)->count(),
            'disposisi_pending' => Disposisi::where('kepada_user_id', $user->id)->pending()->count(),
            'disposisi_proses' => Disposisi::where('kepada_user_id', $user->id)->where('status', 'proses')->count(),
            'disposisi_selesai' => Disposisi::where('kepada_user_id', $user->id)->selesai()->count(),
        ];

        $tugas_disposisi = Disposisi::with(['surat', 'pemberi'])
            ->where('kepada_user_id', $user->id)
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('batas_waktu', 'asc')
            ->limit(5)
            ->get();

        $surat_terbaru = Surat::with('indeks')
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'tugas_disposisi', 'surat_terbaru'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jabatan' => 'nullable|string|max:255',
        ]);

        $user->update($validated);
        
        return redirect()->route('user.profile')->with('success', 'Profile berhasil diupdate');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('user.profile')->with('success', 'Password berhasil diubah');
    }
}

// app/Http/Controllers/SuratController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Indeks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function masuk()
    {
        $surat = Surat::with(['indeks', 'user'])
            ->masuk()
            ->latest()
            ->paginate(10);
            
        $indeks = Indeks::aktif()->get();
        
        return view('surat.masuk', compact('surat', 'indeks'));
    }

    public function keluar()
    {
        $surat = Surat::with(['indeks', 'user'])
            ->keluar()
            ->latest()
            ->paginate(10);
            
        $indeks = Indeks::aktif()->get();
        
        return view('surat.keluar', compact('surat', 'indeks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat',
            'jenis' => 'required|in:masuk,keluar',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
            'pengirim' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'perihal' => 'required|string',
            'indeks_id' => 'required|exists:indeks,id',
            'prioritas' => 'required|in:tinggi,sedang,rendah',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('surat');
        }

        $validated['user_id'] = Auth::id();
        
        Surat::create($validated);
        
        return redirect()->back()->with('success', 'Surat berhasil ditambahkan');
    }
}
?>
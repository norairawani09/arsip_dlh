<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // untuk generate username unik

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $role   = $request->query('role');
        $status = $request->query('status');

        $hasIsActive = Schema::hasColumn('users', 'is_active');
        $hasStatus   = Schema::hasColumn('users', 'status');

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                    if (Schema::hasColumn('users','jabatan')) {
                        $qq->orWhere('jabatan', 'like', "%{$q}%");
                    }
                    if (Schema::hasColumn('users','username')) {
                        $qq->orWhere('username', 'like', "%{$q}%");
                    }
                });
            })
            ->when(in_array($role, ['admin','user'], true), fn ($query) => $query->where('role', $role))
            ->when($status === 'active' && ($hasIsActive || $hasStatus), function ($query) use ($hasIsActive) {
                $hasIsActive ? $query->where('is_active', true)
                             : $query->where('status', 'aktif');
            })
            ->when($status === 'inactive' && ($hasIsActive || $hasStatus), function ($query) use ($hasIsActive) {
                $hasIsActive ? $query->where('is_active', false)
                             : $query->where('status', 'non-aktif');
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $countTotal = User::count();
        $countAdmin = User::where('role', 'admin')->count();
        $countUser  = User::where('role', 'user')->count();

        if ($hasIsActive) {
            $countActive = User::where('is_active', true)->count();
        } elseif ($hasStatus) {
            $countActive = User::where('status', 'aktif')->count();
        } else {
            $countActive = $countTotal;
        }
        $countInactive = max(0, $countTotal - $countActive);

        return view('admin.users.index', compact(
            'users','countTotal','countAdmin','countUser','countActive','countInactive','q','role','status'
        ));
    }

    /** CREATE / STORE USER */
    public function store(Request $request)
{
    $data = $request->validate([
        'name'       => ['required','string','max:255'],
        'username'   => ['required','alpha_dash','min:3','max:30','unique:users,username'], // ✅ pakai input
        'email'      => ['required','email','max:255','unique:users,email'],
        'password'   => ['required','string','min:8','confirmed'],
        'role'       => ['required', Rule::in(['admin','user'])],
        'is_active'  => ['required', Rule::in(['1','0'])],
    ]);

    $user = new User();
    $user->name     = trim($data['name']);
    $user->username = strtolower($data['username']); // ✅ simpan yang diinput (distandardize)
    $user->email    = strtolower($data['email']);
    $user->password = $data['password']; // casts 'hashed' di model akan nge-hash otomatis
    $user->role     = $data['role'];

    if (\Schema::hasColumn('users', 'is_active')) {
        $user->is_active = (bool) $data['is_active'];
    } elseif (\Schema::hasColumn('users', 'status')) {
        $user->status = $data['is_active'] === '1' ? 'aktif' : 'non-aktif';
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil dibuat ✅');
}


    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin','user'])],
        ]);

        // cegah menghapus admin terakhir
        if ($user->role === 'admin' && $data['role'] === 'user') {
            $otherAdmins = User::where('role','admin')->where('id','!=',$user->id)->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Gagal: Minimal harus ada 1 admin.');
            }
        }

        $user->update(['role' => $data['role']]);

        return back()->with('success', 'Role pengguna diperbarui.');
    }

    /** TOGGLE STATUS (flip nilai) */
    public function toggleStatus(User $user)
    {
        if (Schema::hasColumn('users', 'is_active')) {
            $user->is_active = ! (bool) $user->is_active;               // <-- flip
        } elseif (Schema::hasColumn('users', 'status')) {
            $user->status = strtolower($user->status) === 'aktif'       // <-- flip
                ? 'non-aktif'
                : 'aktif';
        }
        $user->save();

        return back()->with('success', 'Status pengguna diubah.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Nggak bisa hapus akun yang sedang login.');
        }

        if (strtolower($user->role ?? '') === 'admin') {
            $otherAdmins = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherAdmins === 0) {
                return back()->with('error', 'Minimal harus tersisa 1 admin.');
            }
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}

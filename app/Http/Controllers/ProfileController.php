<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;                // <-- WAJIB
use Illuminate\Support\Facades\Storage;  

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // ambil data user yang login
        return view('profile.index', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            'username' => ['nullable','string','max:50', Rule::unique('users','username')->ignore($user->id)],
            'avatar'   => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'], // 2MB
        ]);

        // handle avatar (opsional)
        if ($request->hasFile('avatar')) {
            // hapus lama kalau ada
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public'); // storage/app/public/avatars
            $validated['avatar_path'] = $path;
        }

        // simpan
        $user->fill($validated)->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public'); // storage/app/public/avatars
        $user->avatar_path = $path;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diunggah.');
    }

}

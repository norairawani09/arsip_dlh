@extends('layouts.app')

@section('content')
<style>
  :root{
    --bg:#f7faf7;
    --card:#ffffff;
    --ink:#1f2937;
    --muted:#6b7280;
    --line:#e5e7eb;
    --brand:#2E7D32;
    --brand-2:#4CAF50;
    --brand-weak:#E8F5E9;
    --danger:#dc2626;
  }
  .profile-wrap{max-width:1100px;margin:0 auto}
  .grid{display:grid;gap:24px}
  @media(min-width:960px){.grid{grid-template-columns:320px 1fr}}

  .card{background:var(--card);border:1px solid #eef2ef;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.06);padding:22px}
  .title{font-size:26px;font-weight:800;color:var(--ink);letter-spacing:.2px;margin:0}
  .subtitle{color:var(--muted);margin:6px 0 0}
  .pill{display:inline-flex;align-items:center;gap:8px;font-weight:600;padding:6px 10px;border-radius:999px;font-size:.8rem;background:var(--brand-weak);color:var(--brand)}
  .divider{height:1px;background:var(--line);margin:16px 0}

  .avatar-shell{position:relative;width:126px;height:126px;margin:6px auto 10px}
  .avatar{width:100%;height:100%;border-radius:999px;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 18px rgba(0,0,0,.08)}
  .avatar-fallback{width:100%;height:100%;border-radius:999px;background:linear-gradient(135deg,#43a047,#2e7d32);color:#fff;display:grid;place-items:center;font-size:42px;font-weight:800;letter-spacing:1px;box-shadow:0 4px 18px rgba(0,0,0,.08)}
  .avatar-action{position:absolute;right:-4px;bottom:-4px}
  .btn-icon{width:40px;height:40px;border-radius:10px;border:1px solid #cde7d3;background:#fff;display:grid;place-items:center;cursor:pointer;transition:.2s}
  .btn-icon:hover{background:var(--brand-weak);border-color:#a5d6a7}

  .form{display:grid;gap:16px}
  @media(min-width:720px){.form.cols{grid-template-columns:1fr 1fr}}
  .fg{display:flex;flex-direction:column;gap:8px}
  .label{font-weight:700;color:#334155}
  .hint{font-size:.82rem;color:var(--muted)}
  .inp{appearance:none;width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--line);background:#fff;color:var(--ink);outline:none;transition:.2s;box-shadow:0 1px 0 rgba(0,0,0,.01)}
  .inp:focus{border-color:var(--brand-2);box-shadow:0 0 0 3px rgba(76,175,80,.18)}
  .inp[disabled]{background:#f6f7f8;color:#97a0aa}

  .actions{display:flex;gap:10px;flex-wrap:wrap}
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px solid transparent;cursor:pointer;font-weight:700;transition:.2s}
  .btn-primary{background:var(--brand);color:#fff}
  .btn-primary:hover{background:#256c2c}
  .btn-ghost{background:#fff;border-color:var(--line);color:#334155}
  .btn-ghost:hover{background:#f3f7f4}

  .file-row{display:flex;gap:10px;align-items:center;justify-content:center;margin-top:8px}
  .file-input{display:none}
  .file-label{border:1px dashed #bcd5c0;border-radius:12px;padding:10px 14px;cursor:pointer;background:#f6fbf7;color:#2f6b38;font-weight:700}
  .file-name{font-size:.85rem;color:var(--muted)}

  .alert{padding:12px 14px;border-radius:12px;border:1px solid #bfe3c4;background:#eaf7ed;color:#14532d;margin-bottom:16px}
  .err{color:var(--danger);font-size:.85rem;margin-top:6px}

  body{background:var(--bg)}
</style>

<div class="profile-wrap">
  @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div class="grid">
    {{-- ===== Left: Avatar card ===== --}}
    <div class="card">
      <h2 class="title">Foto Profil</h2>
      <p class="hint" style="text-align:center;margin-top:8px;">PNG/JPG/WEBP • Maks 2MB</p>

      @php
        use Illuminate\Support\Facades\Storage;
        $avatarUrl = $user->avatar_path ? Storage::url($user->avatar_path) : null;

        $parts = explode(' ', $user->name);
        $initials = count($parts) > 1
            ? mb_substr($parts[0],0,1).mb_substr($parts[1],0,1)
            : mb_substr($parts[0],0,2);
        $initials = strtoupper($initials);
      @endphp

      <div class="avatar-shell">
        @if($avatarUrl)
          <img id="avatarPreview" src="{{ $avatarUrl }}?v={{ $user->updated_at?->timestamp }}" class="avatar" alt="Avatar">
        @else
          <div id="avatarPreviewFallback" class="avatar-fallback">{{ $initials }}</div>
        @endif

        <div class="avatar-action">
          <label for="avatarInput" class="btn-icon" title="Ganti foto">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 5l2 2h3a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h3l2-2z" stroke="#2f6b38" stroke-width="1.6"/>
              <circle cx="12" cy="13" r="3.2" stroke="#2f6b38" stroke-width="1.6"/>
            </svg>
          </label>
        </div>
      </div>

      {{-- FORM AVATAR KHUSUS --}}
      <form id="avatarForm"
            action="{{ route('profile.updateAvatar') }}"
            method="POST"
            enctype="multipart/form-data"
            style="margin-top:6px;">
        @csrf
        @method('PATCH')

        <div class="file-row">
          <input class="file-input" type="file" name="avatar" id="avatarInput" accept="image/png,image/jpeg,image/webp">
          <label for="avatarInput" class="file-label">Pilih gambar</label>
          <span id="fileName" class="file-name">Belum ada file</span>
        </div>

        @error('avatar') <div class="err">{{ $message }}</div> @enderror

        <div class="actions" style="justify-content:center;margin-top:10px;">
          <button class="btn btn-primary" type="submit" form="avatarForm">Simpan Foto</button>
          @if($user->avatar_path)
            <a class="btn btn-ghost" href="{{ Storage::url($user->avatar_path) }}" target="_blank">Lihat Asli</a>
          @endif
        </div>
      </form>
    </div>

    {{-- ===== Right: Profile form ===== --}}
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:14px">
        <div>
          <h1 class="title" style="margin-bottom:4px;">Profil Saya</h1>
          <p class="subtitle">Kelola data akun kamu di sini</p>
        </div>
        <span class="pill">Role: {{ ucfirst($user->role ?? 'User') }}</span>
      </div>

      <div class="divider"></div>

      <form action="{{ route('profile.update') }}" method="POST" class="form cols">
        @csrf
        @method('PATCH')

        <div class="fg">
          <label class="label">Nama</label>
          <input class="inp" type="text" name="name" value="{{ old('name', $user->name) }}" required>
          @error('name') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
          <label class="label">Email</label>
          <input class="inp" type="email" name="email" value="{{ old('email', $user->email) }}" required>
          @error('email') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
          <label class="label">Username</label>
          <input class="inp" type="text" name="username" value="{{ old('username', $user->username) }}">
          @error('username') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
          <label class="label">Dibuat sejak</label>
          <input class="inp" type="text" value="{{ $user->created_at?->format('d M Y') ?? '-' }}" disabled>
        </div>

        <div class="fg">
          <label class="label">Email terverifikasi</label>
          <input class="inp" type="text" value="{{ $user->email_verified_at?->format('d M Y') ?? '-' }}" disabled>
        </div>

        <div class="fg">
          <label class="label">Terakhir update</label>
          <input class="inp" type="text" value="{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}" disabled>
        </div>

        <div class="divider" style="grid-column:1/-1"></div>

        <div class="actions" style="grid-column:1/-1">
          <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
          <a class="btn btn-ghost"
   href="{{ strtolower(Auth::user()->role ?? '') === 'admin' ? route('admin.dashboard') : route('dashboard') }}">
  Kembali
</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Preview & nama file
  const fileInput = document.getElementById('avatarInput');
  const fileName  = document.getElementById('fileName');

  fileInput?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    fileName.textContent = file ? file.name : 'Belum ada file';
    if (!file) return;

    const url = URL.createObjectURL(file);
    const img = document.getElementById('avatarPreview');
    const fallback = document.getElementById('avatarPreviewFallback');

    if (img) {
      img.src = url;
    } else if (fallback) {
      const el = document.createElement('img');
      el.id = 'avatarPreview';
      el.src = url;
      el.className = 'avatar';
      fallback.replaceWith(el);
    }
  });
</script>
@endsection

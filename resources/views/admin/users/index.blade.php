{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .add-button{
      background:#388E3C;color:#fff;padding:10px 20px;border-radius:8px;border:none;
      font-weight:600;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
    }
    .add-button:hover{ filter:brightness(.95); }
    .page-cta{ display:flex; justify-content:flex-end; margin:14px 0; }

    :root{
      --bg:#eef7ee; --card:#fff; --ink:#0f2a16; --muted:#6b7d73; --bd:#e3efe3;
      --g:#2f7d48; --g-600:#23673a; --g-soft:#eaf6ee; --r:#d83b3b; --r-soft:#fdecec;
    }
    body{background:var(--bg); color:var(--ink); font-size:14px;}

    .page-h1{font-size:28px;font-weight:800;margin:4px 0}
    .page-sub{color:var(--muted); font-size:14px}
    .page-cta{display:flex;justify-content:flex-end;margin:14px 0}

    .btn-cta{
      --g:#2f7d48; --g-hover:#23673a;
      display:inline-flex; align-items:center; gap:8px;
      padding:10px 18px; border-radius:16px; background:var(--g);
      color:#fff; font-weight:800; line-height:1; border:none;
      box-shadow:0 4px 10px rgba(25,104,59,.18);
      transition:background .18s, transform .18s, box-shadow .18s;
      text-decoration:none; font-size:14px;
    }
    .btn-cta:hover{ background:var(--g-hover); transform:translateY(-1px); box-shadow:0 8px 16px rgba(25,104,59,.22); }
    .btn-cta:active{ transform:translateY(0); box-shadow:0 4px 10px rgba(25,104,59,.18); }

    .stats{display:grid;gap:16px;margin:10px 0}
    @media(min-width:900px){.stats{grid-template-columns:repeat(4,1fr)}}
    .stat{background:var(--card);border:1px solid var(--bd);border-radius:16px;padding:14px 16px;box-shadow:0 3px 0 rgba(0,0,0,.03)}
    .stat h4{font-size:16px;font-weight:700;margin:0 0 6px}
    .stat .val{font-size:26px;font-weight:900;color:var(--g)}
    .stat .sub{color:var(--muted);font-size:13px}

    .section{background:var(--card);border:1px solid var(--bd);border-radius:16px;padding:16px 18px}
    .section-title{display:flex;gap:10px;align-items:center;font-weight:800;font-size:18px;margin-bottom:4px}
    .section-sub{color:var(--muted);margin-bottom:12px;font-size:13px}

    .tools{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin:6px 0 12px}
    .inp,.sel{border:1px solid var(--bd);background:#fff;border-radius:10px;padding:8px 10px;font:inherit;font-size:14px}
    .inp{min-width:220px}
    .btn{border:0;padding:8px 12px;border-radius:10px;cursor:pointer;font-weight:700;font-size:14px}
    .btn-primary{background:var(--g);color:#fff}
    .btn-outline{background:#fff;color:var(--ink);border:1px solid var(--bd)}

    .table-wrap{overflow:auto;border:1px solid var(--bd);border-radius:16px;background:var(--card)}
    .table{width:100%;border-collapse:separate;border-spacing:0;font-size:14px}
    .table thead{background:#f5faf6}
    .table th,.table td{padding:10px 12px;border-bottom:1px solid var(--bd);text-align:left}
    .table th{font-weight:800;color:var(--muted)}
    .table tr:hover td{background:#fbfdfb}

    .badge{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:999px;font-size:11px;font-weight:800}
    .badge-role-admin{background:var(--g-soft);color:var(--g)}
    .badge-role-user{background:#e9f2ff;color:#1e5bb8}
    .badge-active{background:var(--g);color:#fff}
    .badge-inactive{background:var(--r-soft);color:var(--r)}

    .actions{display:flex;gap:6px;flex-wrap:wrap}
    .icon-chip{border:1px solid var(--bd);background:#fff;padding:8px;border-radius:10px;cursor:pointer;font-size:13px}
    .icon-chip:hover{background:#f7faf7}
    .icon-chip--danger{border-color:#f2c4c4}
    .icon-chip--danger:hover{background:#fff5f5}

    .alert{padding:8px 10px;border-radius:8px;display:inline-block;font-size:13px}
    .alert.success{background:#e8fbef;color:#0d6b3a}
    .alert.error{background:#ffefef;color:#b42323}

    /* Modal */
    .modal{position:fixed;inset:0;display:none;z-index:60}
    .modal.show{display:block}
    .modal__backdrop{position:absolute;inset:0;background:rgba(15,42,22,.45);backdrop-filter:blur(2px)}
    .modal__panel{position:relative;margin:60px auto;max-width:780px;background:#f1faf1;border:1px solid var(--bd);border-radius:16px;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.25)}
    @media (max-width:820px){ .modal__panel{margin:28px 12px} }
    .modal__header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
    .modal__title{font-size:20px;font-weight:800}
    .modal__desc{color:var(--muted);margin-bottom:14px}
    .modal__close{background:#fff;border:1px solid var(--bd);width:36px;height:36px;border-radius:10px;display:grid;place-items:center;cursor:pointer}
    .modal__grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .modal__grid .full{grid-column:1 / -1}
    .form-label{font-weight:700;margin-bottom:6px}
    .form-input,.form-select{width:100%;border:1px solid var(--bd);background:#fff;border-radius:10px;padding:10px 12px;font:inherit}
    .form-hint{font-size:12px;color:var(--muted);margin-top:6px}
    .err{color:#b42323;font-size:12px;margin-top:6px}
    .modal__footer{display:flex;justify-content:flex-end;gap:10px;margin-top:18px}
    .btn-danger{background:#c03636;color:#fff}
    .btn-danger:hover{background:#a62e2e}

    .page-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem}
    .page-header h2{margin:0}
</style>

<div class="page-header">
  <div>
    <h2 class="text-2xl font-bold">Manajemen Pengguna</h2>
    <p class="text-gray-600">Kelola akun dan hak akses pengguna sistem</p>
  </div>
  <button type="button" id="openCreate" class="add-button">
    <i class="fa-solid fa-plus"></i> Tambah Pengguna
  </button>
</div>

{{-- Stats --}}
<div class="stats">
  <div class="stat"><h4>Total Pengguna</h4><div class="val">{{ $countTotal }}</div><div class="sub">Terdaftar</div></div>
  <div class="stat"><h4>Administrator</h4><div class="val">{{ $countAdmin }}</div><div class="sub">Akses penuh</div></div>
  <div class="stat"><h4>Aktif</h4><div class="val">{{ $countActive }}</div><div class="sub">Pengguna aktif</div></div>
  <div class="stat"><h4>Non-Aktif</h4><div class="val" style="color:#d83b3b">{{ $countInactive }}</div><div class="sub">Dinonaktifkan</div></div>
</div>

{{-- Flash --}}
@if (session('success')) <div class="alert success">{{ session('success') }}</div> @endif
@if (session('error'))   <div class="alert error">{{ session('error') }}</div>   @endif

{{-- List Section --}}
<div class="section" style="margin-top:12px">
  <div class="section-title">📋 Daftar Pengguna</div>
  <div class="section-sub">Kelola akun pengguna dan hak akses sistem</div>

  {{-- Filter & Search --}}
  <form method="GET" class="tools">
    <input class="inp" type="text" name="q" value="{{ $q }}" placeholder="Cari berdasarkan nama atau email…">
    <select name="role" class="sel">
      <option value="">Semua Role</option>
      <option value="admin" {{ $role==='admin'?'selected':'' }}>Admin</option>
      <option value="user"  {{ $role==='user'?'selected':'' }}>User</option>
    </select>
    <select name="status" class="sel">
      <option value="">Semua Status</option>
      <option value="active"   {{ $status==='active'?'selected':'' }}>Aktif</option>
      <option value="inactive" {{ $status==='inactive'?'selected':'' }}>Non-Aktif</option>
    </select>
    <button class="btn btn-primary">Terapkan</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Reset</a>
  </form>

  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $u)
          @php
            $roleLc   = strtolower($u->role ?? '');
            $isActive = isset($u->is_active) ? (bool)$u->is_active
                      : (strtolower($u->status ?? '') === 'aktif');
          @endphp
          <tr>
            <td class="font-medium">{{ $u->name }}</td>
            <td>{{ $u->username }}</td>
            <td>{{ $u->email }}</td>
            <td>
              {{-- fixed: tampil badge saja, tanpa opsi ubah --}}
              @if($roleLc === 'admin')
                <span class="badge badge-role-admin">🛡️ Admin</span>
              @else
                <span class="badge badge-role-user">👤 User</span>
              @endif
            </td>
            <td>
              @if($isActive)
                <span class="badge badge-active">Aktif</span>
              @else
                <span class="badge badge-inactive">Non-Aktif</span>
              @endif
            </td>
            <td>
              <div class="actions">
                {{-- Hapus form UBAH ROLE & tombol 💾 -> DIHAPUS --}}
                {{-- Toggle Status --}}
                <form action="{{ route('admin.users.toggleStatus', $u) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button class="icon-chip" title="{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}">
                    {{ $isActive ? '⛔' : '✅' }}
                  </button>
                </form>

                {{-- Hapus User --}}
                <form action="{{ route('admin.users.destroy', $u) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus user ini? Aksi ini permanen.');">
                  @csrf
                  @method('DELETE')
                  <button class="icon-chip icon-chip--danger" title="Hapus pengguna">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;color:var(--muted);padding:20px">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:12px">
    {{ $users->links() }}
  </div>
</div>

{{-- ===== Modal: Create User (tetep boleh pilih role pas buat akun) ===== --}}
<div id="createModal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="createTitle">
  <div class="modal__backdrop" data-close></div>

  <div class="modal__panel">
    <div class="modal__header">
      <div>
        <div id="createTitle" class="modal__title">Tambah Pengguna Baru</div>
        <div class="modal__desc">Isi form berikut untuk menambahkan pengguna baru</div>
      </div>
      <button type="button" class="modal__close" title="Tutup" data-close>✕</button>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <input type="hidden" name="_form" value="create-user">

      <div class="modal__grid">
        <div>
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
          @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Username</label>
          <input type="text" name="username" value="{{ old('username') }}" class="form-input" required>
          @error('username')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
          @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input" required>
          <div class="form-hint">Minimal 8 karakter.</div>
          @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>

        <div>
          <label class="form-label">Role/Peran</label>
          <select name="role" class="form-select" required>
            <option value="" disabled @selected(old('role')===null)>Pilih role</option>
            <option value="user"  @selected(old('role')==='user')>User</option>
            <option value="admin" @selected(old('role')==='admin')>Admin</option>
          </select>
          @error('role')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="form-label">Status</label>
          <select name="is_active" class="form-select" required>
            <option value="" disabled @selected(old('is_active')===null)>Pilih status</option>
            <option value="1" @selected(old('is_active','1')==='1')>Aktif</option>
            <option value="0" @selected(old('is_active')==='0')>Non-Aktif</option>
          </select>
          @error('is_active')<div class="err">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="modal__footer">
        <button type="button" class="btn btn-outline" data-close>Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const modal   = document.getElementById('createModal');
  const openBtn = document.getElementById('openCreate');
  const closers = modal.querySelectorAll('[data-close]');

  const open  = () => { modal.classList.add('show'); modal.setAttribute('aria-hidden','false'); }
  const close = () => { modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); }

  openBtn?.addEventListener('click', open);
  closers.forEach(el => el.addEventListener('click', close));
  modal.addEventListener('click', (e)=>{ if(e.target.matches('.modal__backdrop')) close(); });
  window.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') close(); });

  const shouldOpen = "{{ old('_form')==='create-user' && $errors->any() ? '1' : '0' }}";
  if (shouldOpen === '1') open();
})();
</script>
@endsection

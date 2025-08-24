<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIPEN-DLH</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --sidebar-bg:#2E462F;
      --main-bg:#F7F9F7;
      --accent-green:#4CAF50;
      --text-light:#E0E0E0;
      --text-dark:#333333;
      --border-color:#E5E7EB;
    }
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'Poppins',sans-serif;background-color:var(--main-bg) !important;color:var(--text-dark);}
    .app-container{display:flex}

    /* ===== Sidebar ===== */
    .sidebar{width:260px;background:var(--sidebar-bg);color:var(--text-light);min-height:100vh;display:flex;flex-direction:column;padding:1.5rem;transition:width .3s ease;}
    .sidebar-header{display:flex;align-items:center;gap:12px;margin-bottom:2.5rem}
    .sidebar-title h2{font-size:1.25rem;line-height:1.2;white-space:nowrap}
    .sidebar-title p{font-size:.8rem;opacity:.7;white-space:nowrap}

    .sidebar-nav{list-style:none;flex-grow:1}
    .sidebar-nav li{border-radius:8px;margin-bottom:8px;transition:background-color .2s}
    .sidebar-nav li:hover{background-color:rgba(255,255,255,.1)}
    .sidebar-nav li.active{background-color:var(--accent-green)}
    .sidebar-nav li.active a{color:#fff}
    .sidebar-nav,.sidebar-nav li,.sidebar-nav li a{width:100%}
    .sidebar-nav li a{
      display:grid;grid-template-columns:28px 1fr;align-items:center;column-gap:12px;
      padding:12px 15px;text-decoration:none;color:var(--text-light);white-space:nowrap;font-weight:500;font-size:.95rem;line-height:1.5;
    }
    .sidebar-nav li a i,.sidebar-nav li a .fa-fw{width:28px;min-width:28px;text-align:center;font-size:1.1rem;justify-self:center}

    .sidebar-footer{border-top:1px solid rgba(255,255,255,.1);padding-top:1.5rem}
    .user-profile{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit}
    .user-avatar{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-weight:700;color:#fff;background:var(--accent-green);overflow:hidden}
    .user-avatar img{width:100%;height:100%;object-fit:cover;display:block}
    .user-info h4{font-size:.9rem}
    .user-info p{font-size:.75rem;opacity:.7}

    /* ===== Main ===== */
    .main-content{flex-grow:1;padding:2rem;max-height:100vh;overflow-y:auto}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
    .header-title h1{font-size:1.5rem}
    .header-title p{color:#6B7280;font-size:1rem}
    .header-right{display:flex;align-items:center;gap:12px}
    .header-right .name-stack{line-height:1.1}
    .header-right .name-stack .role{font-size:.9rem;color:#6B7280}

    /* FA fix */
    .sidebar i,.sidebar .fa,.sidebar .fas,.sidebar .far,.sidebar .fa-solid,.sidebar .fa-regular,.sidebar .fa-brands{
      font-family:"Font Awesome 6 Free" !important;font-weight:900 !important;
    }
  </style>
</head>
<body>
  @php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    $auth = Auth::user()?->fresh(); // refresh biar avatar terbaru kebaca
    $avatarUrl = $auth?->avatar_path ? Storage::url($auth->avatar_path) : null;

    $parts = explode(' ', $auth?->name ?? '');
    $initials = count($parts) > 1
        ? mb_substr($parts[0],0,1).mb_substr($parts[1],0,1)
        : mb_substr($parts[0] ?? '',0,2);
    $initials = strtoupper($initials);

    // === Role helper ===
    $isAdmin = ($auth?->role ?? 'user') === 'admin';

    // Dashboard link & active state sesuai role
    $dashboardRouteName = $isAdmin ? 'admin.dashboard' : 'dashboard';
    $dashboardUrl = route($dashboardRouteName);
    $dashboardActive = request()->routeIs($dashboardRouteName);
  @endphp

  <div class="app-container">
    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="{{ asset('assets/Logoo_DLH.png') }}" alt="Logo DLH" style="width:50px;height:50px;object-fit:contain;">
        <div class="sidebar-title">
          <h2>SIPEN-DLH</h2>
          <p>Dinas Lingkungan Hidup</p>
        </div>
      </div>

      <ul class="sidebar-nav">
        {{-- Dashboard: admin → admin.dashboard, user → dashboard --}}
        <li class="{{ $dashboardActive ? 'active' : '' }}">
          <a href="{{ $dashboardUrl }}"><i class="fa-solid fa-fw fa-tachometer-alt"></i> Dashboard</a>
        </li>

        {{-- ====== MENU SHARED (ADMIN & USER) ====== --}}
        <li class="{{ request()->routeIs('admin.surat-masuk.*') ? 'active' : '' }}">
          <a href="{{ route('admin.surat-masuk.index') }}"><i class="fa-solid fa-fw fa-inbox"></i> Surat Masuk</a>
        </li>
        <li class="{{ request()->routeIs('admin.surat-keluar.*') ? 'active' : '' }}">
          <a href="{{ route('admin.surat-keluar.index') }}"><i class="fa-solid fa-fw fa-inbox"></i> Surat Keluar</a>
        </li>
        <li class="{{ request()->routeIs('admin.disposisi.*') ? 'active' : '' }}">
          <a href="{{ route('admin.disposisi.index') }}"><i class="fa-solid fa-fw fa-file-signature"></i> Disposisi</a>
        </li>

        {{-- ====== MENU ADMIN-ONLY ====== --}}
        @if($isAdmin)
          <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-fw fa-user-shield"></i> Pengguna</a>
          </li>
        @endif
      </ul>

      <div class="sidebar-footer">
        <a href="{{ route('profile.index') }}" class="user-profile" style="margin-top:1rem">
          <div class="user-avatar">
            @if($avatarUrl)
              <img src="{{ $avatarUrl }}?v={{ $auth?->updated_at?->timestamp }}" alt="Avatar">
            @else
              {{ $initials }}
            @endif
          </div>
          <div class="user-info">
            <h4>{{ $auth?->name }}</h4>
            <p>Dinas Lingkungan Hidup</p>
          </div>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:1rem">
          @csrf
          <button type="submit" style="background:none;border:none;color:var(--text-light);cursor:pointer;display:flex;align-items:center;gap:15px;padding:12px 15px;width:100%;font-family:'Poppins',sans-serif;font-size:1rem">
            <i class="fa-solid fa-fw fa-right-from-bracket"></i> Keluar
          </button>
        </form>
      </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
      <!-- Header -->
      <header class="header">
        <div class="header-left">
          <div class="header-title">
            <h1>Sistem Informasi Pengarsipan Surat</h1>
            <p>Dinas Lingkungan Hidup - Pemerintah Kota</p>
          </div>
        </div>

        <div class="header-right">
          <a href="{{ route('profile.index') }}" class="user-profile">
            <div class="user-avatar" style="width:36px;height:36px;">
              @if($avatarUrl)
                <img src="{{ $avatarUrl }}?v={{ $auth?->updated_at?->timestamp }}" alt="Avatar">
              @else
                {{ $initials }}
              @endif
            </div>
            <div class="name-stack">
              <div style="font-weight:700">{{ $auth?->name }}</div>
              <div class="role">
                {{ $isAdmin ? 'Administrator' : 'User' }}
              </div>
            </div>
          </a>
        </div>
      </header>

      <!-- Konten -->
      <div class="content-area">
        @yield('content')
      </div>
    </main>
  </div>
</body>
</html>

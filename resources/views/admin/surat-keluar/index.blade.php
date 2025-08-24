@extends('layouts.app')

@section('content')
<style>
  :root{
    --dark-green:#2E7D32;
    --accent-green:#4CAF50;
    --border-color:#E5E7EB;
  }

  /* ===== Layout ===== */
  .page-title{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;}
  .add-button{
    background:#388E3C;color:#fff;padding:10px 20px;border-radius:8px;border:none;
    font-weight:600;display:inline-flex;gap:8px;align-items:center;cursor:pointer;
  }
  .add-button:hover{ filter:brightness(.95); }
  .main-card{ background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.05);padding:1.5rem; }
  .card-header{ display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem; }
  .search-bar{ position:relative;width:300px; }
  .search-bar input{ width:100%;padding:8px 12px 8px 40px;border-radius:8px;border:1px solid var(--border-color); }
  .search-bar i{ position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF; }

  /* ===== Table ===== */
  .table-container{ overflow-x:auto; }
  .list{ min-width:1250px; }
  /* Nomor | Tgl | Tujuan | Perihal | Prioritas | Lampiran | Status | Aksi */
  .list-header,.list-item{
    display:grid;
    grid-template-columns: 1.4fr 1.2fr 1.6fr 2.6fr 1.1fr 1.2fr 1fr 1fr;
    gap:1rem;align-items:center;padding:12px 1rem;
  }
  .list-header{ font-weight:700;color:#6B7280;font-size:.8rem;border-bottom:2px solid #F3F4F6;text-transform:uppercase; }
  .list-item{ border-bottom:1px solid #F3F4F6;font-size:.92rem; }
  .list-item:last-child{ border-bottom:none; }

  .chip{ padding:4px 10px;border-radius:999px;font-size:.75rem;font-weight:600;display:inline-block;text-align:center; }
  .chip-prio-tinggi{ background:#FFEBEE; color:#C62828; }
  .chip-prio-sedang{ background:#FFFDE7; color:#D97706; }
  .chip-prio-rendah{ background:#E0F7FA; color:#036672; }
  .chip-status-draft{ background:#E5E7EB; color:#374151; }
  .chip-status-kirim{ background:#E8F5E9; color:#2E7D32; }

  /* Lampiran pill */
  .lampiran-pill{
    display:inline-flex;align-items:center;gap:8px;
    padding:6px 10px;border-radius:999px;border:1px solid #e5e7eb;background:#f8faf8;
    font-weight:600;font-size:.8rem;color:#334155;text-decoration:none;
  }
  .lampiran-thumb{
    width:26px;height:26px;border-radius:6px;object-fit:cover;border:1px solid #e5e7eb;background:#fff;
  }
  .muted{color:#9CA3AF}

  .action { display:flex; gap:.5rem; }
  .action button{ border:1px solid #D1FAE5; background:#F0FDF4; border-radius:8px; padding:8px 12px; color:#14532D; cursor:pointer; }
  .action button:hover{ background:#DCFCE7; }

  .alert-success{ padding:1rem; margin-bottom:1.5rem; border-radius:8px; background:#E8F5E9; color:#2E7D32; border:1px solid #A5D6A7; }

  /* ===== Modal (footer fixed, body scroll) ===== */
  .modal-overlay{
    position:fixed; inset:0; background:rgba(0,0,0,.5);
    display:flex; justify-content:center; align-items:center;
    z-index:1000; opacity:0; visibility:hidden; transition:.25s;
  }
  .modal-overlay.open{ opacity:1; visibility:visible; }

  .modal-content{
    background:#fff; border-radius:12px; width:100%; max-width:550px;
    height:90vh; max-height:90vh;
    transform:scale(.95); transition:.25s; display:flex; flex-direction:column;
    overflow:hidden; border:1px solid #E5E7EB;
  }
  .modal-overlay.open .modal-content{ transform:scale(1); }

  .modal-header{
    padding:1.25rem 1.5rem; border-bottom:1px solid #E5E7EB;
    display:flex; justify-content:space-between; align-items:center; flex-shrink:0;
  }
  .modal-header h2{ font-size:1.25rem; font-weight:700; }
  .modal-close-btn{ background:none; border:0; font-size:1.75rem; cursor:pointer; color:#94A3B8; }

  .modal-form{ display:flex; flex-direction:column; flex:1 1 auto; min-height:0; }
  .modal-body{
    padding:1.25rem 1.5rem; overflow:auto; flex:1 1 auto; min-height:0;
    -webkit-overflow-scrolling:touch;
  }
  .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .form-group{ display:flex; flex-direction:column; }
  .form-group.full{ grid-column:1 / -1; }
  .modal-form label{ font-weight:600; margin-bottom:6px; }
  .modal-form input,.modal-form select,.modal-form textarea{
    padding:12px; border-radius:10px; border:1px solid #D1D5DB; background:#fff;
  }
  .modal-form input:focus,.modal-form select:focus,.modal-form textarea:focus{
    outline:none; border-color:var(--accent-green); box-shadow:0 0 0 2px rgba(76,175,80,.18);
  }
  .modal-form textarea{ min-height:96px; resize:vertical; }

  .modal-footer{
    padding:1rem 1.5rem; border-top:1px solid #E5E7EB; background:#F9FAFB;
    display:flex; justify-content:flex-end; gap:.75rem; flex-shrink:0;
  }
  .btn-secondary{ background:#E5E7EB; color:#374151; border:0; border-radius:10px; padding:10px 20px; font-weight:600; cursor:pointer; }
  .btn-primary{ background:var(--dark-green); color:#fff; border:0; border-radius:10px; padding:10px 20px; font-weight:600; cursor:pointer; }
  .search-bar{position:relative;width:300px}
.search-bar .clear-btn{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#9CA3AF;text-decoration:none;font-weight:700}
.search-bar .clear-btn:hover{color:#111827}

</style>

<div class="page-title">
  <div>
    <h2 class="text-2xl font-bold">Surat Keluar</h2>
    <p class="text-gray-600">Kelola dan arsipkan surat keluar</p>
  </div>
  <button id="add-btn" class="add-button">
    <i class="fa-solid fa-plus"></i> Tambah Surat
  </button>
</div>

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif


  <div class="card-header">
  <div class="left">
    <h3 class="font-semibold"><i class="fa-solid fa-paper-plane"></i> Daftar Surat Keluar</h3>
    <p class="text-sm text-gray-500">
     Total {{ $suratKeluar->count() }} surat tercatat
    </p>
  </div>

  {{-- SEARCH: form GET --}}
  <form class="search-bar" method="GET" action="{{ route('admin.surat-keluar.index') }}">
    <i class="fa-solid fa-search"></i>
    <input
      type="text"
      name="q"
      id="search-keluar"
      value="{{ $q ?? request('q') }}"
      placeholder="Cari nomor, tujuan, atau perihal…"
      autocomplete="off"
    >
    @if(!empty($q))
      <a href="{{ route('admin.surat-keluar.index') }}" class="clear-btn" title="Reset">✕</a>
    @endif
  </form>
</div>


  @php use Illuminate\Support\Facades\Storage; @endphp

  <div class="table-container">
    <div class="list">
      <div class="list-header">
        <span>Nomor Surat</span>
        <span>Tanggal</span>
        <span>Tujuan</span>
        <span>Perihal</span>
        <span>Prioritas</span>
        <span>Lampiran</span>
        <span>Status Kirim</span>
        <span>Aksi</span>
      </div>

      @forelse($suratKeluar as $row)
        @php
          // PAKAI kolom 'lampiran'
          $url = $row->lampiran ? Storage::url($row->lampiran) : null;
          $ext = $url ? strtolower(pathinfo($url, PATHINFO_EXTENSION)) : null;
          $isImg = in_array($ext, ['jpg','jpeg','png','webp','gif']);
        @endphp
        <div class="list-item">
          <div>{{ $row->nomor_surat }}</div>
          <div><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($row->tanggal_surat)->format('d/m/Y') }}</div>
          <div>{{ $row->tujuan }}</div>
          <div>{{ \Illuminate\Support\Str::limit($row->perihal, 50) }}</div>

          <div>
            @php $p=strtolower($row->prioritas??'rendah'); @endphp
            <span class="chip {{ $p==='tinggi'?'chip-prio-tinggi':($p==='sedang'?'chip-prio-sedang':'chip-prio-rendah') }}">
              {{ ucfirst($row->prioritas ?? 'Rendah') }}
            </span>
          </div>

          {{-- LAMPIRAN --}}
          <div>
            @if($url)
              @if($isImg)
                <a class="lampiran-pill" href="{{ $url }}" target="_blank">
                  <img class="lampiran-thumb" src="{{ $url }}" alt="lampiran">
                  Lihat
                </a>
              @else
                <a class="lampiran-pill" href="{{ $url }}" target="_blank">
                  <i class="fa-regular fa-file"></i> File
                </a>
              @endif
            @else
              <span class="muted">-</span>
            @endif
          </div>

          <div>
            @php $s=strtolower($row->status??'draft'); @endphp
            <span class="chip {{ $s==='kirim'?'chip-status-kirim':'chip-status-draft' }}">{{ ucfirst($row->status ?? 'Draft') }}</span>
          </div>

          <div class="action">
            <form action="{{ route('admin.surat-keluar.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan/hapus surat ini?')">
              @csrf @method('DELETE')
              <button type="submit" title="Batal">Batal</button>
            </form>
          </div>
        </div>
      @empty
        <div class="text-center p-8 text-gray-500" style="grid-column:1/-1">Belum ada surat keluar.</div>
      @endforelse
    </div>
  </div>
</div>

<!-- ===== MODAL TAMBAH SURAT KELUAR ===== -->
<div id="add-modal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <div>
        <h2>Tambah Surat Keluar</h2>
        <p class="text-gray-600">Isi form berikut untuk menambahkan surat keluar baru</p>
      </div>
      <button class="modal-close-btn" type="button">&times;</button>
    </div>

    <form action="{{ route('admin.surat-keluar.store') }}" method="POST" class="modal-form" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" id="nomor_surat" name="nomor_surat" placeholder="Contoh: 005/DLH/2025" required>
          </div>

          <div class="form-group">
            <label for="tanggal_surat">Tanggal Surat</label>
            <input type="date" id="tanggal_surat" name="tanggal_surat" required>
          </div>

          <div class="form-group">
            <label for="tujuan">Tujuan</label>
            <input type="text" id="tujuan" name="tujuan" placeholder="Nama instansi/perorangan" required>
          </div>

          <div class="form-group">
            <label for="prioritas">Prioritas</label>
            <select id="prioritas" name="prioritas" required>
              <option value="" selected disabled>Pilih prioritas</option>
              <option value="Penting">Penting</option>
            </select>
          </div>

          <div class="form-group full">
            <label for="alamat_tujuan">Alamat Tujuan</label>
            <input type="text" id="alamat_tujuan" name="alamat_tujuan" placeholder="Alamat lengkap tujuan">
          </div>

          <div class="form-group full">
            <label for="perihal">Perihal</label>
            <textarea id="perihal" name="perihal" placeholder="Ringkasan isi surat" required></textarea>
          </div>

          {{-- FILE UPLOAD -> DISIMPAN KE KOLOM "lampiran" --}}
          <div class="form-group full">
            <label for="lampiran">Upload Lampiran (gambar/PDF, maks 2MB)</label>
            <input type="file" id="lampiran" name="lampiran" accept="image/*,application/pdf">
          </div>

          <div class="form-group">
            <label for="status">Status Kirim</label>
            <select id="status" name="status" required>
              <option value="Draft" selected>Draft</option>
              <option value="Kirim">Kirim</option>
            </select>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary modal-cancel-btn">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const addBtn  = document.getElementById('add-btn');
    const modal   = document.getElementById('add-modal');

    addBtn?.addEventListener('click', () => modal.classList.add('open'));

    const closers = modal.querySelectorAll('.modal-close-btn, .modal-cancel-btn');
    closers.forEach(el => el.addEventListener('click', () => modal.classList.remove('open')));

    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });
  });
</script>
@endsection

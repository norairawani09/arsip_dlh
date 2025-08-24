@extends('layouts.app')

@section('content')
<style>
  :root{ --dark-green:#2E7D32; --accent-green:#4CAF50; --border-color:#E5E7EB; }

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
  .mail-list{ min-width:1400px; }

  /*  Nomor | Tgl Terima | Tgl Surat | Pengirim | Perihal | Indeks | Status | Prioritas | Lampiran | Aksi */
  .mail-list-header,.mail-list-item{
    display:grid;
    grid-template-columns:1.6fr 1.2fr 1.2fr 1.6fr 2.6fr 1fr 1fr 1fr 1.2fr 1fr;
    gap:1rem;align-items:center;padding:12px 1rem;
  }
  .mail-list-header{
    font-weight:700;color:#6B7280;font-size:.8rem;
    border-bottom:2px solid #F3F4F6;text-transform:uppercase;
  }
  .mail-list-item{ border-bottom:1px solid #F3F4F6;font-size:.92rem; }
  .mail-list-item:last-child{ border-bottom:none; }
  .mail-list-item div{ display:flex;align-items:center;gap:8px; }

  /* ===== Badges/Chips ===== */
  .indeks-tag,.status-tag,.priority-tag{
    padding:4px 10px;border-radius:999px;font-size:.75rem;font-weight:600;text-align:center;
    display:inline-block;
  }
  .tag-perizinan{ background:#E0F2F1;color:#00796B; }
  .tag-rapat{ background:#E3F2FD;color:#1E88E5; }
  .tag-laporan{ background:#F3E5F5;color:#8E24AA; }

  .status-pending{ background:#FFF3E0;color:#E65100; }
  .status-disposisi{ background:#E8EAF6;color:#3949AB; }
  .status-selesai{ background:#E8F5E9;color:#2E7D32; }

  .priority-penting{ background:#FFEBEE;color:#C62828; }

  /* Lampiran */
  .lampiran-pill{
    display:inline-flex;align-items:center;gap:8px;
    padding:6px 10px;border-radius:999px;border:1px solid #e5e7eb;background:#f8faf8;
    font-weight:600;font-size:.8rem;color:#334155;text-decoration:none;
  }
  .lampiran-thumb{
    width:26px;height:26px;border-radius:6px;object-fit:cover;border:1px solid #e5e7eb;
    background:#fff;
  }
  .muted{color:#9CA3AF}

  /* Aksi */
  .action-buttons{ display:flex;gap:.5rem;justify-content:flex-start; }
  .action-buttons a,.action-buttons button{
    color:#6B7280;text-decoration:none;padding:6px 8px;border-radius:6px;transition:.2s;background:none;border:none;cursor:pointer;font-size:1rem;
  }
  .action-buttons a:hover,.action-buttons button:hover{ background:#F3F4F6;color:#111827; }

  /* ===== Modal ===== */
  .modal-overlay{
    position:fixed;inset:0;background:rgba(0,0,0,.5);
    display:flex;justify-content:center;align-items:center;
    z-index:1000;opacity:0;visibility:hidden;transition:.25s;
  }
  .modal-overlay.open{ opacity:1;visibility:visible; }
  .modal-content{
    background:#fff;border-radius:12px;width:100%;max-width:550px;border:1px solid #E5E7EB;
    transform:scale(.95);transition:.25s;display:flex;flex-direction:column;max-height:90vh;overflow:hidden;
  }
  .modal-overlay.open .modal-content{ transform:scale(1); }
  .modal-header{
    padding:1.25rem 1.5rem;border-bottom:1px solid #E5E7EB;
    display:flex;justify-content:space-between;align-items:center;flex-shrink:0;
  }
  .modal-header h2{ font-size:1.25rem;font-weight:700; }
  .modal-close-btn{ background:none;border:0;font-size:1.75rem;cursor:pointer;color:#94A3B8; }

  .modal-form{ display:flex;flex-direction:column;overflow:hidden;flex-grow:1; }
  .modal-body{ padding:1.25rem 1.5rem;overflow:auto;flex-grow:1; }
  .modal-form .form-grid{ display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
  .modal-form .form-group{ display:flex;flex-direction:column; }
  .modal-form .form-group.full-width{ grid-column:1 / -1; }
  .modal-form label{ font-weight:600;margin-bottom:6px; }
  .modal-form input,.modal-form select,.modal-form textarea{
    padding:12px;border-radius:10px;border:1px solid #D1D5DB;background:#fff;font-family:'Poppins',sans-serif;
  }
  .modal-form input:focus,.modal-form select:focus,.modal-form textarea:focus{
    outline:none;border-color:var(--accent-green);box-shadow:0 0 0 2px rgba(76,175,80,.18);
  }
  .modal-form textarea{ min-height:96px;resize:vertical; }

  .modal-footer{
    padding:1rem 1.5rem;border-top:1px solid #E5E7EB;background:#F9FAFB;
    display:flex;justify-content:flex-end;gap:.75rem;flex-shrink:0;
  }
  .btn-secondary{ background:#E5E7EB;color:#374151;border:0;border-radius:10px;padding:10px 20px;font-weight:600;cursor:pointer; }
  .btn-primary{ background:var(--dark-green);color:#fff;border:0;border-radius:10px;padding:10px 20px;font-weight:600;cursor:pointer; }

  .alert-success{ padding:1rem;margin-bottom:1.5rem;border-radius:8px;background:#E8F5E9;color:#2E7D32;border:1px solid #A5D6A7; }
  .search-bar { position:relative;width:300px; }
.search-bar .clear-btn{
  position:absolute; right:10px; top:50%; transform:translateY(-50%);
  text-decoration:none; color:#9CA3AF; font-weight:700; padding:0 4px;
}
.search-bar .clear-btn:hover{ color:#111827; }

</style>

<div class="page-title">
  <div>
    <h2 class="text-2xl font-bold">Surat Masuk</h2>
    <p style="text-gray-600">Kelola dan arsipkan surat masuk</p>
  </div>
  <button id="add-mail-btn" class="add-button">
    <i class="fa-solid fa-plus"></i>
    Tambah Surat
  </button>
</div>

@if(session('success'))
  <div class="alert-success" role="alert">
    {{ session('success') }}
  </div>
@endif

<div class="main-card">
  <div class="card-header">
    <div>
      <h3 class="font-semibold"><i class="fa-solid fa-folder-open"></i> Daftar Surat Masuk</h3>
      <p class="text-sm text-gray-500">Total {{ $suratMasuk->count() }} surat tercatat</p>
    </div>
   <form class="search-bar" method="GET" action="{{ route('admin.surat-masuk.index') }}">
  <i class="fa-solid fa-search"></i>
  <input
    type="text"
    name="q"
    value="{{ $q ?? request('q') }}"
    placeholder="Cari berdasarkan nomor, pengirim, atau perihal..."
    autocomplete="off"
    id="search-input"
  >
  @if(!empty($q))
    <a href="{{ route('admin.surat-masuk.index') }}" class="clear-btn" title="Reset">✕</a>
  @endif
</form>

  </div>

  <div class="table-container">
    <div class="mail-list">
      <div class="mail-list-header">
        <span>NOMOR SURAT</span>
        <span>TGL TERIMA</span>
        <span>TGL SURAT</span>
        <span>PENGIRIM</span>
        <span>PERIHAL</span>
        <span>INDEKS</span>
        <span>STATUS</span>
        <span>PRIORITAS</span>
        <span>LAMPIRAN</span>
        <span>AKSI</span>
      </div>

      @php use Illuminate\Support\Facades\Storage; @endphp

      @forelse ($suratMasuk as $surat)
        @php
          $lampUrl = $surat->file_path ? Storage::url($surat->file_path) : null;
          $ext = $lampUrl ? strtolower(pathinfo($lampUrl, PATHINFO_EXTENSION)) : null;
          $isImg = in_array($ext,['jpg','jpeg','png','webp','gif']);
        @endphp
        <div class="mail-list-item">
          <div>{{ $surat->nomor_surat }}</div>
          <div><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($surat->tanggal_terima)->format('d/m/Y') }}</div>
          <div><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</div>
          <div><i class="fa-regular fa-user"></i> {{ $surat->pengirim }}</div>
          <div>{{ \Illuminate\Support\Str::limit($surat->perihal, 45) }}</div>
          <div><span class="indeks-tag tag-{{ strtolower($surat->indeks) }}">{{ $surat->indeks }}</span></div>
          <div><span class="status-tag status-{{ strtolower($surat->status) }}">{{ $surat->status }}</span></div>
          <div><span class="priority-tag priority-{{ strtolower($surat->prioritas) }}">{{ $surat->prioritas }}</span></div>

          {{-- LAMPIRAN --}}
          <div>
            @if($lampUrl)
              @if($isImg)
                <a href="{{ $lampUrl }}" target="_blank" class="lampiran-pill">
                  <img src="{{ $lampUrl }}" alt="lampiran" class="lampiran-thumb">
                  Lihat
                </a>
              @else
                <a href="{{ $lampUrl }}" target="_blank" class="lampiran-pill">
                  <i class="fa-regular fa-file"></i> File
                </a>
              @endif
            @else
              <span class="muted">-</span>
            @endif
          </div>

          <div class="action-buttons">
            <button class="edit-btn" title="Edit Surat"
              data-id="{{ $surat->id }}"
              data-nomor_surat="{{ $surat->nomor_surat }}"
              data-tanggal_terima="{{ $surat->tanggal_terima }}"
              data-tanggal_surat="{{ $surat->tanggal_surat }}"
              data-pengirim="{{ $surat->pengirim }}"
              data-perihal="{{ $surat->perihal }}"
              data-indeks="{{ $surat->indeks }}"
              data-prioritas="{{ $surat->prioritas }}"
              data-status="{{ $surat->status }}">
              <i class="fa-regular fa-pen-to-square"></i>
            </button>

            <form action="{{ route('admin.surat-masuk.destroy', $surat->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" title="Hapus Surat" onclick="return confirm('Anda yakin ingin menghapus surat ini?')">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </form>
          </div>
        </div>
      @empty
        <div class="text-center p-8 text-gray-500" style="grid-column: 1 / -1;">
          Belum ada surat masuk yang tercatat.
        </div>
      @endforelse
    </div>
  </div>
</div>

<!-- ===== MODAL TAMBAH ===== -->
<div id="add-mail-modal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <div>
        <h2>Tambah Surat Masuk</h2>
        <p class="text-gray-600">Isi form untuk menambahkan surat baru</p>
      </div>
      <button class="modal-close-btn" type="button">&times;</button>
    </div>

    {{-- multipart utk upload --}}
    <form action="{{ route('admin.surat-masuk.store') }}" method="POST" class="modal-form" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" id="nomor_surat" name="nomor_surat" placeholder="Contoh: 001/ABC/2024" required>
          </div>
          <div class="form-group">
            <label for="tanggal_terima">Tanggal Terima</label>
            <input type="date" id="tanggal_terima" name="tanggal_terima" required>
          </div>
          <div class="form-group">
            <label for="tanggal_surat">Tanggal Surat</label>
            <input type="date" id="tanggal_surat" name="tanggal_surat" required>
          </div>
          <div class="form-group">
            <label for="pengirim">Pengirim</label>
            <input type="text" id="pengirim" name="pengirim" placeholder="Nama pengirim" required>
          </div>
          <div class="form-group full-width">
            <label for="perihal">Perihal</label>
            <textarea id="perihal" name="perihal" placeholder="Ringkasan perihal surat" required></textarea>
          </div>
          <div class="form-group">
            <label for="indeks">Indeks</label>
            <select id="indeks" name="indeks" required>
              <option value="">Pilih indeks</option>
              <option value="Perizinan">Perizinan</option>
              <option value="Rapat">Rapat</option>
              <option value="Laporan">Laporan</option>
            </select>
          </div>
          <div class="form-group">
            <label for="prioritas">Prioritas</label>
            <select id="prioritas" name="prioritas" required>
              <option value="">Pilih prioritas</option>
              <option value="Penting">Penting</option>
            </select>
          </div>

          {{-- input lampiran --}}
          <div class="form-group full-width">
            <label for="lampiran">Lampiran (gambar/PDF, maks 2MB)</label>
            <input type="file" id="lampiran" name="lampiran" accept="image/*,application/pdf">
          </div>

          <div class="form-group full-width">
            <label for="status">Status Awal</label>
            <select id="status" name="status" required>
              <option value="pending" selected>Pending (Disimpan sebagai draft)</option>
              <option value="disposisi">Langsung Disposisi</option>
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

<!-- ===== MODAL EDIT ===== -->
<div id="edit-mail-modal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <div>
        <h2>Edit Surat Masuk</h2>
        <p class="text-gray-600">Perbarui detail surat masuk</p>
      </div>
      <button class="modal-close-btn" type="button">&times;</button>
    </div>

    {{-- multipart utk upload baru (opsional ganti lampiran) --}}
    <form id="edit-form" method="POST" class="modal-form" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label for="edit_nomor_surat">Nomor Surat</label>
            <input type="text" id="edit_nomor_surat" name="nomor_surat" required>
          </div>
          <div class="form-group">
            <label for="edit_tanggal_terima">Tanggal Terima</label>
            <input type="date" id="edit_tanggal_terima" name="tanggal_terima" required>
          </div>
          <div class="form-group">
            <label for="edit_tanggal_surat">Tanggal Surat</label>
            <input type="date" id="edit_tanggal_surat" name="tanggal_surat" required>
          </div>
          <div class="form-group">
            <label for="edit_pengirim">Pengirim</label>
            <input type="text" id="edit_pengirim" name="pengirim" required>
          </div>
          <div class="form-group full-width">
            <label for="edit_perihal">Perihal</label>
            <textarea id="edit_perihal" name="perihal" required></textarea>
          </div>
          <div class="form-group">
            <label for="edit_indeks">Indeks</label>
            <select id="edit_indeks" name="indeks" required>
              <option value="Perizinan">Perizinan</option>
              <option value="Rapat">Rapat</option>
              <option value="Laporan">Laporan</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_prioritas">Prioritas</label>
            <select id="edit_prioritas" name="prioritas" required>
              <option value="Penting">Penting</option>
            </select>
          </div>

          {{-- ganti lampiran (opsional) --}}
          <div class="form-group full-width">
            <label for="edit_lampiran">Ganti Lampiran (gambar/PDF)</label>
            <input type="file" id="edit_lampiran" name="lampiran" accept="image/*,application/pdf">
          </div>

          <div class="form-group">
            <label for="edit_status">Status</label>
            <select id="edit_status" name="status" required>
              <option value="pending">Pending</option>
              <option value="disposisi">Disposisi</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary modal-cancel-btn">Batal</button>
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // --- Modal Tambah ---
    const addMailBtn = document.getElementById('add-mail-btn');
    const addMailModal = document.getElementById('add-mail-modal');
    if (addMailBtn && addMailModal) {
      addMailBtn.addEventListener('click', () => addMailModal.classList.add('open'));
    }

    // --- Modal Edit ---
    const editMailModal = document.getElementById('edit-mail-modal');
    const editForm = document.getElementById('edit-form');
    const editButtons = document.querySelectorAll('.edit-btn');

    if (editMailModal && editForm) {
      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const data = this.dataset;
          const url = `{{ url('admin/surat-masuk') }}/${data.id}`;
          editForm.action = url;

          document.getElementById('edit_nomor_surat').value = data.nomor_surat;
          document.getElementById('edit_tanggal_terima').value = data.tanggal_terima;
          document.getElementById('edit_tanggal_surat').value = data.tanggal_surat;
          document.getElementById('edit_pengirim').value = data.pengirim;
          document.getElementById('edit_perihal').value = data.perihal;
          document.getElementById('edit_indeks').value = data.indeks;
          document.getElementById('edit_prioritas').value = data.prioritas;
          document.getElementById('edit_status').value = data.status;

          editMailModal.classList.add('open');
        });
      });
    }

    // --- Close semua modal ---
    const allModals = document.querySelectorAll('.modal-overlay');
    allModals.forEach(modal => {
      modal.querySelector('.modal-close-btn')?.addEventListener('click', () => modal.classList.remove('open'));
      modal.querySelector('.modal-cancel-btn')?.addEventListener('click', () => modal.classList.remove('open'));
      modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('open'); });
    });
  });
</script>
@endsection

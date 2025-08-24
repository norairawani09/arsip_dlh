@extends('layouts.app')

@section('content')
<style>
    :root{ --dark-green:#2E7D32; --accent-green:#4CAF50; --border-color:#E5E7EB; }

    .summary-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-bottom:2rem}
    .summary-card{background:#fff;padding:1.5rem;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.05);display:flex;justify-content:space-between;align-items:flex-start}
    .summary-card .info h3{font-size:2rem;font-weight:700}
    .summary-card .info p{color:#6B7280}
    .summary-card .tag{padding:4px 12px;border-radius:999px;font-size:.75rem;font-weight:500}
    .tag-urgent{background:#FFEBEE;color:#C62828}.tag-active{background:#E8F5E9;color:#2E7D32}.tag-completed{background:#E3F2FD;color:#1565C0}

    .main-card{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.05);padding:1.5rem}
    .card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
    .card-header h3{display:flex;gap:.5rem;align-items:center}
    .search-bar{position:relative;width:300px}
    .search-bar input{width:100%;padding:8px 12px 8px 40px;border-radius:8px;border:1px solid var(--border-color)}
    .search-bar i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF}

    .table-container{overflow-x:auto}
    .disposisi-list{min-width:1100px}
    .disposisi-list-header,.disposisi-list-item{display:grid;grid-template-columns:1.5fr 2.5fr 2fr 1.2fr 1.2fr 1fr 1fr 1fr;align-items:center;padding:12px 1rem;gap:1rem}
    .disposisi-list-header{font-weight:600;color:#6B7280;font-size:.8rem;border-bottom:2px solid #F3F4F6;text-transform:uppercase}
    .disposisi-list-item{border-bottom:1px solid #F3F4F6;font-size:.9rem}
    .disposisi-list-item:last-child{border-bottom:none}
    .disposisi-list-item div{display:flex;align-items:center;gap:8px}

    .alert-success{padding:1rem;margin-bottom:1.5rem;border-radius:8px;background:#E8F5E9;color:#2E7D32;border:1px solid #A5D6A7}

    .priority-tag,.status-tag{padding:4px 12px;border-radius:999px;font-size:.75rem;font-weight:500;text-align:center}
    .status-pending{background:#FFF3E0;color:#E65100}
    .status-proses,.status-disposisi{background:#E8EAF6;color:#3949AB}
    .status-selesai{background:#E8F5E9;color:#2E7D32}
    .priority-penting{background:#FFEBEE;color:#C62828}
    

    .action-buttons a,.action-buttons button{color:#6B7280;text-decoration:none;padding:8px;width:36px;height:36px;display:inline-grid;place-items:center;border-radius:8px;transition:.2s;background:#F0FDF4;border:1px solid #D1FAE5;cursor:pointer;font-size:1rem}
    .action-buttons a:hover,.action-buttons button:hover{background:#DCFCE7;color:var(--dark-green);border-color:var(--accent-green)}

    /* ===== POP-UP MODAL ===== */
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;justify-content:center;align-items:center;z-index:1000;opacity:0;visibility:hidden;transition:.25s}
    .modal-overlay.open{opacity:1;visibility:visible}
    .modal-content{background:#F1FAF5;border-radius:16px;width:100%;max-width:680px;transform:scale(.96);transition:.25s;display:flex;flex-direction:column;max-height:90vh;overflow:hidden;border:1px solid #E5F6EA}
    .modal-overlay.open .modal-content{transform:scale(1)}
    .modal-header{padding:1.25rem 1.5rem;border-bottom:1px solid #E5F6EA;display:flex;justify-content:space-between;align-items:center}
    .modal-header h2{font-size:1.25rem;font-weight:700}
    .modal-close-btn{background:none;border:0;font-size:1.75rem;cursor:pointer;color:#94A3B8}
    .modal-form{display:flex;flex-direction:column}
    .modal-body{padding:1.25rem 1.5rem;overflow:auto}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .form-group{display:flex;flex-direction:column}
    .form-group.full-width{grid-column:1/-1}
    .modal-form label{font-weight:600;margin-bottom:6px}
    .modal-form input,.modal-form select,.modal-form textarea{padding:12px;border-radius:10px;border:1px solid #D1D5DB;background:#fff}
    .modal-form input:focus,.modal-form select:focus,.modal-form textarea:focus{outline:none;border-color:var(--accent-green);box-shadow:0 0 0 2px rgba(76,175,80,.18)}
    .modal-footer{padding:1rem 1.5rem;border-top:1px solid #E5F6EA;background:#F7FBF9;display:flex;justify-content:flex-end;gap:.75rem}
    .btn-secondary{background:#E5E7EB;color:#374151;border:0;border-radius:10px;padding:10px 20px;font-weight:600}
    .btn-primary{background:var(--dark-green);color:#fff;border:0;border-radius:10px;padding:10px 20px;font-weight:600}
</style>

<div class="page-title mb-4">
    <h2 class="text-2xl font-bold">Disposisi</h2>
    <p class="text-gray-600">Kelola dan teruskan surat untuk ditindaklanjuti</p>
</div>

@if(session('success'))
  <div class="alert-success" role="alert">
    {{ session('success') }}
  </div>
@endif

{{-- ===================== TABEL 1: BELUM DIDISPOSISIKAN ===================== --}}
<div class="main-card mb-6">
  <div class="card-header">
    <h3 class="font-semibold"><i class="fa-solid fa-inbox"></i> Belum Didisposisikan</h3>
    <p class="text-sm text-gray-500">Total {{ $belumDisposisi->count() }} surat</p>
  </div>

  <div class="table-container">
    <div class="disposisi-list">
      <div class="disposisi-list-header">
        <span>Nomor Surat</span>
        <span>Perihal</span>
        <span>Tujuan</span>
        <span>Tanggal</span>
        <span>Batas Waktu</span>
        <span>Prioritas</span>
        <span>Status</span>
        <span>Aksi</span>
      </div>

      @forelse ($belumDisposisi as $surat)
        <div class="disposisi-list-item">
          <div>{{ $surat->nomor_surat }}</div>
          <div>{{ \Illuminate\Support\Str::limit($surat->perihal, 45) }}</div>
          <div><i class="fa-regular fa-user"></i> Belum Diteruskan</div>
          <div><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</div>
          <div><i class="fa-regular fa-calendar-check"></i> -</div>
          <div><span class="priority-tag priority-{{ strtolower($surat->prioritas) }}">{{ $surat->prioritas }}</span></div>
          <div><span class="status-tag status-{{ strtolower($surat->status) }}">{{ ucfirst($surat->status) }}</span></div>
          <div class="action-buttons">
            <button
              class="disposisi-btn"
              title="Buat Disposisi"
              data-id="{{ $surat->id }}"
              data-nomor="{{ $surat->nomor_surat }}"
              data-tanggal=""
              data-tujuan=""
              data-batas=""
              data-prioritas="{{ $surat->prioritas }}"
              data-status=""
              data-catatan=""
            >
              <i class="fa-regular fa-pen-to-square"></i>
            </button>
          </div>
        </div>
      @empty
        <div class="text-center p-8 text-gray-500" style="grid-column:1/-1;">
          Semua surat sudah didisposisikan. 🎉
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ===================== TABEL 2: SUDAH DIDISPOSISIKAN ===================== --}}
<div class="main-card">
  <div class="card-header">
    <h3 class="font-semibold"><i class="fa-solid fa-list-check"></i> Sudah Didisposisikan</h3>
    <p class="text-sm text-gray-500">Total {{ $sudahDisposisi->count() }} surat</p>
  </div>

  <div class="table-container">
    <div class="disposisi-list">
      <div class="disposisi-list-header">
        <span>Nomor Surat</span>
        <span>Perihal</span>
        <span>Tujuan</span>
        <span>Tanggal</span>
        <span>Batas Waktu</span>
        <span>Prioritas</span>
        <span>Status</span>
        <span>Aksi</span>
      </div>

      @forelse ($sudahDisposisi as $surat)
        @php $d = $surat->disposisi; @endphp
        <div class="disposisi-list-item">
          <div>{{ $surat->nomor_surat }}</div>
          <div>{{ \Illuminate\Support\Str::limit($surat->perihal, 45) }}</div>
          <div><i class="fa-regular fa-user"></i> {{ $d->tujuan }}</div>
          <div>
            <i class="fa-regular fa-calendar"></i>
            {{ $d->tanggal_disposisi ? \Carbon\Carbon::parse($d->tanggal_disposisi)->format('d/m/Y') : \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}
          </div>
          <div>
            <i class="fa-regular fa-calendar-check"></i>
            {{ $d->batas_waktu ? \Carbon\Carbon::parse($d->batas_waktu)->format('d/m/Y') : '-' }}
          </div>
          <div>
            @php $prio = $d->prioritas ?? $surat->prioritas; @endphp
            <span class="priority-tag priority-{{ strtolower($prio) }}">{{ $prio }}</span>
          </div>
          <div>
            @php $st = $d->status ?? $surat->status; @endphp
            <span class="status-tag status-{{ strtolower($st) }}">{{ ucfirst($st) }}</span>
          </div>
          <div class="action-buttons">
            <button
              class="disposisi-btn"
              title="Edit Disposisi"
              data-id="{{ $surat->id }}"
              data-nomor="{{ $surat->nomor_surat }}"
              data-tanggal="{{ $d->tanggal_disposisi }}"
              data-tujuan="{{ $d->tujuan }}"
              data-batas="{{ $d->batas_waktu }}"
              data-prioritas="{{ $d->prioritas ?? $surat->prioritas }}"
              data-status="{{ $d->status }}"
              data-catatan="{{ $d->catatan }}"
            >
              <i class="fa-regular fa-pen-to-square"></i>
            </button>
          </div>
        </div>
      @empty
        <div class="text-center p-8 text-gray-500" style="grid-column:1/-1;">
          Belum ada surat yang didisposisikan.
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ===================== POP-UP MODAL ===================== --}}
<div id="disposisi-modal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <div>
        <h2>Buat Disposisi Baru</h2>
        <p class="text-gray-600">Isi form berikut untuk membuat disposisi surat</p>
      </div>
      <button class="modal-close-btn" type="button">&times;</button>
    </div>

    <form id="disposisi-form" action="{{ route('admin.disposisi.store') }}" method="POST" class="modal-form">
      @csrf
      <input type="hidden" id="disposisi_surat_masuk_id" name="surat_masuk_id">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label for="nomor_surat_display">Nomor Surat</label>
            <input type="text" id="nomor_surat_display" disabled placeholder="Pilih surat" style="background:#fff">
          </div>
          <div class="form-group">
            <label for="tanggal_disposisi">Tanggal Disposisi</label>
            <input type="date" id="tanggal_disposisi" name="tanggal_disposisi" required>
          </div>
          <div class="form-group">
            <label for="tujuan">Tujuan Disposisi</label>
            <select id="tujuan" name="tujuan" required>
              <option value="" selected disabled>Pilih penerima</option>
              <option value="Kepala Bidang Perizinan">Kepala Bidang Perizinan</option>
              <option value="Kepala Bidang Pengelolaan Sampah">Kepala Bidang Pengelolaan Sampah</option>
              <option value="Kepala Bidang Monitoring">Kepala Bidang Monitoring</option>
            </select>
          </div>
          <div class="form-group">
            <label for="batas_waktu">Batas Waktu</label>
            <input type="date" id="batas_waktu" name="batas_waktu">
          </div>
          <div class="form-group">
            <label for="prioritas_disposisi">Prioritas</label>
            <select id="prioritas_disposisi" name="prioritas_disposisi" required>
              <option value="" selected disabled>Pilih prioritas</option>
              <option value="Penting">Penting</option>
            </select>
          </div>
          <div class="form-group">
            <label for="status_disposisi">Status</label>
            <select id="status_disposisi" name="status_disposisi" required>
              <option value="" selected disabled>Pilih status</option>
              <option value="Proses">Proses</option>
              <option value="Selesai">Selesai</option>
            </select>
          </div>
          <div class="form-group full-width">
            <label for="catatan">Instruksi Disposisi</label>
            <textarea id="catatan" name="catatan" placeholder="Instruksi atau catatan untuk penerima disposisi..."></textarea>
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
  const disposisiModal = document.getElementById('disposisi-modal');
  const buttons        = document.querySelectorAll('.disposisi-btn');

  // util: format ke YYYY-MM-DD
  const fmt = (d) => {
    const yyyy = d.getFullYear();
    const mm   = String(d.getMonth()+1).padStart(2,'0');
    const dd   = String(d.getDate()).padStart(2,'0');
    return `${yyyy}-${mm}-${dd}`;
  };

  // util: tambah tahun dengan handling 29 Feb
  const addYearsSafe = (dateStr, years) => {
    const [y,m,d] = dateStr.split('-').map(n => parseInt(n,10));
    const base = new Date(y, m-1, d);
    const targetY = y + years;
    // coba tanggal sama
    let cand = new Date(targetY, m-1, d);
    // kalau overflow (contoh 29 Feb), fallback ke last day of month
    if (cand.getMonth() !== (m-1)) {
      // set ke hari 0 bulan berikutnya = last day bulan target
      cand = new Date(targetY, m, 0);
    }
    return fmt(cand);
  };

  // set batas_waktu = tanggal_disposisi + 5 tahun
  const syncBatasWaktu = () => {
    const tgl = document.getElementById('tanggal_disposisi').value;
    if (tgl) {
      document.getElementById('batas_waktu').value = addYearsSafe(tgl, 5);
      // optional: jaga-jaga batas_waktu minimal = tanggal_disposisi
      document.getElementById('batas_waktu').min = tgl;
    }
  };

  // buka modal + prefill dari data-attribute tombol
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const d = btn.dataset;

      document.getElementById('disposisi_surat_masuk_id').value = d.id || '';
      document.getElementById('nomor_surat_display').value      = d.nomor || '';

      // default-in tanggal_disposisi = hari ini kalau kosong
      const today = new Date();
      const defTanggal = d.tanggal && d.tanggal !== 'null' ? d.tanggal : fmt(today);
      document.getElementById('tanggal_disposisi').value = defTanggal;

      document.getElementById('tujuan').value              = d.tujuan || '';
      document.getElementById('prioritas_disposisi').value = d.prioritas || '';

      // status bisa tersimpan lowercase → samain dengan option (Proses/Selesai)
      const st = d.status ? (d.status.charAt(0).toUpperCase()+d.status.slice(1)) : '';
      document.getElementById('status_disposisi').value = st;

      document.getElementById('catatan').value = d.catatan || '';

      // kalau sudah ada batas_waktu dari data, pakai itu; kalau kosong, auto +5 tahun
      const batasInput = document.getElementById('batas_waktu');
      if (d.batas && d.batas !== 'null' && d.batas !== '') {
        batasInput.value = d.batas;
      } else {
        syncBatasWaktu();
      }

      disposisiModal.classList.add('open');
    });
  });

  // re-calc kalau user ganti tanggal_disposisi
  document.getElementById('tanggal_disposisi').addEventListener('change', syncBatasWaktu);

  // close actions
  const allModals = document.querySelectorAll('.modal-overlay');
  allModals.forEach(modal => {
    modal.querySelector('.modal-close-btn')?.addEventListener('click', () => modal.classList.remove('open'));
    modal.querySelector('.modal-cancel-btn')?.addEventListener('click', () => modal.classList.remove('open'));
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('open'); });
  });
});
</script>

@endsection

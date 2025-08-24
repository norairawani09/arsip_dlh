@extends('layouts.app')

@section('content')
<style>
  .cards{display:grid;gap:16px}
  @media(min-width:768px){.cards{grid-template-columns:repeat(2,1fr)}}
  @media(min-width:1024px){.cards{grid-template-columns:repeat(4,1fr)}}
  .card{background:#fff;border:1px solid #E5E7EB;border-radius:14px;padding:16px;box-shadow:0 4px 14px rgba(0,0,0,.04)}
  .card h3{font-weight:700;font-size:1rem;margin:0;display:flex;align-items:center;gap:8px}
  .card .val{font-size:2rem;font-weight:800;margin-top:6px}
  .sub{color:#6B7280;font-size:.9rem;margin-top:4px;line-height:1.4}
  .tag-warn{color:#C2410C}
  .tag-info{color:#2563EB}
  .tag-ok{color:#16A34A}

  /* tambahan untuk table */
  .dash-table{width:100%;border-collapse:collapse;margin-top:2rem;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .dash-table th,.dash-table td{padding:10px 14px;border-bottom:1px solid #E5E7EB;font-size:.9rem}
  .dash-table th{background:#F9FAFB;text-align:left;font-weight:600;color:#374151}
  .dash-table tr:last-child td{border-bottom:none}
</style>

<div class="cards">
  <div class="card">
    <h3><i class="fa-regular fa-file-lines"></i> Surat Masuk</h3>
    <div class="val">{{ $totalSuratMasuk }}</div>
    <div class="sub">Bulan ini: {{ $bulanIniSuratMasuk }}<br>
      <span class="tag-warn">Pending: {{ $pendingSuratMasuk }}</span>
    </div>
  </div>

  <div class="card">
    <h3><i class="fa-regular fa-paper-plane"></i> Surat Keluar</h3>
    <div class="val">{{ $totalSuratKeluar }}</div>
    <div class="sub">Bulan ini: {{ $bulanIniSuratKeluar }}<br>
      <span class="tag-info">Draft: {{ $draftSuratKeluar }}</span>
    </div>
  </div>

  <div class="card"> 
    <h3><i class="fa-solid fa-users"></i> Pengguna Sistem</h3>
    <div class="val">{{ $totalUser }}</div>
    <div class="sub">
      <span class="tag-ok">Aktif: {{ $userAktif }}</span><br>
      Admin: {{ $userAdmin }}
    </div>
  </div>

  <div class="card"> 
    <h3><i class="fa-solid fa-box-archive"></i> Total Arsip</h3>
    <div class="val">{{ $totalArsip }}</div>
    <div class="sub">Dokumen tersimpan</div>
  </div>
</div>

{{-- ===== Tabel Surat Masuk Hari Ini ===== --}}
<h2 style="margin-top:2rem;font-weight:700;font-size:1.2rem">Surat Masuk Hari Ini ({{ $jmMasukHariIni }})</h2>
<table class="dash-table">
  <thead>
    <tr>
      <th>No</th>
      <th>Nomor Surat</th>
      <th>Pengirim</th>
      <th>Perihal</th>
      <th>Tanggal</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($todaySuratMasuk as $i => $sm)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $sm->nomor_surat }}</td>
        <td>{{ $sm->pengirim }}</td>
        <td>{{ $sm->perihal }}</td>
        <td>{{ $sm->created_at->format('d/m/Y H:i') }}</td>
        <td>{{ ucfirst($sm->status) }}</td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align:center">Tidak ada surat masuk hari ini</td></tr>
    @endforelse
  </tbody>
</table>

{{-- ===== Tabel Surat Keluar Hari Ini ===== --}}
<h2 style="margin-top:2rem;font-weight:700;font-size:1.2rem">Surat Keluar Hari Ini ({{ $jmKeluarHariIni }})</h2>
<table class="dash-table">
  <thead>
    <tr>
      <th>No</th>
      <th>Nomor Surat</th>
      <th>Tujuan</th>
      <th>Perihal</th>
      <th>Tanggal</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($todaySuratKeluar as $i => $sk)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $sk->nomor_surat }}</td>
        <td>{{ $sk->tujuan }}</td>
        <td>{{ $sk->perihal }}</td>
        <td>{{ $sk->created_at->format('d/m/Y H:i') }}</td>
        <td>{{ ucfirst($sk->status) }}</td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align:center">Tidak ada surat keluar hari ini</td></tr>
    @endforelse
  </tbody>
</table>
@endsection

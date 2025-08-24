@extends('layouts.app')

@section('content')
@php
  $user = auth()->user();
  $name = trim($user->name ?? '');
  $first = $name !== '' ? explode(' ', $name)[0] : 'User';
@endphp

<style>
  .hero{
    background: linear-gradient(180deg, #ffffff, #f6faf6);
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 18px;
    display:flex;justify-content:space-between;align-items:center;gap:16px
  }
  .hero h2{font-size:1.35rem;line-height:1.2;margin:0}
  .hero h2 span{color:#2E7D32}
  .hero p{margin-top:6px;color:#6B7280}

  .cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:14px;
  }
  .card{
    background:#fff;border:1px solid #E5E7EB;border-radius:16px;
    padding:18px; text-decoration:none; color:#111827;
    box-shadow:0 6px 22px rgba(0,0,0,.06);
    display:flex; flex-direction:column; gap:10px; transition:transform .15s ease, box-shadow .15s ease;
  }
  .card:hover{ transform:translateY(-2px); box-shadow:0 10px 28px rgba(0,0,0,.08); }
  .icon-badge{
    width:40px;height:40px;border-radius:12px;display:grid;place-items:center;
    background:#ECFDF5;border:1px solid #D1FAE5;
  }
  .icon-badge i{ font-size:1.1rem; color:#10B981; }
  .count{ font-size:2rem; font-weight:800; line-height:1; }
  .label{ color:#6B7280; margin-top:-4px; }
  .link{ margin-top:8px; font-weight:600; color:#2E7D32; }
</style>

<div class="hero">
  <div>
    <h2>Halo, <span>{{ $first }}</span> 👋</h2>
    <p>Selamat datang kembali di <strong>SIPEN-DLH</strong>. Akses cepat ke surat kamu ada di bawah.</p>
  </div>
</div>

<div class="cards">
  <a href="{{ route('admin.surat-masuk.index') }}" class="card">
    <div class="icon-badge"><i class="fa-solid fa-inbox"></i></div>
    <div class="count">{{ $jumlahSuratMasuk ?? 0 }}</div>
    <div class="label">Surat Masuk</div>
    <div class="link">Lihat semua →</div>
  </a>

  <a href="{{ route('admin.surat-keluar.index') }}" class="card">
    <div class="icon-badge"><i class="fa-solid fa-paper-plane"></i></div>
    <div class="count">{{ $jumlahSuratKeluar ?? 0 }}</div>
    <div class="label">Surat Keluar</div>
    <div class="link">Lihat semua →</div>
  </a>

  <a href="{{ route('admin.disposisi.index') }}" class="card">
    <div class="icon-badge"><i class="fa-solid fa-file-signature"></i></div>
    <div class="count">{{ $jumlahDisposisi ?? 0 }}</div>
    <div class="label">Disposisi</div>
    <div class="link">Lihat semua →</div>
  </a>
</div>
@endsection

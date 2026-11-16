<div class="sidebar-heading">Menu Utama</div>

<a href="{{ route('pensiunan.dashboard') }}"
   class="sidebar-link {{ request()->routeIs('pensiunan.dashboard') && !request()->has('tab') ? 'active' : '' }}">
    <i class="bi bi-speedometer2" aria-hidden="true"></i>
    <span>Dashboard Utama</span>
</a>

<a href="{{ route('pensiunan.dashboard') }}#profil-ringkasan"
   class="sidebar-link">
    <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
    <span>Profil Pensiunan</span>
</a>

<a href="{{ route('pensiunan.dashboard') }}#riwayat-transaksi"
   class="sidebar-link">
    <i class="bi bi-receipt" aria-hidden="true"></i>
    <span>Riwayat Pembayaran</span>
</a>

<a href="{{ route('pensiunan.dashboard') }}#informasi-rekening"
   class="sidebar-link">
    <i class="bi bi-bank" aria-hidden="true"></i>
    <span>Rekening Terdaftar</span>
</a>

<div class="sidebar-heading">Pemberitahuan</div>

@php
    $unreadPensiunanNotif = Auth::check() ? Auth::user()->notifikasi()->where('sudah_dibaca', false)->count() : 0;
@endphp
<a href="{{ route('notifikasi.index') }}"
   class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-bell-fill" aria-hidden="true"></i>
        <span>Notifikasi</span>
    </div>
    @if($unreadPensiunanNotif > 0)
        <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem; font-weight: 700;">
            {{ $unreadPensiunanNotif }}
        </span>
    @endif
</a>

<div class="sidebar-heading">Akun</div>

<form method="POST" action="{{ route('logout') }}" class="m-0">
    @csrf
    <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent" style="cursor: pointer;">
        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
        <span>Keluar Sistem</span>
    </button>
</form>

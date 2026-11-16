<div class="sidebar-heading">Menu Utama</div>

<a href="{{ route('keuangan.dashboard') }}"
   class="sidebar-link {{ request()->routeIs('keuangan.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2" aria-hidden="true"></i>
    <span>Dashboard</span>
</a>

<div class="sidebar-heading">Transaksi & Penyaluran</div>

<a href="{{ route('keuangan.pembayaran.index') }}"
   class="sidebar-link {{ request()->routeIs('keuangan.pembayaran.index', 'keuangan.pembayaran.show') ? 'active' : '' }} d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-credit-card-2-front-fill" aria-hidden="true"></i>
        <span>Daftar Pembayaran</span>
    </div>
    @php
        $pendingPembayaranCount = \App\Models\Pembayaran::where('status', 'menunggu')->count();
    @endphp
    @if($pendingPembayaranCount > 0)
        <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.7rem; font-weight: 700;">
            {{ $pendingPembayaranCount }}
        </span>
    @endif
</a>

<a href="{{ route('keuangan.pembayaran.create') }}"
   class="sidebar-link {{ request()->routeIs('keuangan.pembayaran.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle-fill" aria-hidden="true"></i>
    <span>Buat Pembayaran</span>
</a>

<a href="{{ route('keuangan.rekening.index') }}"
   class="sidebar-link {{ request()->routeIs('keuangan.rekening.*') ? 'active' : '' }}">
    <i class="bi bi-bank2" aria-hidden="true"></i>
    <span>Rekening Pensiunan</span>
</a>

<div class="sidebar-heading">Laporan & Analitik</div>

<a href="{{ route('keuangan.laporan.index') }}"
   class="sidebar-link {{ request()->routeIs('keuangan.laporan.*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-spreadsheet-fill" aria-hidden="true"></i>
    <span>Laporan Keuangan</span>
</a>

<div class="sidebar-heading">Pemberitahuan</div>

@php
    $unreadKeuanganNotif = Auth::check() ? Auth::user()->notifikasi()->where('sudah_dibaca', false)->count() : 0;
@endphp
<a href="{{ route('notifikasi.index') }}"
   class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-bell-fill" aria-hidden="true"></i>
        <span>Pusat Notifikasi</span>
    </div>
    @if($unreadKeuanganNotif > 0)
        <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem; font-weight: 700;">
            {{ $unreadKeuanganNotif }}
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

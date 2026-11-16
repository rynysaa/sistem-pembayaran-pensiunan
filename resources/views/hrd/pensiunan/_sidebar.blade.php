<div class="sidebar-heading">Menu Utama</div>

<a href="{{ route('hrd.dashboard') }}"
   class="sidebar-link {{ request()->routeIs('hrd.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2" aria-hidden="true"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('hrd.pensiunan.index') }}"
   class="sidebar-link {{ request()->routeIs('hrd.pensiunan.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill" aria-hidden="true"></i>
    <span>Data Pensiunan</span>
</a>

<a href="{{ route('hrd.rekening.index') }}"
   class="sidebar-link {{ request()->routeIs('hrd.rekening.*') ? 'active' : '' }}">
    <i class="bi bi-credit-card-2-front-fill" aria-hidden="true"></i>
    <span>Rekening Pensiunan</span>
</a>

<div class="sidebar-heading">Verifikasi & Berkas</div>

<a href="{{ route('hrd.verifikasi.index') }}"
   class="sidebar-link {{ request()->routeIs('hrd.verifikasi.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-clipboard2-check-fill" aria-hidden="true"></i>
        <span>Verifikasi Data</span>
    </div>
    @php
        $pendingVerifikasiCount = \App\Models\VerifikasiData::where('status', 'menunggu')->count();
    @endphp
    @if($pendingVerifikasiCount > 0)
        <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.7rem; font-weight: 700;">
            {{ $pendingVerifikasiCount }}
        </span>
    @endif
</a>

<div class="sidebar-heading">Pemberitahuan</div>

@php
    $unreadHrdNotif = Auth::check() ? Auth::user()->notifikasi()->where('sudah_dibaca', false)->count() : 0;
@endphp
<a href="{{ route('notifikasi.index') }}"
   class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-bell-fill" aria-hidden="true"></i>
        <span>Pusat Notifikasi</span>
    </div>
    @if($unreadHrdNotif > 0)
        <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem; font-weight: 700;">
            {{ $unreadHrdNotif }}
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

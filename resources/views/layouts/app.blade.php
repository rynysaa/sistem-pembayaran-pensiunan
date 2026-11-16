<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pembayaran Pensiunan') - PT Jasa Marga</title>

    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Corporate Banking Design System --}}
    <link href="{{ asset('css/corporate-banking.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

{{-- ── Top Navigation Bar ── --}}
<nav class="navbar navbar-corporate fixed-top px-3">
    <div class="d-flex align-items-center gap-3">
        {{-- Mobile Hamburger Trigger (R-03 & R-39) --}}
        <button type="button"
                class="navbar-btn-icon d-lg-none"
                id="sidebarToggleBtn"
                aria-label="Buka navigasi menu"
                aria-expanded="false"
                aria-controls="sidebarNav">
            <i class="bi bi-list fs-5" aria-hidden="true"></i>
        </button>

        {{-- Brand Emblem & Title --}}
        <a href="{{ Auth::user()->isHrd() ? route('hrd.dashboard') : (Auth::user()->isKeuangan() ? route('keuangan.dashboard') : route('pensiunan.dashboard')) }}"
           class="d-flex align-items-center gap-2 text-decoration-none"
           title="Kembali ke Dashboard">
            <div class="brand-badge" aria-hidden="true">JM</div>
            <div class="d-none d-sm-block">
                <div class="brand-text-title">Sistem Pembayaran Pensiunan</div>
                <div class="brand-text-sub">PT Jasa Marga (Persero) Tbk</div>
            </div>
        </a>
    </div>

    <div class="d-flex align-items-center gap-2 gap-sm-3 ms-auto">
        {{-- Notifikasi Badge --}}
        @php
            $unreadNavbarNotifCount = Auth::check() ? Auth::user()->notifikasi()->where('sudah_dibaca', false)->count() : 0;
        @endphp
        <a href="{{ route('notifikasi.index') }}"
           class="navbar-btn-icon position-relative"
           title="Notifikasi Anda"
           aria-label="Notifikasi: {{ $unreadNavbarNotifCount }} belum dibaca">
            <i class="bi bi-bell-fill" aria-hidden="true"></i>
            @if($unreadNavbarNotifCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size: 0.65rem; padding: 0.25rem 0.45rem;">
                    {{ $unreadNavbarNotifCount > 99 ? '99+' : $unreadNavbarNotifCount }}
                </span>
            @endif
        </a>

        {{-- Profil Singkat --}}
        <div class="text-end d-none d-md-block">
            <div class="navbar-user-name">{{ Auth::user()->name }}</div>
            <span class="role-pill role-pill-{{ Auth::user()->role }}">
                {{ ucfirst(Auth::user()->role) }}
            </span>
        </div>

        {{-- Logout Action --}}
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit"
                    class="btn btn-sm btn-outline-light d-flex align-items-center gap-1 px-2 px-sm-3"
                    style="border-color: rgba(255,255,255,0.2); font-size: 0.8rem;"
                    title="Keluar dari sistem"
                    aria-label="Keluar dari akun">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Keluar</span>
            </button>
        </form>
    </div>
</nav>

{{-- ── Mobile Sidebar Backdrop ── --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

{{-- ── Sidebar Navigation ── --}}
<aside class="sidebar-corporate" id="sidebarNav" aria-label="Navigasi Utama">
    <div class="d-flex align-items-center justify-content-between d-lg-none mb-3 pb-2 border-bottom border-secondary border-opacity-25 px-2">
        <span class="text-white fw-bold small">Menu Navigasi</span>
        <button type="button" class="btn btn-sm btn-link text-white text-decoration-none p-0" id="sidebarCloseBtn" aria-label="Tutup navigasi">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>
    @yield('sidebar')
</aside>

{{-- ── Main Content Container ── --}}
<main class="main-wrapper" id="mainContent">

    {{-- System Flash Feedback --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2" aria-hidden="true"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2" aria-hidden="true"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
        </div>
    @endif

    @yield('content')
</main>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Responsive Mobile Sidebar Toggle
    (function() {
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.getElementById('sidebarNav');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            if (!sidebar) return;
            sidebar.classList.add('show');
            if (backdrop) backdrop.classList.add('show');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (!sidebar) return;
            sidebar.classList.remove('show');
            if (backdrop) backdrop.classList.remove('show');
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.focus();
            }
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);

        // Keyboard Accessibility (R-32): Close drawer on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                closeSidebar();
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>

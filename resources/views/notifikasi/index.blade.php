@extends('layouts.app')

@section('title', 'Pusat Notifikasi & Pemberitahuan')

@section('sidebar')
    @if(Auth::user()->isHrd())
        @include('hrd.pensiunan._sidebar')
    @elseif(Auth::user()->isKeuangan())
        @include('keuangan._sidebar')
    @else
        @include('pensiunan._sidebar')
    @endif
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-{{ Auth::user()->role }}">Pemberitahuan Sistem</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Pusat Notifikasi &amp; Pemberitahuan</h1>
        <p class="corporate-header-sub">
            Pemberitahuan real-time terkait verifikasi berkas kepegawaian, transaksi pembayaran, dan pengumuman resmi.
        </p>
    </div>
    @if($totalBelumDibaca > 0)
        <form method="POST" action="{{ route('notifikasi.mark-all-read') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-jm-gold btn-sm">
                <i class="bi bi-check2-all" aria-hidden="true"></i>
                <span>Tandai Semua Sudah Dibaca</span>
            </button>
        </form>
    @endif
</div>

{{-- Mini Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--jm-blue-light); color: var(--jm-blue);">
                    <i class="bi bi-bell-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark" style="font-variant-numeric: tabular-nums;">{{ $totalSemua }}</div>
                    <div class="small text-muted">Total Notifikasi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3" style="border-left: 3px solid #DC2626;">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-danger-bg); color: var(--status-danger-text);">
                    <i class="bi bi-envelope-exclamation" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger" style="font-variant-numeric: tabular-nums;">{{ $totalBelumDibaca }}</div>
                    <div class="small text-muted">Belum Dibaca</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3" style="border-left: 3px solid #10B981;">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                    <i class="bi bi-envelope-check" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">{{ $totalSudahDibaca }}</div>
                    <div class="small text-muted">Sudah Dibaca</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabs & List Notifikasi --}}
<div class="corporate-card">
    <div class="corporate-card-header p-0 px-3 bg-white border-bottom">
        <ul class="nav nav-tabs border-0" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'semua' ? 'active' : '' }}"
                   href="{{ route('notifikasi.index', ['tab' => 'semua']) }}">
                    Semua Notifikasi ({{ $totalSemua }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'belum_dibaca' ? 'active' : '' }}"
                   href="{{ route('notifikasi.index', ['tab' => 'belum_dibaca']) }}">
                    Belum Dibaca ({{ $totalBelumDibaca }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'sudah_dibaca' ? 'active' : '' }}"
                   href="{{ route('notifikasi.index', ['tab' => 'sudah_dibaca']) }}">
                    Sudah Dibaca ({{ $totalSudahDibaca }})
                </a>
            </li>
        </ul>
    </div>

    <div class="corporate-card-body p-0">
        @if($notifikasi->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifikasi as $item)
                    <div class="list-group-item p-3 px-4 border-bottom d-flex align-items-start gap-3 {{ ! $item->sudah_dibaca ? 'bg-light' : '' }}"
                         style="{{ ! $item->sudah_dibaca ? 'border-left: 3px solid var(--jm-blue) !important;' : '' }}">
                        <div class="fs-4 mt-1 text-primary">
                            <i class="bi {{ $item->icon }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $item->badge_class }} rounded-pill small">
                                        {{ ucfirst($item->jenis) }}
                                    </span>
                                    <h6 class="mb-0 fw-bold {{ ! $item->sudah_dibaca ? 'text-primary' : 'text-dark' }}">
                                        {{ $item->judul }}
                                    </h6>
                                    @if(! $item->sudah_dibaca)
                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">Baru</span>
                                    @endif
                                </div>
                                <span class="small text-muted font-monospace">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mb-2 text-dark small" style="line-height: 1.5;">
                                {{ Str::limit($item->pesan, 180) }}
                            </p>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('notifikasi.show', $item) }}" class="btn btn-jm-primary btn-sm py-0 px-2" style="font-size: 0.78rem;">
                                    <span>Buka Pesan</span>
                                    <i class="bi bi-chevron-right small" aria-hidden="true"></i>
                                </a>

                                @if(! $item->sudah_dibaca)
                                    <form method="POST" action="{{ route('notifikasi.mark-as-read', $item) }}" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-jm-outline btn-sm py-0 px-2" style="font-size: 0.78rem;">
                                            <i class="bi bi-check2" aria-hidden="true"></i>
                                            <span>Tandai Dibaca</span>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('notifikasi.destroy', $item) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?');"
                                      class="m-0 ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none" title="Hapus notifikasi">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state py-5">
                <div class="empty-state-icon" aria-hidden="true"><i class="bi bi-bell-slash"></i></div>
                <div class="empty-state-title">Tidak Ada Notifikasi</div>
                <p class="empty-state-text">
                    Tidak ada pemberitahuan pada kategori yang dipilih saat ini.
                </p>
            </div>
        @endif
    </div>

    @if($notifikasi->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white">
            {{ $notifikasi->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

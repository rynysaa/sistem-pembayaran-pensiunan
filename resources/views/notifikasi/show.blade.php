@extends('layouts.app')

@section('title', 'Detail Notifikasi - ' . $notifikasi->judul)

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
            <span class="role-pill role-pill-{{ Auth::user()->role }}">Pesan Resmi</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Detail Pemberitahuan</h1>
        <p class="corporate-header-sub">
            Rincian informasi instruksi, status transaksi, atau pengumuman dari sistem.
        </p>
    </div>
    <a href="{{ route('notifikasi.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        <span>Kembali ke Pusat Notifikasi</span>
    </a>
</div>

{{-- Memo Detail Card --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-body p-4 p-md-5">
        {{-- Memo Header --}}
        <div class="d-flex align-items-start gap-3 pb-4 border-bottom">
            <div class="metric-icon-box" style="width: 52px; height: 52px; font-size: 1.6rem; background-color: var(--jm-blue-light); color: var(--jm-blue);">
                <i class="bi {{ $notifikasi->icon }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge {{ $notifikasi->badge_class }} rounded-pill small">
                        Kategori: {{ ucfirst($notifikasi->jenis) }}
                    </span>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 small">
                        <i class="bi bi-clock me-1"></i>{{ $notifikasi->created_at->translatedFormat('l, d F Y - H:i') }} WIB
                    </span>
                    <span class="badge-status badge-status-success">
                        <i class="bi bi-check2-all me-1"></i>Telah Dibaca
                    </span>
                </div>
                <h3 class="fw-bold mb-0 text-dark fs-4">{{ $notifikasi->judul }}</h3>
            </div>
        </div>

        {{-- Memo Body --}}
        <div class="py-4">
            <span class="metric-label d-block mb-2">Isi Pesan Resmi:</span>
            <div class="p-4 rounded-3 text-dark fs-6" style="background-color: #F8FAFC; border: 1px solid var(--jm-border); line-height: 1.7; white-space: pre-line;">
                {{ $notifikasi->pesan }}
            </div>
        </div>

        {{-- Memo Metadata --}}
        <div class="row g-3 py-3 border-top text-muted small">
            <div class="col-sm-6">
                <i class="bi bi-calendar-event me-1"></i> Diterima pada:
                <strong>{{ $notifikasi->created_at->translatedFormat('d F Y, H:i:s') }} WIB</strong>
            </div>
            <div class="col-sm-6 text-sm-end">
                <i class="bi bi-envelope-open me-1"></i> Waktu dibaca:
                <strong>{{ $notifikasi->dibaca_pada ? $notifikasi->dibaca_pada->translatedFormat('d F Y, H:i:s') . ' WIB' : 'Baru saja' }}</strong>
            </div>
        </div>

        {{-- Actions Footer --}}
        <div class="d-flex align-items-center justify-content-between pt-4 border-top flex-wrap gap-2">
            <div>
                @if(Auth::user()->isPensiunan())
                    <a href="{{ route('pensiunan.dashboard') }}" class="btn btn-jm-primary btn-sm px-3">
                        <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>
                        <span>Buka Dashboard Saya</span>
                    </a>
                @elseif(Auth::user()->isHrd())
                    <a href="{{ route('hrd.verifikasi.index') }}" class="btn btn-jm-primary btn-sm px-3">
                        <i class="bi bi-clipboard2-check me-1" aria-hidden="true"></i>
                        <span>Buka Verifikasi HRD</span>
                    </a>
                @elseif(Auth::user()->isKeuangan())
                    <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-jm-primary btn-sm px-3">
                        <i class="bi bi-credit-card-2-front me-1" aria-hidden="true"></i>
                        <span>Buka Transaksi Pembayaran</span>
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('notifikasi.destroy', $notifikasi) }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?');"
                  class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i>
                    <span>Hapus Notifikasi Ini</span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

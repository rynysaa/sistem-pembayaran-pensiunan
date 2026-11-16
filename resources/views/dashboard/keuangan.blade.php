@extends('layouts.app')

@section('title', 'Dashboard Keuangan')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')
    {{-- Header Banner --}}
    <div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="role-pill role-pill-keuangan">Divisi Keuangan &amp; Perbendaharaan</span>
                <span class="text-white-50 small">|</span>
                <span class="text-white-50 small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="corporate-header-title">Dashboard Pembayaran Pensiun</h1>
            <p class="corporate-header-sub">
                Monitoring transaksi perbankan dan penyaluran manfaat pensiun PT Jasa Marga (Persero) Tbk.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.laporan.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
                <i class="bi bi-file-earmark-spreadsheet" aria-hidden="true"></i>
                <span>Laporan Penyaluran</span>
            </a>
            <a href="{{ route('keuangan.pembayaran.create') }}" class="btn btn-jm-gold btn-sm">
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
                <span>Buat Pembayaran Baru</span>
            </a>
        </div>
    </div>

    {{-- Metrics Row (Financial Hierarchy) --}}
    <div class="row g-3 mb-4">
        {{-- Hero Financial Metric: Total Dana Berhasil Disalurkan --}}
        <div class="col-12 col-lg-5">
            <div class="metric-card-hero">
                <div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Total Dana Berhasil Disalurkan</span>
                        <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 rounded-pill px-2 py-1 small">
                            Terealisasi
                        </span>
                    </div>
                    <div class="metric-value" style="color: #F8FAFC;">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </div>
                    <p class="small text-white-50 mb-3">
                        Total kumulatif transfer dana pensiunan dengan status transaksi sukses.
                    </p>
                </div>

                <div class="pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center small">
                    <span class="text-white-50">Transaksi Berhasil:</span>
                    <span class="text-white fw-bold">{{ number_format($berhasilCount, 0, ',', '.') }} Transaksi</span>
                </div>
            </div>
        </div>

        {{-- Transaction Status Pipeline --}}
        <div class="col-12 col-lg-7">
            <div class="corporate-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <span class="metric-label">Status Antrean Transaksi Penyaluran</span>
                    <span class="small text-muted fw-semibold">Total {{ $totalPembayaran }} Transaksi</span>
                </div>

                <div class="row g-2">
                    {{-- Menunggu --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-2" style="background-color: var(--status-warning-bg); border: 1px solid var(--status-warning-border);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small text-muted fw-semibold">Menunggu</span>
                                <i class="bi bi-clock-history text-warning" aria-hidden="true"></i>
                            </div>
                            <div class="fs-4 fw-bold text-dark">{{ $menungguCount }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">Perlu diverifikasi</div>
                        </div>
                    </div>

                    {{-- Diproses --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-2" style="background-color: var(--status-info-bg); border: 1px solid var(--status-info-border);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small text-muted fw-semibold">Diproses</span>
                                <i class="bi bi-arrow-repeat text-info" aria-hidden="true"></i>
                            </div>
                            <div class="fs-4 fw-bold text-dark">{{ $diprosesCount }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">Dalam kliring bank</div>
                        </div>
                    </div>

                    {{-- Berhasil --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-2" style="background-color: var(--status-success-bg); border: 1px solid var(--status-success-border);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small text-muted fw-semibold">Berhasil</span>
                                <i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i>
                            </div>
                            <div class="fs-4 fw-bold text-dark">{{ $berhasilCount }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">Dana telah masuk</div>
                        </div>
                    </div>

                    {{-- Gagal --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-2" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small text-muted fw-semibold">Gagal</span>
                                <i class="bi bi-x-circle-fill text-danger" aria-hidden="true"></i>
                            </div>
                            <div class="fs-4 fw-bold text-dark">{{ $gagalCount }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">Retur / kendala</div>
                        </div>
                    </div>
                </div>

                {{-- Action shortcut --}}
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('keuangan.pembayaran.index') }}?status=menunggu" class="small fw-semibold text-decoration-none">
                        Tindak lanjuti antrean menunggu ({{ $menungguCount }}) <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Grid: Ringkasan Periode & Transaksi Terbaru --}}
    <div class="row g-4">
        {{-- Ringkasan Penyaluran Berdasarkan Periode --}}
        <div class="col-12 col-lg-5">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-calendar3 text-primary" aria-hidden="true"></i>
                        <span>Realisasi per Periode</span>
                    </h2>
                    <a href="{{ route('keuangan.laporan.index') }}" class="small fw-semibold text-decoration-none">
                        Rekap Detail
                    </a>
                </div>
                <div class="corporate-card-body p-0">
                    @if($ringkasanPeriode->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($ringkasanPeriode as $periode)
                                <div class="list-group-item p-3 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="fw-bold text-dark">
                                            Periode {{ $periode->periode }}
                                        </div>
                                        <div class="fw-bold text-success" style="font-variant-numeric: tabular-nums;">
                                            Rp {{ number_format($periode->nominal_berhasil, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Total: {{ $periode->total_transaksi }} transaksi</span>
                                        <span>
                                            <span class="text-success fw-semibold">{{ $periode->total_berhasil }} sukses</span>
                                            @if($periode->total_menunggu > 0)
                                                | <span class="text-warning fw-semibold">{{ $periode->total_menunggu }} pending</span>
                                            @endif
                                            @if($periode->total_gagal > 0)
                                                | <span class="text-danger fw-semibold">{{ $periode->total_gagal }} gagal</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-4">
                            <p class="empty-state-text">Belum ada riwayat transaksi pada periode berjalan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 5 Transaksi Terbaru --}}
        <div class="col-12 col-lg-7">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-clock-history text-secondary" aria-hidden="true"></i>
                        <span>Transaksi Pembayaran Terbaru</span>
                    </h2>
                    <a href="{{ route('keuangan.pembayaran.index') }}" class="small fw-semibold text-decoration-none">
                        Buka Semua
                    </a>
                </div>
                <div class="corporate-card-body p-0">
                    @if($pembayaranTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table-corporate">
                                <thead>
                                    <tr>
                                        <th>Penerima</th>
                                        <th>Periode</th>
                                        <th class="text-end">Nominal</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaranTerbaru as $bayar)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $bayar->pensiunan->nama }}</div>
                                                <div class="small text-muted font-monospace">{{ $bayar->pensiunan->nip }}</div>
                                            </td>
                                            <td>
                                                <span class="small fw-semibold">{{ $bayar->periode }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-dark" style="font-variant-numeric: tabular-nums;">
                                                Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($bayar->status === 'berhasil')
                                                    <span class="badge-status badge-status-success">Berhasil</span>
                                                @elseif($bayar->status === 'diproses')
                                                    <span class="badge-status badge-status-info">Diproses</span>
                                                @elseif($bayar->status === 'menunggu')
                                                    <span class="badge-status badge-status-warning">Menunggu</span>
                                                @else
                                                    <span class="badge-status badge-status-danger">Gagal</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('keuangan.pembayaran.show', $bayar) }}" class="btn btn-jm-outline btn-sm py-1 px-2">
                                                    <span>Detail</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state py-4">
                            <p class="empty-state-text">Belum ada transaksi pembayaran yang dicatat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

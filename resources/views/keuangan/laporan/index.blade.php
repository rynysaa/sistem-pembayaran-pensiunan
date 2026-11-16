@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Pembayaran Pensiunan')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')

{{-- Print Official Letterhead (Print-only) --}}
<div class="d-none d-print-block mb-4 pb-3 border-bottom border-dark">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3 class="fw-bold mb-0 text-dark">PT JASA MARGA (PERSERO) TBK</h3>
            <div class="text-uppercase small fw-semibold text-secondary">Divisi Keuangan &amp; Manajemen Perbendaharaan</div>
            <div class="small text-muted">Laporan Resmi Rekapitulasi Penyaluran Dana Pensiun</div>
        </div>
        <div class="text-end small">
            <div>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Dokumen Resmi Perusahaan</div>
        </div>
    </div>
</div>

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3 btn-no-print">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-keuangan">Laporan Keuangan</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Laporan Penyaluran Dana Pensiun</h1>
        <p class="corporate-header-sub">
            Rekapitulasi pencairan manfaat pensiun, status kliring perbankan, dan arsip audit transaksi.
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-jm-gold btn-sm" onclick="window.print()" title="Cetak laporan">
            <i class="bi bi-printer" aria-hidden="true"></i>
            <span>Cetak Dokumen Laporan</span>
        </button>
    </div>
</div>

{{-- Filter Periode Card --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('keuangan.laporan.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6 col-lg-4">
                <label for="laporanPeriode" class="form-label-corporate">Filter Periode Transaksi</label>
                <select id="laporanPeriode" name="periode" class="form-select form-select-corporate">
                    <option value="">Semua Periode Penyaluran</option>
                    @foreach($daftarPeriode as $prd)
                        <option value="{{ $prd }}" {{ $periode === $prd ? 'selected' : '' }}>
                            Periode {{ $prd }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-jm-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                    <span>Terapkan Filter</span>
                </button>
                @if($periode)
                    <a href="{{ route('keuangan.laporan.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
                        <i class="bi bi-x" aria-hidden="true"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Kartu Rekapitulasi Realisasi --}}
<div class="row g-3 mb-4">
    {{-- Hero Nominal Berhasil --}}
    <div class="col-12 col-lg-5">
        <div class="metric-card-hero">
            <div>
                <span class="metric-label">Total Realisasi Tersalur</span>
                <div class="metric-value">
                    Rp {{ number_format($nominalBerhasil, 0, ',', '.') }}
                </div>
                <p class="small text-white-50 mb-2">
                    {{ $periode ? "Realisasi transfer periode {$periode}" : 'Total kumulatif seluruh periode berjalan' }}
                </p>
            </div>
            <div class="pt-2 border-top border-white border-opacity-10 d-flex justify-content-between small text-white-50">
                <span>Sukses Diterima:</span>
                <span class="text-white fw-bold">{{ $totalBerhasil }} Transaksi</span>
            </div>
        </div>
    </div>

    {{-- Breakdown Status Pipeline --}}
    <div class="col-12 col-lg-7">
        <div class="corporate-card h-100 p-3">
            <span class="metric-label d-block mb-3">Distribusi Status Transaksi</span>
            <div class="row g-2">
                <div class="col-6 col-sm-3">
                    <div class="p-3 rounded-2" style="background-color: #F8FAFC; border: 1px solid var(--jm-border);">
                        <div class="small text-muted fw-semibold">Total</div>
                        <div class="fs-4 fw-bold text-dark mt-1" style="font-variant-numeric: tabular-nums;">{{ $totalTransaksi }}</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Volume transaksi</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-3 rounded-2" style="background-color: var(--status-success-bg); border: 1px solid var(--status-success-border);">
                        <div class="small text-success fw-semibold">Berhasil</div>
                        <div class="fs-4 fw-bold text-success mt-1" style="font-variant-numeric: tabular-nums;">{{ $totalBerhasil }}</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Selesai transfer</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-3 rounded-2" style="background-color: var(--status-warning-bg); border: 1px solid var(--status-warning-border);">
                        <div class="small text-warning fw-semibold">Menunggu / Proses</div>
                        <div class="fs-4 fw-bold text-warning mt-1" style="font-variant-numeric: tabular-nums;">{{ $totalMenunggu + $totalDiproses }}</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Belum tuntas</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-3 rounded-2" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                        <div class="small text-danger fw-semibold">Gagal</div>
                        <div class="fs-4 fw-bold text-danger mt-1" style="font-variant-numeric: tabular-nums;">{{ $totalGagal }}</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Retur perbankan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table Card --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-table text-primary" aria-hidden="true"></i>
            <span>Arsip Riwayat Pembayaran Pensiunan</span>
        </h2>
        <small class="text-muted">
            Menampilkan {{ $pembayaran->firstItem() ?? 0 }} - {{ $pembayaran->lastItem() ?? 0 }} dari {{ $pembayaran->total() }} transaksi
        </small>
    </div>

    <div class="table-responsive">
        <table class="table-corporate">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>NIP &amp; Nama Pensiunan</th>
                    <th>Rekening Tujuan</th>
                    <th>Periode</th>
                    <th class="text-end">Nominal Manfaat</th>
                    <th>Tanggal Kliring</th>
                    <th class="text-center">Status</th>
                    <th>Petugas Pemroses</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $item)
                    @php
                        $rek = $item->pensiunan->rekening->first();
                    @endphp
                    <tr>
                        <td class="ps-4 text-muted small">{{ $pembayaran->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->pensiunan->nama }}</div>
                            <div class="small text-muted font-monospace">NIP: {{ $item->pensiunan->nip }}</div>
                        </td>
                        <td>
                            @if($rek)
                                <div class="small fw-semibold text-dark">{{ $rek->nama_bank }}</div>
                                <div class="small font-monospace text-muted">{{ $rek->nomor_rekening }}</div>
                            @else
                                <span class="small text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                {{ $item->periode }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark" style="font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="small text-muted">
                                {{ $item->tanggal_pembayaran ? $item->tanggal_pembayaran->translatedFormat('d M Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->status === 'berhasil')
                                <span class="badge-status badge-status-success">Berhasil</span>
                            @elseif($item->status === 'diproses')
                                <span class="badge-status badge-status-info">Diproses</span>
                            @elseif($item->status === 'menunggu')
                                <span class="badge-status badge-status-warning">Menunggu</span>
                            @else
                                <span class="badge-status badge-status-danger">Gagal</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">{{ $item->processor->name ?? '-' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon" aria-hidden="true"><i class="bi bi-file-earmark-spreadsheet"></i></div>
                                <div class="empty-state-title">Tidak Ada Catatan Transaksi</div>
                                <p class="empty-state-text">
                                    Tidak ada data penyaluran dana pensiun yang tercatat untuk filter periode ini.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pembayaran->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white btn-no-print">
            {{ $pembayaran->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Print Signature Footer (Print-only) --}}
<div class="d-none d-print-block mt-5 pt-4">
    <div class="row">
        <div class="col-6 text-center">
            <div class="small text-muted mb-5">Mengetahui,<br>Kepala Divisi Human Capital</div>
            <div class="fw-bold text-dark text-decoration-underline">(...................................................)</div>
            <div class="small text-muted">NIP. .....................................</div>
        </div>
        <div class="col-6 text-center">
            <div class="small text-muted mb-5">Disetujui Oleh,<br>Kepala Divisi Keuangan &amp; Perbendaharaan</div>
            <div class="fw-bold text-dark text-decoration-underline">(...................................................)</div>
            <div class="small text-muted">NIP. .....................................</div>
        </div>
    </div>
</div>

@endsection

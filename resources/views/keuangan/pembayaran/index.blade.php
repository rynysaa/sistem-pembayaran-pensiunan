@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembayaran Pensiunan')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-keuangan">Penyaluran Perbankan</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Daftar Transaksi Pembayaran</h1>
        <p class="corporate-header-sub">
            Monitoring dan eksekusi transfer dana pensiunan kepada penerima manfaat yang telah terverifikasi.
        </p>
    </div>
    <a href="{{ route('keuangan.pembayaran.create') }}" class="btn btn-jm-gold btn-sm">
        <i class="bi bi-plus-circle" aria-hidden="true"></i>
        <span>Terbitkan Pembayaran Baru</span>
    </a>
</div>

{{-- Metric Mini Cards Pipeline --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="small text-muted fw-semibold">Total Transaksi</span>
            </div>
            <div class="fs-4 fw-bold text-dark" style="font-variant-numeric: tabular-nums;">{{ $totalPembayaran }}</div>
            <div class="small text-muted" style="font-size: 0.75rem;">Semua status transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="corporate-card p-3" style="border-left: 3px solid var(--jm-gold);">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="small text-muted fw-semibold">Menunggu Kliring</span>
                <i class="bi bi-clock-history text-warning" aria-hidden="true"></i>
            </div>
            <div class="fs-4 fw-bold text-warning" style="font-variant-numeric: tabular-nums;">{{ $totalMenunggu }}</div>
            <div class="small text-muted" style="font-size: 0.75rem;">Perlu ditindaklanjuti</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="corporate-card p-3" style="border-left: 3px solid var(--jm-blue);">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="small text-muted fw-semibold">Sedang Diproses</span>
                <i class="bi bi-arrow-repeat text-info" aria-hidden="true"></i>
            </div>
            <div class="fs-4 fw-bold text-info" style="font-variant-numeric: tabular-nums;">{{ $totalDiproses }}</div>
            <div class="small text-muted" style="font-size: 0.75rem;">Dalam kliring bank</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="corporate-card p-3" style="border-left: 3px solid #10B981;">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="small text-muted fw-semibold">Berhasil Tersalur</span>
                <i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i>
            </div>
            <div class="fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">{{ $totalBerhasil }}</div>
            <div class="small text-muted" style="font-size: 0.75rem;">Dana telah diterima</div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('keuangan.pembayaran.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label for="searchPembayaran" class="form-label-corporate">Pencarian Pensiunan</label>
                <div class="input-group input-group-corporate">
                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-search"></i></span>
                    <input type="text"
                           id="searchPembayaran"
                           name="search"
                           class="form-control form-control-corporate"
                           placeholder="Cari NIP atau nama pensiunan..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-6 col-md-3">
                <label for="filterPeriode" class="form-label-corporate">Periode Penyaluran</label>
                <select id="filterPeriode" name="periode" class="form-select form-select-corporate">
                    <option value="">Semua Periode</option>
                    @foreach($daftarPeriode as $prd)
                        <option value="{{ $prd }}" {{ request('periode') === $prd ? 'selected' : '' }}>
                            {{ $prd }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="filterStatusBayar" class="form-label-corporate">Status Transaksi</label>
                <select id="filterStatusBayar" name="status" class="form-select form-select-corporate">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="berhasil" {{ request('status') === 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                    <option value="gagal"    {{ request('status') === 'gagal'    ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-jm-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('periode') || request('status'))
                    <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
                        <i class="bi bi-x" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Data Table Card --}}
<div class="corporate-card">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-receipt text-primary" aria-hidden="true"></i>
            <span>Daftar Transaksi Perbankan</span>
        </h2>
        <small class="text-muted">
            Menampilkan {{ $pembayaran->firstItem() ?? 0 }} - {{ $pembayaran->lastItem() ?? 0 }} dari {{ $pembayaran->total() }} data
        </small>
    </div>

    <div class="table-responsive">
        <table class="table-corporate">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>NIP &amp; Nama Penerima</th>
                    <th>Bank &amp; Rekening</th>
                    <th>Periode</th>
                    <th class="text-end">Jumlah Manfaat</th>
                    <th>Tanggal Eksekusi</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $p)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $pembayaran->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->pensiunan->nama }}</div>
                            <div class="small text-muted font-monospace">NIP: {{ $p->pensiunan->nip }}</div>
                        </td>
                        <td>
                            @php
                                $rek = $p->pensiunan->rekening->first();
                            @endphp
                            @if($rek)
                                <div class="small fw-semibold text-dark">{{ $rek->nama_bank }}</div>
                                <div class="small font-monospace text-muted">{{ $rek->nomor_rekening }}</div>
                            @else
                                <span class="small text-muted fst-italic">Belum Ada Rekening</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                {{ $p->periode }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark" style="font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="small text-muted">
                                {{ $p->tanggal_pembayaran ? $p->tanggal_pembayaran->translatedFormat('d M Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($p->status === 'berhasil')
                                <span class="badge-status badge-status-success">Berhasil</span>
                            @elseif($p->status === 'diproses')
                                <span class="badge-status badge-status-info">Diproses</span>
                            @elseif($p->status === 'menunggu')
                                <span class="badge-status badge-status-warning">Menunggu</span>
                            @else
                                <span class="badge-status badge-status-danger">Gagal</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('keuangan.pembayaran.show', $p) }}"
                               class="btn btn-jm-outline btn-sm py-1 px-3"
                               title="Detail dan Eksekusi Pembayaran">
                                <span>{{ in_array($p->status, ['menunggu', 'diproses']) ? 'Proses' : 'Detail' }}</span>
                                <i class="bi bi-chevron-right small text-muted" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon" aria-hidden="true">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="empty-state-title">Transaksi Tidak Ditemukan</div>
                                <p class="empty-state-text">
                                    Tidak ada data transaksi pembayaran yang sesuai dengan kriteria filter yang diterapkan.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pembayaran->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white">
            {{ $pembayaran->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

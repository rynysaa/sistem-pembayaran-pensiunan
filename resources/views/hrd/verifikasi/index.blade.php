@extends('layouts.app')

@section('title', 'Verifikasi Data Pensiunan')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Validasi Dokumen</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">Divisi Human Capital PT Jasa Marga</span>
        </div>
        <h1 class="corporate-header-title">Verifikasi Data Pensiunan</h1>
        <p class="corporate-header-sub">
            Pemeriksaan keabsahan berkas kepegawaian purnabakti sebelum penerbitan hak penyaluran manfaat pensiun.
        </p>
    </div>
</div>

{{-- Metric Mini Cards (Asymmetrical Pipeline) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--jm-blue-light); color: var(--jm-blue);">
                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark" style="font-variant-numeric: tabular-nums;">{{ $totalSemua }}</div>
                    <div class="small text-muted">Total Pengajuan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-warning-bg); color: var(--status-warning-text);">
                    <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-warning" style="font-variant-numeric: tabular-nums;">{{ $totalMenunggu }}</div>
                    <div class="small text-muted">Menunggu Review</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                    <i class="bi bi-check2-circle" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">{{ $totalDiterima }}</div>
                    <div class="small text-muted">Telah Disetujui</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-danger-bg); color: var(--status-danger-text);">
                    <i class="bi bi-x-circle" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger" style="font-variant-numeric: tabular-nums;">{{ $totalDitolak }}</div>
                    <div class="small text-muted">Ditolak / Dikembalikan</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter & Search Form --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('hrd.verifikasi.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label for="searchVerif" class="form-label-corporate">Pencarian Data Pensiunan</label>
                <div class="input-group input-group-corporate">
                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-search"></i></span>
                    <input type="text"
                           id="searchVerif"
                           name="search"
                           class="form-control form-control-corporate"
                           placeholder="Cari berdasarkan nama atau NIP..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label for="filterStatusVerif" class="form-label-corporate">Status Verifikasi Berkas</label>
                <select id="filterStatusVerif" name="status" class="form-select form-select-corporate">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima / Disetujui</option>
                    <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak / Dikembalikan</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-jm-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('hrd.verifikasi.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
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
            <i class="bi bi-list-check text-primary" aria-hidden="true"></i>
            <span>Daftar Berkas Verifikasi Pensiunan</span>
        </h2>
        <small class="text-muted">
            Menampilkan {{ $verifikasis->firstItem() ?? 0 }} - {{ $verifikasis->lastItem() ?? 0 }} dari {{ $verifikasis->total() }} data
        </small>
    </div>

    <div class="table-responsive">
        <table class="table-corporate">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>NIP &amp; Nama Pensiunan</th>
                    <th>Jabatan &amp; Unit Terakhir</th>
                    <th>Tanggal Pensiun</th>
                    <th class="text-center">Status Verifikasi</th>
                    <th class="text-end pe-4">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($verifikasis as $v)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $verifikasis->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $v->pensiunan->nama }}</div>
                            <div class="small text-muted font-monospace">NIP: {{ $v->pensiunan->nip }}</div>
                        </td>
                        <td>
                            <div class="text-dark small fw-semibold">{{ $v->pensiunan->jabatan_terakhir }}</div>
                            @if($v->pensiunan->unit_kerja)
                                <div class="text-muted small" style="font-size: 0.75rem;">{{ $v->pensiunan->unit_kerja }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">
                                {{ $v->pensiunan->tanggal_pensiun ? $v->pensiunan->tanggal_pensiun->translatedFormat('d M Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($v->status === 'diterima')
                                <span class="badge-status badge-status-success">Diterima</span>
                            @elseif($v->status === 'menunggu')
                                <span class="badge-status badge-status-warning">Menunggu</span>
                            @else
                                <span class="badge-status badge-status-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('hrd.verifikasi.show', $v) }}" class="btn btn-jm-primary btn-sm py-1 px-3">
                                <span>Tinjau</span>
                                <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon" aria-hidden="true">
                                    <i class="bi bi-clipboard2-check"></i>
                                </div>
                                <div class="empty-state-title">Data Verifikasi Tidak Ditemukan</div>
                                <p class="empty-state-text">
                                    Tidak ada catatan verifikasi berkas yang sesuai dengan kriteria filter yang diterapkan.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($verifikasis->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white">
            {{ $verifikasis->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

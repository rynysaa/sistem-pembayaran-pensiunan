@extends('layouts.app')

@section('title', 'Data Rekening Pensiunan - Keuangan')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-keuangan">Referensi Rekening</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Referensi Rekening Pensiunan</h1>
        <p class="corporate-header-sub">
            Direktori rekening bank pensiunan terverifikasi sebagai acuan penyaluran dana divisi Keuangan.
        </p>
    </div>
</div>

{{-- Mini Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--jm-blue-light); color: var(--jm-blue);">
                    <i class="bi bi-bank2" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark" style="font-variant-numeric: tabular-nums;">{{ $totalRekening }}</div>
                    <div class="small text-muted">Total Rekening Terdaftar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">{{ $totalAktif }}</div>
                    <div class="small text-muted">Rekening Aktif Siap Kliring</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-danger-bg); color: var(--status-danger-text);">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger" style="font-variant-numeric: tabular-nums;">{{ $totalNonaktif }}</div>
                    <div class="small text-muted">Rekening Ditangguhkan</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('keuangan.rekening.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label for="searchRekeningKeu" class="form-label-corporate">Pencarian Rekening</label>
                <div class="input-group input-group-corporate">
                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-search"></i></span>
                    <input type="text"
                           id="searchRekeningKeu"
                           name="search"
                           class="form-control form-control-corporate"
                           placeholder="Cari nama pensiunan, NIP, atau nama bank..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label for="statusRekKeu" class="form-label-corporate">Status Rekening</label>
                <select id="statusRekKeu" name="status" class="form-select form-select-corporate">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-jm-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('keuangan.rekening.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
                        <i class="bi bi-x" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table Card --}}
<div class="corporate-card">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-card-checklist text-primary" aria-hidden="true"></i>
            <span>Daftar Akun Bank Penyaluran</span>
        </h2>
        <small class="text-muted">
            Menampilkan {{ $rekening->firstItem() ?? 0 }} - {{ $rekening->lastItem() ?? 0 }} dari {{ $rekening->total() }} rekening
        </small>
    </div>

    <div class="table-responsive">
        <table class="table-corporate">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>Nama Pensiunan &amp; NIP</th>
                    <th>Lembaga Perbankan</th>
                    <th>Nomor Rekening</th>
                    <th>Atas Nama Pemilik</th>
                    <th class="text-center pe-4">Status Rekening</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekening as $r)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $rekening->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $r->pensiunan->nama }}</div>
                            <div class="small text-muted font-monospace">NIP: {{ $r->pensiunan->nip }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-semibold px-2 py-1">
                                <i class="bi bi-bank2 me-1 text-primary" aria-hidden="true"></i>
                                {{ $r->nama_bank }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold font-monospace fs-6 text-dark letter-spacing-1">
                                {{ $r->nomor_rekening }}
                            </span>
                        </td>
                        <td>
                            <span class="small text-dark fw-medium">{{ $r->atas_nama }}</span>
                        </td>
                        <td class="text-center pe-4">
                            @if($r->status === 'aktif')
                                <span class="badge-status badge-status-success">Aktif</span>
                            @else
                                <span class="badge-status badge-status-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon" aria-hidden="true">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div class="empty-state-title">Rekening Tidak Ditemukan</div>
                                <p class="empty-state-text">
                                    Tidak ada data rekening yang sesuai dengan kata kunci pencarian.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rekening->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white">
            {{ $rekening->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

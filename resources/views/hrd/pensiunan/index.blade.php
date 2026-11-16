@extends('layouts.app')

@section('title', 'Direktori Data Pensiunan')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Master Data SDM</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Direktori Data Pensiunan</h1>
        <p class="corporate-header-sub">Kelola arsip identitas, status kepegawaian, dan data purnabakti perusahaan.</p>
    </div>
    <a href="{{ route('hrd.pensiunan.create') }}" class="btn btn-jm-gold btn-sm">
        <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
        <span>Tambah Pensiunan Baru</span>
    </a>
</div>

{{-- Metric Mini Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--jm-blue-light); color: var(--jm-blue);">
                    <i class="bi bi-people-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark" style="font-variant-numeric: tabular-nums;">{{ $totalSemua }}</div>
                    <div class="small text-muted">Total Seluruh Data</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                    <i class="bi bi-person-check-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">{{ $totalAktif }}</div>
                    <div class="small text-muted">Status Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="corporate-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background-color: var(--status-danger-bg); color: var(--status-danger-text);">
                    <i class="bi bi-person-dash-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-danger" style="font-variant-numeric: tabular-nums;">{{ $totalNonaktif }}</div>
                    <div class="small text-muted">Status Nonaktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter & Search Form --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('hrd.pensiunan.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="searchPensiunan" class="form-label-corporate">Pencarian Pensiunan</label>
                <div class="input-group input-group-corporate">
                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-search"></i></span>
                    <input type="text"
                           id="searchPensiunan"
                           name="search"
                           class="form-control form-control-corporate"
                           placeholder="Cari berdasarkan NIP, NIK, atau Nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label for="filterStatus" class="form-label-corporate">Status Kepesertaan</label>
                <select id="filterStatus" name="status" class="form-select form-select-corporate">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-jm-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
                        <i class="bi bi-x-circle" aria-hidden="true"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Data Table Card --}}
<div class="corporate-card">
    <div class="corporate-card-header">
        <div class="d-flex align-items-center gap-2">
            <h2 class="corporate-card-title">
                <i class="bi bi-table text-primary" aria-hidden="true"></i>
                <span>Daftar Purnabakti Terdaftar</span>
            </h2>
            @if(request('search') || request('status'))
                <span class="badge bg-primary rounded-pill small">Filter Diterapkan</span>
            @endif
        </div>
        <small class="text-muted">
            Menampilkan {{ $pensiunan->firstItem() ?? 0 }} - {{ $pensiunan->lastItem() ?? 0 }} dari {{ $pensiunan->total() }} data
        </small>
    </div>

    <div class="table-responsive">
        <table class="table-corporate">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>NIP &amp; Nama Lengkap</th>
                    <th>NIK</th>
                    <th>Jabatan &amp; Unit Terakhir</th>
                    <th>Tanggal Pensiun</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pensiunan as $p)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $pensiunan->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->nama }}</div>
                            <div class="small text-muted font-monospace">NIP: {{ $p->nip }}</div>
                        </td>
                        <td>
                            <span class="small font-monospace text-muted">{{ $p->nik ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="text-dark small fw-semibold">{{ $p->jabatan_terakhir }}</div>
                            @if($p->unit_kerja)
                                <div class="text-muted small" style="font-size: 0.75rem;">{{ $p->unit_kerja }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">
                                {{ $p->tanggal_pensiun ? $p->tanggal_pensiun->translatedFormat('d M Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($p->status === 'aktif')
                                <span class="badge-status badge-status-success">Aktif</span>
                            @else
                                <span class="badge-status badge-status-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('hrd.pensiunan.show', $p) }}"
                                   class="btn-action-icon"
                                   title="Lihat Detail Pensiunan"
                                   aria-label="Detail {{ $p->nama }}">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('hrd.pensiunan.edit', $p) }}"
                                   class="btn-action-icon text-warning"
                                   title="Edit Data Pensiunan"
                                   aria-label="Edit {{ $p->nama }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <button type="button"
                                        class="btn-action-icon text-danger"
                                        title="Hapus Data Pensiunan"
                                        aria-label="Hapus {{ $p->nama }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus"
                                        data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon" aria-hidden="true">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="empty-state-title">Data Pensiunan Tidak Ditemukan</div>
                                <p class="empty-state-text">
                                    Tidak ada catatan data pensiunan yang sesuai dengan kriteria pencarian atau filter yang dipilih.
                                </p>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm">
                                        Bersihkan Filter Pencarian
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pensiunan->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-white">
            {{ $pensiunan->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Modal Konfirmasi Hapus (Accessible R-32 & R-39) --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="modalHapusLabel">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>
                    Konfirmasi Penghapusan Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup dialog"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-muted small mb-2">Anda akan menghapus data purnabakti berikut:</p>
                <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                    <div class="fw-bold text-danger" id="namaHapus"></div>
                    <div class="small text-muted">Seluruh data riwayat dan rekening terkait akan terpengaruh.</div>
                </div>
                <p class="small text-muted mb-0">
                    Tindakan ini permanen dan <strong>tidak dapat dibatalkan</strong>. Pastikan data tidak sedang digunakan dalam proses transaksi aktif.
                </p>
            </div>
            <div class="modal-footer border-top pt-2">
                <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <form id="formHapus" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash3-fill me-1" aria-hidden="true"></i>
                        Ya, Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modalHapus = document.getElementById('modalHapus');
    if (modalHapus) {
        modalHapus.addEventListener('show.bs.modal', function (e) {
            const btn   = e.relatedTarget;
            const id    = btn.getAttribute('data-id');
            const nama  = btn.getAttribute('data-nama');
            document.getElementById('namaHapus').textContent = nama;
            document.getElementById('formHapus').action = `/hrd/pensiunan/${id}`;
        });
    }
</script>
@endpush

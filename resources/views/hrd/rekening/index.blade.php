@extends('layouts.app')

@section('title', 'Manajemen Rekening Bank Pensiunan')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">SDM &amp; Keuangan</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">Administrasi Perbankan</span>
        </div>
        <h1 class="corporate-header-title">Manajemen Rekening Pensiunan</h1>
        <p class="corporate-header-sub">Kelola akun bank penerima manfaat pensiun PT Jasa Marga (Persero) Tbk.</p>
    </div>
    <a href="{{ route('hrd.rekening.create') }}" class="btn btn-jm-gold btn-sm">
        <i class="bi bi-credit-card-2-front" aria-hidden="true"></i>
        <span>Tambah Rekening Baru</span>
    </a>
</div>

{{-- Metric Mini Cards --}}
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
                    <div class="small text-muted">Rekening Aktif (Siap Transfer)</div>
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
                    <div class="small text-muted">Rekening Nonaktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="corporate-card mb-4 filter-corporate">
    <div class="corporate-card-body p-3">
        <form method="GET" action="{{ route('hrd.rekening.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label for="searchRekening" class="form-label-corporate">Pencarian Cepat</label>
                <div class="input-group input-group-corporate">
                    <span class="input-group-text" aria-hidden="true"><i class="bi bi-search"></i></span>
                    <input type="text"
                           id="searchRekening"
                           name="search"
                           class="form-control form-control-corporate"
                           placeholder="Cari nama pensiunan, NIP, atau bank..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label for="filterPensiunan" class="form-label-corporate">Filter Pensiunan Tertentu</label>
                <select id="filterPensiunan" name="pensiunan_id" class="form-select form-select-corporate">
                    <option value="">Semua Pensiunan</option>
                    @foreach($pensiunans as $p)
                        <option value="{{ $p->id }}" {{ request('pensiunan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-sm-3 col-md-2">
                <label for="filterStatusRek" class="form-label-corporate">Status</label>
                <select id="filterStatusRek" name="status" class="form-select form-select-corporate">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="col-6 col-sm-3 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-jm-primary btn-sm w-100" title="Terapkan filter">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                </button>
                @if(request('search') || request('pensiunan_id') || request('status'))
                    <a href="{{ route('hrd.rekening.index') }}" class="btn btn-jm-outline btn-sm" title="Reset filter">
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
            <i class="bi bi-card-checklist text-primary" aria-hidden="true"></i>
            <span>Daftar Rekening Bank Penyaluran</span>
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
                    <th>Pensiunan Penerima</th>
                    <th>Lembaga Perbankan</th>
                    <th>Nomor Rekening</th>
                    <th>Nama Tertera di Rekening</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Aksi</th>
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
                        <td class="text-center">
                            @if($r->status === 'aktif')
                                <span class="badge-status badge-status-success">Aktif</span>
                            @else
                                <span class="badge-status badge-status-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1 align-items-center">
                                {{-- Toggle status --}}
                                <form method="POST" action="{{ route('hrd.rekening.toggle-status', $r) }}" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn-action-icon {{ $r->status === 'aktif' ? 'text-secondary' : 'text-success' }}"
                                            title="{{ $r->status === 'aktif' ? 'Nonaktifkan rekening' : 'Aktifkan rekening' }}"
                                            aria-label="{{ $r->status === 'aktif' ? 'Nonaktifkan rekening' : 'Aktifkan rekening' }}">
                                        <i class="bi {{ $r->status === 'aktif' ? 'bi-toggle2-on' : 'bi-toggle2-off' }}" aria-hidden="true"></i>
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <a href="{{ route('hrd.rekening.edit', $r) }}"
                                   class="btn-action-icon text-warning"
                                   title="Edit Rekening"
                                   aria-label="Edit Rekening {{ $r->nomor_rekening }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>

                                {{-- Hapus --}}
                                <button type="button"
                                        class="btn-action-icon text-danger"
                                        title="Hapus Rekening"
                                        aria-label="Hapus Rekening {{ $r->nomor_rekening }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus"
                                        data-id="{{ $r->id }}"
                                        data-info="{{ $r->nama_bank }} - {{ $r->nomor_rekening }}"
                                        data-nama="{{ $r->pensiunan->nama }}">
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
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div class="empty-state-title">Data Rekening Tidak Ditemukan</div>
                                <p class="empty-state-text">
                                    Tidak ada data rekening yang sesuai dengan kata kunci pencarian atau filter yang dipilih.
                                </p>
                                @if(request('search') || request('pensiunan_id') || request('status'))
                                    <a href="{{ route('hrd.rekening.index') }}" class="btn btn-jm-outline btn-sm">
                                        Bersihkan Filter
                                    </a>
                                @endif
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

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="modalHapusLabel">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>
                    Konfirmasi Hapus Rekening
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-muted small mb-2">Anda akan menghapus rekening perbankan berikut:</p>
                <div class="p-3 rounded-2 mb-2" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                    <div class="fw-bold text-danger font-monospace fs-6" id="infoRekening"></div>
                    <div class="small text-muted mt-1">Pemilik: <span class="fw-semibold text-dark" id="namaPensiunan"></span></div>
                </div>
                <p class="small text-muted mb-0">
                    Tindakan ini tidak dapat dibatalkan. Pastikan rekening tidak memiliki riwayat pembayaran aktif yang sedang diproses.
                </p>
            </div>
            <div class="modal-footer border-top pt-2">
                <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <form id="formHapus" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash3-fill me-1" aria-hidden="true"></i>
                        Ya, Hapus Rekening
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
            const btn  = e.relatedTarget;
            const id   = btn.getAttribute('data-id');
            const info = btn.getAttribute('data-info');
            const nama = btn.getAttribute('data-nama');

            document.getElementById('infoRekening').textContent  = info;
            document.getElementById('namaPensiunan').textContent = nama;
            document.getElementById('formHapus').action = `/hrd/rekening/${id}`;
        });
    }
</script>
@endpush

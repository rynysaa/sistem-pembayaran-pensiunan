@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')
    {{-- Header Banner --}}
    <div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="role-pill role-pill-hrd">Divisi Human Capital</span>
                <span class="text-white-50 small">|</span>
                <span class="text-white-50 small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="corporate-header-title">Dashboard Manajemen Pensiunan</h1>
            <p class="corporate-header-sub">
                Ringkasan data kepegawaian purnabakti dan validasi dokumen PT Jasa Marga (Persero) Tbk.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('hrd.verifikasi.index') }}" class="btn btn-jm-gold btn-sm">
                <i class="bi bi-clipboard2-check-fill" aria-hidden="true"></i>
                <span>Tinjau Verifikasi ({{ $menungguVerifikasi }})</span>
            </a>
            <a href="{{ route('hrd.pensiunan.create') }}" class="btn btn-jm-blue btn-sm">
                <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                <span>Tambah Pensiunan</span>
            </a>
        </div>
    </div>

    {{-- Metrics Row (Asymmetric & Hierarchical) --}}
    <div class="row g-3 mb-4">
        {{-- Hero Metric: Total Pensiunan --}}
        <div class="col-12 col-lg-5">
            <div class="metric-card-hero">
                <div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Total Purnabakti Terdata</span>
                        <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-2 py-1 small">
                            Master Data
                        </span>
                    </div>
                    <div class="metric-value">{{ number_format($totalPensiunan, 0, ',', '.') }} <span class="fs-6 fw-normal text-white-50">orang</span></div>
                    <p class="small text-white-50 mb-3">Total karyawan purna tugas yang terdaftar dalam basis data perusahaan.</p>
                </div>

                {{-- Status Ratio Breakdown --}}
                <div class="pt-3 border-top border-white border-opacity-10">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white-50">Status Kepesertaan:</span>
                        <span class="text-white fw-semibold">{{ $pensiunanAktif }} Aktif / {{ $pensiunanNonaktif }} Nonaktif</span>
                    </div>
                    <div class="progress" style="height: 6px; background-color: rgba(255,255,255,0.15);">
                        @php
                            $aktifPct = $totalPensiunan > 0 ? ($pensiunanAktif / $totalPensiunan) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $aktifPct }}%" aria-valuenow="{{ $aktifPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verification Status Pipeline --}}
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card-standard">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-label">Status Verifikasi Berkas</span>
                        <div class="metric-icon-box" style="background-color: var(--status-warning-bg); color: var(--jm-gold);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="metric-value text-warning">{{ $menungguVerifikasi }}</span>
                        <span class="small text-muted fw-semibold">Menunggu Review</span>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top d-flex justify-content-between small">
                    <div>
                        <span class="text-muted d-block">Disetujui:</span>
                        <span class="fw-bold text-success">{{ $verifikasiDiterima }} berkas</span>
                    </div>
                    <div class="text-end">
                        <span class="text-muted d-block">Ditolak:</span>
                        <span class="fw-bold text-danger">{{ $verifikasiDitolak }} berkas</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Operational Shortcuts --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="corporate-card h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <span class="metric-label d-block mb-2">Aksi Cepat SDM</span>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm justify-content-between w-100">
                            <span><i class="bi bi-people me-1"></i> Direktori Pensiunan</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('hrd.rekening.index') }}" class="btn btn-jm-outline btn-sm justify-content-between w-100">
                            <span><i class="bi bi-credit-card me-1"></i> Rekening Bank</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>
                        <a href="{{ route('notifikasi.index') }}" class="btn btn-jm-outline btn-sm justify-content-between w-100">
                            <span><i class="bi bi-bell me-1"></i> Notifikasi Sistem</span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-muted" style="font-size: 0.72rem;">Sistem Pembayaran PT Jasa Marga</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="row g-4">
        {{-- Antrean Verifikasi --}}
        <div class="col-12 col-lg-7">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-hourglass-split text-warning" aria-hidden="true"></i>
                        <span>Antrean Verifikasi Berkas Baru</span>
                    </h2>
                    <a href="{{ route('hrd.verifikasi.index') }}?status=menunggu" class="small fw-semibold text-decoration-none">
                        Lihat Semua ({{ $menungguVerifikasi }})
                    </a>
                </div>
                <div class="corporate-card-body p-0">
                    @if($antreanVerifikasi->count() > 0)
                        <div class="table-responsive">
                            <table class="table-corporate">
                                <thead>
                                    <tr>
                                        <th>NIP &amp; Nama</th>
                                        <th>Jabatan Terakhir</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th class="text-end">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($antreanVerifikasi as $verif)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $verif->pensiunan->nama }}</div>
                                                <div class="small text-muted font-monospace">{{ $verif->pensiunan->nip }}</div>
                                            </td>
                                            <td>
                                                <span class="small">{{ $verif->pensiunan->jabatan_terakhir }}</span>
                                            </td>
                                            <td>
                                                <span class="small text-muted">{{ $verif->created_at->translatedFormat('d M Y') }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('hrd.verifikasi.show', $verif) }}" class="btn btn-jm-primary btn-sm py-1 px-2">
                                                    <span>Tinjau</span>
                                                    <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon" aria-hidden="true">
                                <i class="bi bi-check-all text-success"></i>
                            </div>
                            <div class="empty-state-title">Seluruh Berkas Telah Terverifikasi</div>
                            <p class="empty-state-text">
                                Tidak ada pengajuan verifikasi berkas pensiunan yang sedang menunggu persetujuan saat ini.
                            </p>
                            <a href="{{ route('hrd.verifikasi.index') }}" class="btn btn-jm-outline btn-sm">
                                Buka Arsip Verifikasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pensiunan Baru Terdaftar --}}
        <div class="col-12 col-lg-5">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-person-check text-primary" aria-hidden="true"></i>
                        <span>Pensiunan Baru Terdaftar</span>
                    </h2>
                    <a href="{{ route('hrd.pensiunan.index') }}" class="small fw-semibold text-decoration-none">
                        Buka Direktori
                    </a>
                </div>
                <div class="corporate-card-body p-0">
                    @if($pensiunanTerbaru->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pensiunanTerbaru as $p)
                                <div class="list-group-item p-3 border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="brand-badge" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($p->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $p->nama }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                NIP: <span class="font-monospace">{{ $p->nip }}</span> | {{ $p->jabatan_terakhir }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($p->status === 'aktif')
                                            <span class="badge-status badge-status-success">Aktif</span>
                                        @else
                                            <span class="badge-status badge-status-danger">Nonaktif</span>
                                        @endif
                                        <div class="mt-1">
                                            <a href="{{ route('hrd.pensiunan.show', $p) }}" class="btn btn-sm btn-link p-0 text-decoration-none small">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-4">
                            <p class="empty-state-text">Belum ada pensiunan yang terdaftar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

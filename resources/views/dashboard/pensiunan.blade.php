@extends('layouts.app')

@section('title', 'Dashboard Pensiunan')

@section('sidebar')
    @include('pensiunan._sidebar')
@endsection

@section('content')
    {{-- Welcome Banner --}}
    <div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="role-pill role-pill-pensiunan">Purnabakti PT Jasa Marga</span>
                <span class="text-white-50 small">|</span>
                <span class="text-white-50 small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="corporate-header-title">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}</h1>
            <p class="corporate-header-sub">
                Layanan informasi terpadu data kepegawaian dan penyaluran manfaat pensiun bulanan Anda.
            </p>
        </div>
        <div>
            @if($verifikasiStatus === 'diterima')
                <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 px-3 py-2 rounded-pill small">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> Data Terverifikasi
                </span>
            @elseif($verifikasiStatus === 'menunggu')
                <span class="badge bg-warning bg-opacity-25 text-white border border-warning border-opacity-50 px-3 py-2 rounded-pill small">
                    <i class="bi bi-hourglass-split text-warning me-1"></i> Menunggu Verifikasi HRD
                </span>
            @else
                <span class="badge bg-danger bg-opacity-25 text-white border border-danger border-opacity-50 px-3 py-2 rounded-pill small">
                    <i class="bi bi-exclamation-octagon-fill text-danger me-1"></i> Perlu Perbaikan Data
                </span>
            @endif
        </div>
    </div>

    {{-- Financial & Status Cards --}}
    <div class="row g-3 mb-4">
        {{-- Pembayaran Bulan Berjalan --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="metric-card-hero">
                <div>
                    <span class="metric-label">Manfaat Pensiun Bulan Ini</span>
                    <div class="metric-value">
                        {{ $pembayaranBulanIni ? 'Rp ' . number_format($pembayaranBulanIni->jumlah, 0, ',', '.') : 'Rp 0' }}
                    </div>
                    <p class="small text-white-50 mb-2">
                        Periode: <strong>{{ date('F Y') }}</strong>
                    </p>
                </div>
                <div class="pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                    <span class="text-white-50 small">Status Penyaluran:</span>
                    @if($pembayaranBulanIni)
                        @if($pembayaranBulanIni->status === 'berhasil')
                            <span class="badge-status badge-status-success">Berhasil Ditransfer</span>
                        @elseif($pembayaranBulanIni->status === 'diproses')
                            <span class="badge-status badge-status-info">Sedang Diproses</span>
                        @elseif($pembayaranBulanIni->status === 'menunggu')
                            <span class="badge-status badge-status-warning">Menunggu Kliring</span>
                        @else
                            <span class="badge-status badge-status-danger">Gagal / Kendala</span>
                        @endif
                    @else
                        <span class="badge bg-secondary text-white rounded-pill px-2 py-1 small">Belum Diterbitkan</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Manfaat Diterima --}}
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card-standard">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-label">Total Akumulasi Diterima</span>
                        <div class="metric-icon-box" style="background-color: var(--status-success-bg); color: var(--status-success-text);">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div class="metric-value text-success" style="font-size: 1.6rem;">
                        Rp {{ number_format($totalDiterimaNominal, 0, ',', '.') }}
                    </div>
                    <p class="small text-muted mt-1">Total kumulatif dana pensiun yang sukses masuk ke rekening Anda.</p>
                </div>
                <div class="pt-2 border-top">
                    <span class="small text-muted">Rekening Penerima: <strong>{{ $rekeningAktif ? $rekeningAktif->nama_bank : 'Belum Ditentukan' }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Notifikasi & Pengumuman --}}
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="corporate-card h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="metric-label">Pemberitahuan Sistem</span>
                        <div class="metric-icon-box" style="background-color: var(--jm-blue-light); color: var(--jm-blue);">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 1.6rem;">
                        {{ $notifUnreadCount }} <span class="fs-6 fw-normal text-muted">belum dibaca</span>
                    </div>
                    <p class="small text-muted mt-1">Informasi penting terkait verifikasi dan jadwal pembayaran pensiun.</p>
                </div>
                <div class="pt-2 border-top">
                    <a href="{{ route('notifikasi.index') }}" class="btn btn-jm-outline btn-sm w-100 justify-content-center">
                        <span>Buka Pusat Notifikasi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Data Profil & Rekening --}}
    <div class="row g-4 mb-4">
        {{-- Profil Pensiunan --}}
        <div class="col-12 col-lg-6" id="profil-ringkasan">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-person-badge text-primary" aria-hidden="true"></i>
                        <span>Profil Kepegawaian Purnabakti</span>
                    </h2>
                    <span class="badge bg-light text-dark border small">Data Resmi</span>
                </div>
                <div class="corporate-card-body">
                    @if($pensiunan)
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Nomor Induk Pegawai (NIP):</span>
                                <span class="fw-bold font-monospace text-dark">{{ $pensiunan->nip }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Nomor Induk Kependudukan (NIK):</span>
                                <span class="fw-bold font-monospace text-dark">{{ $pensiunan->nik ?? '-' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Nama Lengkap:</span>
                                <span class="fw-semibold text-dark">{{ $pensiunan->nama }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Jabatan Terakhir:</span>
                                <span class="fw-semibold text-dark">{{ $pensiunan->jabatan_terakhir }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Tanggal Pensiun:</span>
                                <span class="fw-semibold text-dark">
                                    {{ $pensiunan->tanggal_pensiun ? \Carbon\Carbon::parse($pensiunan->tanggal_pensiun)->translatedFormat('d F Y') : '-' }}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <span class="small text-muted d-block">Nomor Telepon:</span>
                                <span class="fw-semibold text-dark">{{ $pensiunan->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-12">
                                <span class="small text-muted d-block">Alamat Domisili:</span>
                                <span class="text-dark">{{ $pensiunan->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="empty-state py-3">
                            <p class="empty-state-text">Data profil belum dihubungkan dengan akun Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rekening Penerima --}}
        <div class="col-12 col-lg-6" id="informasi-rekening">
            <div class="corporate-card h-100">
                <div class="corporate-card-header">
                    <h2 class="corporate-card-title">
                        <i class="bi bi-bank text-secondary" aria-hidden="true"></i>
                        <span>Rekening Bank Penyaluran</span>
                    </h2>
                    <span class="badge-status badge-status-success">Aktif Terdaftar</span>
                </div>
                <div class="corporate-card-body">
                    @if($rekeningAktif)
                        <div class="p-3 rounded-2 mb-3" style="background-color: #F8FAFC; border: 1px solid var(--jm-border);">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary px-3 py-1 fw-bold">{{ $rekeningAktif->nama_bank }}</span>
                                <span class="badge-status badge-status-success">Siap Menerima Transfer</span>
                            </div>
                            <div class="small text-muted mt-2">Nomor Rekening:</div>
                            <div class="fs-4 fw-bold font-monospace text-dark letter-spacing-1">
                                {{ $rekeningAktif->nomor_rekening }}
                            </div>
                            <div class="small text-muted mt-1">
                                Atas Nama: <strong class="text-dark">{{ $rekeningAktif->atas_nama }}</strong>
                            </div>
                        </div>
                        <p class="small text-muted mb-0">
                            <i class="bi bi-info-circle text-primary me-1"></i>
                            Penyaluran dana pensiun otomatis ditransfer ke rekening di atas setiap periode pembayaran. Jika ada perubahan nomor rekening, harap hubungi bagian SDM PT Jasa Marga.
                        </p>
                    @else
                        <div class="empty-state py-3">
                            <div class="empty-state-icon"><i class="bi bi-credit-card"></i></div>
                            <div class="empty-state-title">Rekening Belum Terdaftar</div>
                            <p class="empty-state-text">
                                Rekening bank Anda belum diverifikasi oleh HRD. Penyaluran dana akan dilakukan setelah data rekening aktif tercatat.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Transaksi Pembayaran --}}
    <div class="corporate-card mb-4" id="riwayat-transaksi">
        <div class="corporate-card-header">
            <h2 class="corporate-card-title">
                <i class="bi bi-receipt text-primary" aria-hidden="true"></i>
                <span>Riwayat Penyaluran Dana Pensiun</span>
            </h2>
            <span class="small text-muted">Daftar Transaksi Resmi</span>
        </div>
        <div class="corporate-card-body p-0">
            @if($riwayatPembayaran->count() > 0)
                <div class="table-responsive">
                    <table class="table-corporate">
                        <thead>
                            <tr>
                                <th>Periode Pembayaran</th>
                                <th>Tanggal Penyaluran</th>
                                <th class="text-end">Jumlah Manfaat</th>
                                <th>Status Transaksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatPembayaran as $riwayat)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Periode {{ $riwayat->periode }}</div>
                                    </td>
                                    <td>
                                        <span class="small text-muted">
                                            {{ $riwayat->tanggal_pembayaran ? \Carbon\Carbon::parse($riwayat->tanggal_pembayaran)->translatedFormat('d F Y') : 'Menunggu Jadwal' }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark" style="font-variant-numeric: tabular-nums;">
                                        Rp {{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($riwayat->status === 'berhasil')
                                            <span class="badge-status badge-status-success">Berhasil Masuk</span>
                                        @elseif($riwayat->status === 'diproses')
                                            <span class="badge-status badge-status-info">Dalam Proses Kliring</span>
                                        @elseif($riwayat->status === 'menunggu')
                                            <span class="badge-status badge-status-warning">Menunggu Persetujuan</span>
                                        @else
                                            <span class="badge-status badge-status-danger">Gagal Ditransfer</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $riwayat->keterangan ?? '-' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <div class="empty-state-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="empty-state-title">Belum Ada Riwayat Transaksi</div>
                    <p class="empty-state-text">
                        Data riwayat pembayaran Anda akan otomatis tampil di sini saat penyaluran dana periode berjalan diproses.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

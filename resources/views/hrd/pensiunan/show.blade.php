@extends('layouts.app')

@section('title', 'Detail Data Pensiunan - ' . $pensiunan->nama)

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Profil Karyawan Pensiun</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">NIP: {{ $pensiunan->nip }}</span>
        </div>
        <h1 class="corporate-header-title">{{ $pensiunan->nama }}</h1>
        <p class="corporate-header-sub">
            {{ $pensiunan->jabatan_terakhir }} {{ $pensiunan->unit_kerja ? '- ' . $pensiunan->unit_kerja : '' }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hrd.pensiunan.edit', $pensiunan) }}" class="btn btn-jm-gold btn-sm">
            <i class="bi bi-pencil" aria-hidden="true"></i>
            <span>Edit Data</span>
        </a>
        <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Kolom Kiri: Identitas Pribadi --}}
    <div class="col-12 col-lg-6">
        <div class="corporate-card h-100">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-person-vcard text-primary" aria-hidden="true"></i>
                    <span>Informasi Pribadi &amp; Kontak</span>
                </h2>
                @if($pensiunan->status === 'aktif')
                    <span class="badge-status badge-status-success">Status Aktif</span>
                @else
                    <span class="badge-status badge-status-danger">Status Nonaktif</span>
                @endif
            </div>
            <div class="corporate-card-body">
                <div class="voucher-row">
                    <span class="voucher-label">Nama Lengkap</span>
                    <span class="voucher-val fs-6">{{ $pensiunan->nama }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Nomor Induk Pegawai (NIP)</span>
                    <span class="voucher-val font-monospace text-primary">{{ $pensiunan->nip }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Nomor Induk Kependudukan (NIK)</span>
                    <span class="voucher-val font-monospace">{{ $pensiunan->nik ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Jenis Kelamin</span>
                    <span class="voucher-val">{{ $pensiunan->jenis_kelamin }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Lahir &amp; Usia</span>
                    <span class="voucher-val">
                        {{ $pensiunan->tanggal_lahir ? $pensiunan->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                        @if($pensiunan->tanggal_lahir)
                            <span class="text-muted small">({{ $pensiunan->tanggal_lahir->age }} tahun)</span>
                        @endif
                    </span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Nomor Telepon</span>
                    <span class="voucher-val">{{ $pensiunan->no_telepon ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Alamat Domisili</span>
                    <span class="voucher-val text-end" style="max-width: 60%;">{{ $pensiunan->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Kepegawaian & Rekening --}}
    <div class="col-12 col-lg-6">
        <div class="corporate-card mb-4">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-briefcase text-secondary" aria-hidden="true"></i>
                    <span>Informasi Kepegawaian</span>
                </h2>
            </div>
            <div class="corporate-card-body">
                <div class="voucher-row">
                    <span class="voucher-label">Jabatan Terakhir</span>
                    <span class="voucher-val">{{ $pensiunan->jabatan_terakhir }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Unit Kerja</span>
                    <span class="voucher-val">{{ $pensiunan->unit_kerja ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Mulai Bekerja (TMT Awal)</span>
                    <span class="voucher-val">
                        {{ $pensiunan->tanggal_mulai_kerja ? $pensiunan->tanggal_mulai_kerja->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Pensiun (TMT Pensiun)</span>
                    <span class="voucher-val">
                        {{ $pensiunan->tanggal_pensiun ? $pensiunan->tanggal_pensiun->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Registrasi Sistem</span>
                    <span class="voucher-val small text-muted">
                        {{ $pensiunan->created_at ? $pensiunan->created_at->translatedFormat('d F Y, H:i') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Rekening Terdaftar --}}
        <div class="corporate-card">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-bank text-dark" aria-hidden="true"></i>
                    <span>Rekening Penyaluran Terdaftar</span>
                </h2>
                <a href="{{ route('hrd.rekening.create') }}" class="small fw-semibold text-decoration-none">
                    + Tambah Rekening
                </a>
            </div>
            <div class="corporate-card-body p-0">
                @if($pensiunan->rekening && $pensiunan->rekening->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pensiunan->rekening as $rek)
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary px-2 py-1 small">{{ $rek->nama_bank }}</span>
                                        @if($rek->status === 'aktif')
                                            <span class="badge-status badge-status-success">Aktif</span>
                                        @else
                                            <span class="badge-status badge-status-danger">Nonaktif</span>
                                        @endif
                                    </div>
                                    <div class="fw-bold font-monospace fs-6 text-dark">{{ $rek->nomor_rekening }}</div>
                                    <div class="small text-muted">Atas Nama: {{ $rek->atas_nama }}</div>
                                </div>
                                <a href="{{ route('hrd.rekening.edit', $rek) }}" class="btn btn-jm-outline btn-sm">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                    <span>Kelola</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="empty-state-title">Belum Ada Rekening Terdaftar</div>
                        <p class="empty-state-text">
                            Pensiunan ini belum memiliki nomor rekening aktif untuk penyaluran dana.
                        </p>
                        <a href="{{ route('hrd.rekening.create') }}" class="btn btn-jm-primary btn-sm">
                            Tambah Rekening Bank
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tombol Hapus Bawah --}}
<div class="d-flex justify-content-end mb-4">
    <button type="button"
            class="btn btn-outline-danger btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalHapus">
        <i class="bi bi-trash3-fill me-1" aria-hidden="true"></i>
        <span>Hapus Data Pensiunan Ini</span>
    </button>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="modalHapusLabel">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>
                    Konfirmasi Hapus Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-muted small mb-2">Anda akan menghapus data pensiunan:</p>
                <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                    <div class="fw-bold text-danger">{{ $pensiunan->nama }}</div>
                    <div class="small text-muted font-monospace">NIP: {{ $pensiunan->nip }}</div>
                </div>
                <p class="small text-muted mb-0">
                    Tindakan ini permanen dan tidak dapat dibatalkan. Seluruh riwayat berkas dan rekening terkait akan terhapus.
                </p>
            </div>
            <div class="modal-footer border-top pt-2">
                <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <form method="POST" action="{{ route('hrd.pensiunan.destroy', $pensiunan) }}" class="m-0">
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

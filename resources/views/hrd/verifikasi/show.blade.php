@extends('layouts.app')

@section('title', 'Detail Verifikasi - ' . $verifikasi->pensiunan->nama)

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Pemeriksaan Berkas</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">NIP: {{ $verifikasi->pensiunan->nip }}</span>
        </div>
        <h1 class="corporate-header-title">Verifikasi: {{ $verifikasi->pensiunan->nama }}</h1>
        <p class="corporate-header-sub">
            Validasi berkas identitas dan keabsahan rekening bank purna karya sebelum penyaluran hak pensiun.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hrd.pensiunan.show', $verifikasi->pensiunan) }}" class="btn btn-jm-outline btn-sm bg-white text-dark" target="_blank">
            <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
            <span>Buka Data Master</span>
        </a>
        <a href="{{ route('hrd.verifikasi.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
</div>

{{-- Status Banner Card --}}
<div class="corporate-card mb-4 p-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box" style="width: 50px; height: 50px; font-size: 1.5rem;
                @if($verifikasi->status === 'diterima') background-color: var(--status-success-bg); color: var(--status-success-text);
                @elseif($verifikasi->status === 'ditolak') background-color: var(--status-danger-bg); color: var(--status-danger-text);
                @else background-color: var(--status-warning-bg); color: var(--status-warning-text);
                @endif">
                @if($verifikasi->status === 'diterima')
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                @elseif($verifikasi->status === 'ditolak')
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                @else
                    <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                @endif
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="small text-muted fw-semibold">Status Saat Ini:</span>
                    @if($verifikasi->status === 'diterima')
                        <span class="badge-status badge-status-success fs-6">Berkas Diterima / Terverifikasi</span>
                    @elseif($verifikasi->status === 'ditolak')
                        <span class="badge-status badge-status-danger fs-6">Berkas Ditolak / Tidak Lengkap</span>
                    @else
                        <span class="badge-status badge-status-warning fs-6">Menunggu Pemeriksaan HRD</span>
                    @endif
                </div>
                <div class="small text-muted">
                    @if($verifikasi->tanggal_verifikasi)
                        Diproses oleh <strong>{{ $verifikasi->verifikator->name ?? 'Tim SDM' }}</strong> pada {{ $verifikasi->tanggal_verifikasi->translatedFormat('d F Y, H:i') }} WIB
                    @else
                        Berkas baru diajukan dan belum ditandatangani secara digital oleh bagian SDM.
                    @endif
                </div>
            </div>
        </div>

        {{-- Verification Action Buttons --}}
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-jm-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTerima">
                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                <span>Setujui Berkas</span>
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTolak">
                <i class="bi bi-x-circle" aria-hidden="true"></i>
                <span>Tolak Berkas</span>
            </button>
        </div>
    </div>

    @if($verifikasi->catatan)
        <div class="mt-3 pt-3 border-top">
            <div class="small fw-bold text-dark mb-1">
                <i class="bi bi-chat-left-quote-fill me-1 text-primary" aria-hidden="true"></i>
                Catatan Verifikator:
            </div>
            <div class="p-3 rounded-2 small text-dark" style="background-color: #F8FAFC; border: 1px solid var(--jm-border);">
                {{ $verifikasi->catatan }}
            </div>
        </div>
    @endif
</div>

{{-- Content Grid: Identitas & Kepegawaian --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
        <div class="corporate-card h-100">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-person-vcard text-primary" aria-hidden="true"></i>
                    <span>Data Identitas Pensiunan</span>
                </h2>
            </div>
            <div class="corporate-card-body">
                <div class="voucher-row">
                    <span class="voucher-label">Nama Lengkap</span>
                    <span class="voucher-val fs-6 text-dark">{{ $verifikasi->pensiunan->nama }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">NIP</span>
                    <span class="voucher-val font-monospace text-primary">{{ $verifikasi->pensiunan->nip }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">NIK (KTP)</span>
                    <span class="voucher-val font-monospace">{{ $verifikasi->pensiunan->nik ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Jenis Kelamin</span>
                    <span class="voucher-val">{{ $verifikasi->pensiunan->jenis_kelamin }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Lahir &amp; Usia</span>
                    <span class="voucher-val">
                        {{ $verifikasi->pensiunan->tanggal_lahir ? $verifikasi->pensiunan->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                        @if($verifikasi->pensiunan->tanggal_lahir)
                            <span class="text-muted small">({{ $verifikasi->pensiunan->tanggal_lahir->age }} tahun)</span>
                        @endif
                    </span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Nomor Telepon</span>
                    <span class="voucher-val">{{ $verifikasi->pensiunan->no_telepon ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Alamat Sesuai KTP</span>
                    <span class="voucher-val text-end" style="max-width: 60%;">{{ $verifikasi->pensiunan->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="corporate-card h-100">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-briefcase text-secondary" aria-hidden="true"></i>
                    <span>Riwayat Kepegawaian PT Jasa Marga</span>
                </h2>
            </div>
            <div class="corporate-card-body">
                <div class="voucher-row">
                    <span class="voucher-label">Jabatan Terakhir</span>
                    <span class="voucher-val text-dark fw-bold">{{ $verifikasi->pensiunan->jabatan_terakhir }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Unit Kerja Terakhir</span>
                    <span class="voucher-val">{{ $verifikasi->pensiunan->unit_kerja ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Mulai Bekerja</span>
                    <span class="voucher-val">
                        {{ $verifikasi->pensiunan->tanggal_mulai_kerja ? $verifikasi->pensiunan->tanggal_mulai_kerja->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Tanggal Pensiun Resmi</span>
                    <span class="voucher-val">
                        {{ $verifikasi->pensiunan->tanggal_pensiun ? $verifikasi->pensiunan->tanggal_pensiun->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
                @if($verifikasi->pensiunan->tanggal_mulai_kerja && $verifikasi->pensiunan->tanggal_pensiun)
                    <div class="voucher-row">
                        <span class="voucher-label">Total Masa Bakti</span>
                        <span class="voucher-val text-primary fw-bold">
                            {{ $verifikasi->pensiunan->tanggal_mulai_kerja->diffInYears($verifikasi->pensiunan->tanggal_pensiun) }} Tahun Pengabdian
                        </span>
                    </div>
                @endif
                <div class="voucher-row">
                    <span class="voucher-label">Status Kepesertaan</span>
                    <span class="voucher-val">
                        @if($verifikasi->pensiunan->status === 'aktif')
                            <span class="badge-status badge-status-success">Aktif</span>
                        @else
                            <span class="badge-status badge-status-danger">Nonaktif</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Rekening Pensiunan Terkait --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-bank2 text-primary" aria-hidden="true"></i>
            <span>Rekening Bank Penyaluran yang Diajukan</span>
        </h2>
        <span class="small text-muted">{{ $verifikasi->pensiunan->rekening->count() }} Rekening Terdaftar</span>
    </div>
    <div class="corporate-card-body p-0">
        @if($verifikasi->pensiunan->rekening->count() > 0)
            <div class="table-responsive">
                <table class="table-corporate">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Lembaga Bank</th>
                            <th>Nomor Rekening</th>
                            <th>Atas Nama Pemilik</th>
                            <th class="text-center pe-4">Status Rekening</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifikasi->pensiunan->rekening as $rek)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-semibold px-2 py-1">
                                        <i class="bi bi-bank2 me-1 text-primary" aria-hidden="true"></i>
                                        {{ $rek->nama_bank }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold font-monospace fs-6 text-dark">{{ $rek->nomor_rekening }}</span>
                                </td>
                                <td>
                                    <span class="small text-dark fw-medium">{{ $rek->atas_nama }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    @if($rek->status === 'aktif')
                                        <span class="badge-status badge-status-success">Aktif</span>
                                    @else
                                        <span class="badge-status badge-status-danger">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-4">
                <div class="empty-state-icon"><i class="bi bi-credit-card"></i></div>
                <div class="empty-state-title">Rekening Belum Terdaftar</div>
                <p class="empty-state-text">Pensiunan ini belum memiliki rekening bank terdaftar di sistem.</p>
            </div>
        @endif
    </div>
</div>

{{-- Modal Terima Data --}}
<div class="modal fade" id="modalTerima" tabindex="-1" aria-labelledby="modalTerimaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <form method="POST" action="{{ route('hrd.verifikasi.proses', $verifikasi) }}">
                @csrf
                <input type="hidden" name="status" value="diterima">

                <div class="modal-header border-bottom pb-3">
                    <h5 class="modal-title fw-bold text-success fs-6" id="modalTerimaLabel">
                        <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
                        Persetujuan Verifikasi Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-2">Anda akan menyetujui dan memvalidasi berkas purnabakti:</p>
                    <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-success-bg); border: 1px solid var(--status-success-border);">
                        <div class="fw-bold text-success">{{ $verifikasi->pensiunan->nama }}</div>
                        <div class="small text-muted font-monospace">NIP: {{ $verifikasi->pensiunan->nip }}</div>
                    </div>

                    <div class="mb-2">
                        <label for="catatan_terima" class="form-label-corporate">Catatan Verifikator (Opsional)</label>
                        <textarea class="form-control form-control-corporate" id="catatan_terima" name="catatan" rows="3"
                                  placeholder="Contoh: Dokumen lengkap, SK Pensiun dan buku tabungan sah.">{{ old('catatan', $verifikasi->status === 'diterima' ? $verifikasi->catatan : '') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-success btn-sm px-3">
                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                        Ya, Setujui Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tolak Data --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <form method="POST" action="{{ route('hrd.verifikasi.proses', $verifikasi) }}">
                @csrf
                <input type="hidden" name="status" value="ditolak">

                <div class="modal-header border-bottom pb-3">
                    <h5 class="modal-title fw-bold text-danger fs-6" id="modalTolakLabel">
                        <i class="bi bi-x-circle-fill me-2" aria-hidden="true"></i>
                        Penolakan Verifikasi Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-2">Anda akan menolak pengajuan verifikasi purnabakti:</p>
                    <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                        <div class="fw-bold text-danger">{{ $verifikasi->pensiunan->nama }}</div>
                        <div class="small text-muted font-monospace">NIP: {{ $verifikasi->pensiunan->nip }}</div>
                    </div>

                    <div class="mb-2">
                        <label for="catatan_tolak" class="form-label-corporate text-danger">Alasan Penolakan (Wajib Diisi) <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-corporate @error('catatan') is-invalid @enderror" id="catatan_tolak" name="catatan" rows="3"
                                  placeholder="Sebutkan kendala atau dokumen yang belum lengkap..." required>{{ old('catatan', $verifikasi->status === 'ditolak' ? $verifikasi->catatan : '') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-danger btn-sm px-3">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                        Ya, Tolak Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

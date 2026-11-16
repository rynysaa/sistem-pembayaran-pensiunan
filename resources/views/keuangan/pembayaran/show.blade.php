@extends('layouts.app')

@section('title', 'Detail Transaksi Pembayaran - ' . $pembayaran->pensiunan->nama . ' (' . $pembayaran->periode . ')')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-keuangan">Slip Pembayaran</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">Periode: {{ $pembayaran->periode }}</span>
        </div>
        <h1 class="corporate-header-title">Slip Penyaluran: {{ $pembayaran->pensiunan->nama }}</h1>
        <p class="corporate-header-sub">
            Bukti instruksi dan status penyaluran hak pensiun PT Jasa Marga (Persero) Tbk.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
</div>

{{-- Status & Quick Action Banner --}}
<div class="corporate-card mb-4 p-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box" style="width: 50px; height: 50px; font-size: 1.5rem;
                @if($pembayaran->status === 'berhasil') background-color: var(--status-success-bg); color: var(--status-success-text);
                @elseif($pembayaran->status === 'diproses') background-color: var(--status-info-bg); color: var(--status-info-text);
                @elseif($pembayaran->status === 'menunggu') background-color: var(--status-warning-bg); color: var(--status-warning-text);
                @else background-color: var(--status-danger-bg); color: var(--status-danger-text);
                @endif">
                @if($pembayaran->status === 'berhasil')
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                @elseif($pembayaran->status === 'diproses')
                    <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                @elseif($pembayaran->status === 'menunggu')
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                @else
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                @endif
            </div>

            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="small text-muted fw-semibold">Status Transaksi:</span>
                    @if($pembayaran->status === 'berhasil')
                        <span class="badge-status badge-status-success fs-6">Selesai Ditransfer</span>
                    @elseif($pembayaran->status === 'diproses')
                        <span class="badge-status badge-status-info fs-6">Dalam Proses Kliring</span>
                    @elseif($pembayaran->status === 'menunggu')
                        <span class="badge-status badge-status-warning fs-6">Menunggu Eksekusi</span>
                    @else
                        <span class="badge-status badge-status-danger fs-6">Transaksi Gagal</span>
                    @endif
                </div>
                <div class="small text-muted">
                    @if($pembayaran->status === 'berhasil')
                        Dana sukses disalurkan pada <strong>{{ $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->translatedFormat('d F Y') : '-' }}</strong>
                        @if($pembayaran->processor)
                            oleh petugas <strong>{{ $pembayaran->processor->name }}</strong>
                        @endif
                    @elseif($pembayaran->status === 'diproses')
                        Sedang dalam kliring bank oleh petugas <strong>{{ $pembayaran->processor->name ?? Auth::user()->name }}</strong>.
                    @elseif($pembayaran->status === 'gagal')
                        Transaksi mengalami kendala. Periksa alasan kegagalan pada bagian catatan.
                    @else
                        Instruksi pembayaran telah diterbitkan, menunggu eksekusi perbankan.
                    @endif
                </div>
            </div>
        </div>

        {{-- Execution Buttons --}}
        <div class="d-flex gap-2">
            @if($pembayaran->status === 'menunggu')
                <form method="POST" action="{{ route('keuangan.pembayaran.proses', $pembayaran) }}" class="m-0">
                    @csrf
                    <input type="hidden" name="status" value="diproses">
                    <button type="submit" class="btn btn-jm-blue btn-sm px-3">
                        <i class="bi bi-arrow-repeat me-1" aria-hidden="true"></i>
                        <span>Mulai Proses Transfer</span>
                    </button>
                </form>
                <button type="button" class="btn btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalGagal">
                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                    <span>Tandai Gagal</span>
                </button>
            @elseif($pembayaran->status === 'diproses')
                <button type="button" class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalBerhasil">
                    <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                    <span>Konfirmasi Berhasil</span>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalGagal">
                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                    <span>Tandai Gagal</span>
                </button>
            @elseif($pembayaran->status === 'gagal')
                <form method="POST" action="{{ route('keuangan.pembayaran.proses', $pembayaran) }}" class="m-0">
                    @csrf
                    <input type="hidden" name="status" value="diproses">
                    <button type="submit" class="btn btn-jm-gold btn-sm px-3">
                        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>
                        <span>Coba Proses Ulang</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Remittance Voucher View --}}
<div class="row g-4 mb-4">
    {{-- Voucher / Rincian Penyaluran --}}
    <div class="col-12 col-lg-7">
        <div class="voucher-box">
            <div class="voucher-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="brand-text-title text-dark fs-6">SLIP PENYALURAN MANFAAT PENSIUN</div>
                    <div class="small text-muted">PT Jasa Marga (Persero) Tbk - Divisi Perbendaharaan</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark border font-monospace">REF-{{ $pembayaran->id }}-{{ str_replace('-', '', $pembayaran->periode) }}</span>
                </div>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">Periode Pembayaran</span>
                <span class="voucher-val font-monospace fs-6 text-dark">{{ $pembayaran->periode }}</span>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">Jumlah Bersih Ditransfer</span>
                <span class="voucher-val fs-4 fw-bold text-success" style="font-variant-numeric: tabular-nums;">
                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </span>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">Tanggal Pelaksanaan Transfer</span>
                <span class="voucher-val">
                    {{ $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->translatedFormat('d F Y') : 'Menunggu Pelaksanaan' }}
                </span>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">Petugas Keuangan Pemroses</span>
                <span class="voucher-val">
                    {{ $pembayaran->processor ? $pembayaran->processor->name : 'Belum Ditugaskan' }}
                </span>
            </div>

            <div class="voucher-row">
                <span class="voucher-label">Waktu Pembuatan Instruksi</span>
                <span class="voucher-val small text-muted">
                    {{ $pembayaran->created_at ? $pembayaran->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                </span>
            </div>

            @if($pembayaran->keterangan)
                <div class="mt-3 pt-3 border-top">
                    <span class="voucher-label d-block mb-1">Catatan / Bukti Referensi:</span>
                    <div class="p-3 rounded-2 small text-dark" style="background-color: #F8FAFC; border: 1px solid var(--jm-border);">
                        {{ $pembayaran->keterangan }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Penerima & Rekening Tujuan --}}
    <div class="col-12 col-lg-5">
        {{-- Data Penerima --}}
        <div class="corporate-card mb-4">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-person-check text-primary" aria-hidden="true"></i>
                    <span>Identitas Pensiunan Penerima</span>
                </h2>
            </div>
            <div class="corporate-card-body">
                <div class="voucher-row">
                    <span class="voucher-label">Nama Lengkap</span>
                    <span class="voucher-val text-dark fw-bold">{{ $pembayaran->pensiunan->nama }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">NIP</span>
                    <span class="voucher-val font-monospace text-primary">{{ $pembayaran->pensiunan->nip }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">NIK (KTP)</span>
                    <span class="voucher-val font-monospace">{{ $pembayaran->pensiunan->nik ?? '-' }}</span>
                </div>
                <div class="voucher-row">
                    <span class="voucher-label">Jabatan Terakhir</span>
                    <span class="voucher-val">{{ $pembayaran->pensiunan->jabatan_terakhir }}</span>
                </div>
            </div>
        </div>

        {{-- Rekening Tujuan --}}
        <div class="corporate-card">
            <div class="corporate-card-header">
                <h2 class="corporate-card-title">
                    <i class="bi bi-bank2 text-secondary" aria-hidden="true"></i>
                    <span>Rekening Bank Penerima</span>
                </h2>
            </div>
            <div class="corporate-card-body">
                @php
                    $rek = $pembayaran->pensiunan->rekening->where('status', 'aktif')->first() ?? $pembayaran->pensiunan->rekening->first();
                @endphp
                @if($rek)
                    <div class="voucher-row">
                        <span class="voucher-label">Lembaga Perbankan</span>
                        <span class="voucher-val fw-bold text-primary">{{ $rek->nama_bank }}</span>
                    </div>
                    <div class="voucher-row">
                        <span class="voucher-label">Nomor Rekening</span>
                        <span class="voucher-val font-monospace fs-6 text-dark">{{ $rek->nomor_rekening }}</span>
                    </div>
                    <div class="voucher-row">
                        <span class="voucher-label">Atas Nama Rekening</span>
                        <span class="voucher-val fw-semibold">{{ $rek->atas_nama }}</span>
                    </div>
                    <div class="voucher-row">
                        <span class="voucher-label">Status Rekening</span>
                        <span class="voucher-val">
                            @if($rek->status === 'aktif')
                                <span class="badge-status badge-status-success">Aktif</span>
                            @else
                                <span class="badge-status badge-status-danger">Nonaktif</span>
                            @endif
                        </span>
                    </div>
                @else
                    <div class="empty-state py-3">
                        <div class="empty-state-icon"><i class="bi bi-exclamation-triangle"></i></div>
                        <p class="empty-state-text">Pensiunan ini belum memiliki rekening bank aktif terdaftar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Berhasil --}}
<div class="modal fade" id="modalBerhasil" tabindex="-1" aria-labelledby="modalBerhasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <form method="POST" action="{{ route('keuangan.pembayaran.proses', $pembayaran) }}">
                @csrf
                <input type="hidden" name="status" value="berhasil">

                <div class="modal-header border-bottom pb-3">
                    <h5 class="modal-title fw-bold text-success fs-6" id="modalBerhasilLabel">
                        <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
                        Konfirmasi Pembayaran Berhasil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-2">Pastikan dana telah berhasil didebit dan masuk ke rekening penerima:</p>
                    <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-success-bg); border: 1px solid var(--status-success-border);">
                        <div class="small text-muted">Penerima:</div>
                        <div class="fw-bold text-dark">{{ $pembayaran->pensiunan->nama }} ({{ $pembayaran->pensiunan->nip }})</div>
                        <div class="small text-muted mt-2">Nominal Ditransfer:</div>
                        <div class="fs-5 fw-bold text-success font-monospace">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
                        <div class="small text-muted mt-1">Periode: <strong>{{ $pembayaran->periode }}</strong></div>
                    </div>

                    <div class="mb-2">
                        <label for="keterangan_berhasil" class="form-label-corporate">Nomor Referensi Bank / Catatan (Opsional)</label>
                        <textarea class="form-control form-control-corporate" id="keterangan_berhasil" name="keterangan" rows="2"
                                  placeholder="Contoh: No. Ref Kliring BI-FAST: TRX-202611-00892. Berhasil masuk.">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">
                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                        Ya, Konfirmasi Berhasil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Gagal --}}
<div class="modal fade" id="modalGagal" tabindex="-1" aria-labelledby="modalGagalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content corporate-card">
            <form method="POST" action="{{ route('keuangan.pembayaran.proses', $pembayaran) }}">
                @csrf
                <input type="hidden" name="status" value="gagal">

                <div class="modal-header border-bottom pb-3">
                    <h5 class="modal-title fw-bold text-danger fs-6" id="modalGagalLabel">
                        <i class="bi bi-x-circle-fill me-2" aria-hidden="true"></i>
                        Tandai Pembayaran Gagal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-2">Anda akan menandai transaksi ini sebagai <strong>GAGAL</strong>:</p>
                    <div class="p-3 rounded-2 mb-3" style="background-color: var(--status-danger-bg); border: 1px solid var(--status-danger-border);">
                        <strong class="text-danger">{{ $pembayaran->pensiunan->nama }}</strong>
                        <div class="small text-muted font-monospace">NIP: {{ $pembayaran->pensiunan->nip }} - Periode: {{ $pembayaran->periode }}</div>
                    </div>

                    <div class="mb-2">
                        <label for="keterangan_gagal" class="form-label-corporate text-danger">Alasan Kegagalan Transfer (Wajib Diisi) <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-corporate @error('keterangan') is-invalid @enderror" id="keterangan_gagal" name="keterangan" rows="3"
                                  placeholder="Contoh: Rekening dormant / rekening ditutup / penolakan kliring bank." required>{{ old('keterangan', $pembayaran->status === 'gagal' ? $pembayaran->keterangan : '') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-jm-outline btn-sm" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                        Ya, Tandai Gagal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

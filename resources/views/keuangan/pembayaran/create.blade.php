@extends('layouts.app')

@section('title', 'Terbitkan Instruksi Pembayaran Pensiun')

@section('sidebar')
    @include('keuangan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-keuangan">Penerbitan Tagihan</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">Divisi Keuangan PT Jasa Marga</span>
        </div>
        <h1 class="corporate-header-title">Terbitkan Pembayaran Pensiun Baru</h1>
        <p class="corporate-header-sub">
            Penerbitan instruksi penyaluran dana manfaat pensiun bagi penerima yang telah terverifikasi sah oleh HRD.
        </p>
    </div>
    <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        <span>Kembali ke Daftar</span>
    </a>
</div>

{{-- Validation Error Alert --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5" aria-hidden="true"></i>
            <strong>Gagal menerbitkan pembayaran:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<form method="POST" action="{{ route('keuangan.pembayaran.store') }}" novalidate>
    @csrf

    {{-- Step 1: Penerima Pensiun --}}
    <div class="corporate-card mb-4">
        <div class="corporate-card-header">
            <h2 class="corporate-card-title">
                <i class="bi bi-person-check text-primary" aria-hidden="true"></i>
                <span>1. Pilih Penerima Pensiun Terverifikasi</span>
            </h2>
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                Data Terverifikasi HRD
            </span>
        </div>
        <div class="corporate-card-body">
            <div class="mb-3">
                <label for="pensiunan_id" class="form-label-corporate">
                    Nama Pensiunan / NIP <span class="text-danger">*</span>
                </label>
                <select id="pensiunan_id"
                        name="pensiunan_id"
                        class="form-select form-select-corporate @error('pensiunan_id') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih pensiunan yang telah disetujui HRD --</option>
                    @foreach($pensiunans as $p)
                        @php
                            $rekUtama = $p->rekening->first();
                        @endphp
                        <option value="{{ $p->id }}" {{ old('pensiunan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (NIP: {{ $p->nip }}) -
                            @if($rekUtama)
                                Rekening: {{ $rekUtama->nama_bank }} ({{ $rekUtama->nomor_rekening }})
                            @else
                                (Belum memiliki rekening terdaftar)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('pensiunan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text text-muted small mt-1">
                    <i class="bi bi-info-circle me-1"></i>
                    Sistem hanya menampilkan pensiunan dengan status verifikasi <strong>DITERIMA</strong> oleh divisi HRD.
                </div>
            </div>
        </div>
    </div>

    {{-- Step 2: Rincian Pembayaran --}}
    <div class="corporate-card mb-4">
        <div class="corporate-card-header">
            <h2 class="corporate-card-title">
                <i class="bi bi-cash-stack text-secondary" aria-hidden="true"></i>
                <span>2. Parameter Penyaluran &amp; Nominal Dana</span>
            </h2>
        </div>
        <div class="corporate-card-body">
            <div class="row g-3">
                {{-- Periode --}}
                <div class="col-md-5">
                    <label for="periode" class="form-label-corporate">
                        Periode Pembayaran (Tahun-Bulan) <span class="text-danger">*</span>
                    </label>
                    <input type="month"
                           id="periode"
                           name="periode"
                           class="form-control form-control-corporate font-monospace @error('periode') is-invalid @enderror"
                           value="{{ old('periode', $defaultPeriode) }}"
                           required>
                    @error('periode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted small mt-1">Contoh: 2026-11 untuk bulan berjalan.</div>
                </div>

                {{-- Nominal Pembayaran --}}
                <div class="col-md-7">
                    <label for="jumlah" class="form-label-corporate">
                        Nominal Manfaat Ditransfer (Rupiah) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-corporate">
                        <span class="input-group-text fw-bold">Rp</span>
                        <input type="number"
                               id="jumlah"
                               name="jumlah"
                               step="1000"
                               min="1"
                               class="form-control form-control-corporate @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah') }}"
                               placeholder="Contoh: 4500000"
                               required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text text-muted small mt-1">
                        Masukkan nominal angka riil tanpa titik atau koma.
                    </div>

                    {{-- Quick Nominal Buttons --}}
                    <div class="d-flex flex-wrap gap-2 mt-2 align-items-center">
                        <span class="small text-muted">Nominal cepat:</span>
                        <button type="button" class="btn btn-jm-outline btn-sm py-0 px-2" onclick="document.getElementById('jumlah').value='3500000'">Rp 3.500.000</button>
                        <button type="button" class="btn btn-jm-outline btn-sm py-0 px-2" onclick="document.getElementById('jumlah').value='4500000'">Rp 4.500.000</button>
                        <button type="button" class="btn btn-jm-outline btn-sm py-0 px-2" onclick="document.getElementById('jumlah').value='5500000'">Rp 5.500.000</button>
                        <button type="button" class="btn btn-jm-outline btn-sm py-0 px-2" onclick="document.getElementById('jumlah').value='8000000'">Rp 8.000.000</button>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="col-12">
                    <label for="keterangan" class="form-label-corporate">
                        Keterangan Instruksi Transfer <span class="text-muted">(Opsional)</span>
                    </label>
                    <textarea id="keterangan"
                              name="keterangan"
                              rows="3"
                              class="form-control form-control-corporate @error('keterangan') is-invalid @enderror"
                              placeholder="Catatan transfer, contoh: Penyaluran manfaat pensiun bulanan reguler.">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('keuangan.pembayaran.index') }}" class="btn btn-jm-outline">
            <i class="bi bi-x-circle" aria-hidden="true"></i>
            <span>Batalkan</span>
        </a>
        <button type="submit" class="btn btn-jm-primary px-4">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            <span>Terbitkan Instruksi Pembayaran</span>
        </button>
    </div>
</form>

@endsection

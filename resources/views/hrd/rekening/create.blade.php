@extends('layouts.app')

@section('title', 'Tambah Rekening Pensiunan')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Administrasi Rekening</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">PT Jasa Marga (Persero) Tbk</span>
        </div>
        <h1 class="corporate-header-title">Tambah Rekening Pensiunan</h1>
        <p class="corporate-header-sub">Daftarkan akun perbankan resmi untuk tujuan penyaluran manfaat pensiun.</p>
    </div>
    <a href="{{ route('hrd.rekening.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        <span>Kembali ke Daftar</span>
    </a>
</div>

{{-- Validation Errors Alert --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5" aria-hidden="true"></i>
            <strong>Terdapat kesalahan pada isian formulir:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<form method="POST" action="{{ route('hrd.rekening.store') }}" novalidate>
    @csrf

    @include('hrd.rekening._form')

    {{-- Action Footer --}}
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('hrd.rekening.index') }}" class="btn btn-jm-outline">
            <i class="bi bi-x-circle" aria-hidden="true"></i>
            <span>Batalkan</span>
        </a>
        <button type="submit" class="btn btn-jm-primary px-4">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            <span>Simpan Rekening Bank</span>
        </button>
    </div>
</form>

@endsection

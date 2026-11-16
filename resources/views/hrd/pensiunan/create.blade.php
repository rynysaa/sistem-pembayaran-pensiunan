@extends('layouts.app')

@section('title', 'Tambah Data Pensiunan Baru')

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Formulir SDM</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">Registrasi Karyawan Purna Tugas</span>
        </div>
        <h1 class="corporate-header-title">Tambah Data Pensiunan Baru</h1>
        <p class="corporate-header-sub">Lengkapi seluruh data identitas dan informasi kepegawaian pensiunan.</p>
    </div>
    <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        <span>Kembali ke Direktori</span>
    </a>
</div>

<form method="POST" action="{{ route('hrd.pensiunan.store') }}" novalidate>
    @csrf

    @include('hrd.pensiunan._form')

    {{-- Action Buttons Footer --}}
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline">
            <i class="bi bi-x-circle" aria-hidden="true"></i>
            <span>Batalkan</span>
        </a>
        <button type="submit" class="btn btn-jm-primary px-4">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            <span>Simpan Data Pensiunan</span>
        </button>
    </div>
</form>

@endsection

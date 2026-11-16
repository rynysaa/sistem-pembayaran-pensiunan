@extends('layouts.app')

@section('title', 'Edit Data Pensiunan - ' . $pensiunan->nama)

@section('sidebar')
    @include('hrd.pensiunan._sidebar')
@endsection

@section('content')

{{-- Header Banner --}}
<div class="corporate-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="role-pill role-pill-hrd">Pembaruan Data</span>
            <span class="text-white-50 small">|</span>
            <span class="text-white-50 small">NIP: {{ $pensiunan->nip }}</span>
        </div>
        <h1 class="corporate-header-title">Edit Data: {{ $pensiunan->nama }}</h1>
        <p class="corporate-header-sub">Perbarui informasi identitas pribadi atau data riwayat kepegawaian purnabakti.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hrd.pensiunan.show', $pensiunan) }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
            <i class="bi bi-eye" aria-hidden="true"></i>
            <span>Lihat Detail</span>
        </a>
        <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline btn-sm bg-white text-dark">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<form method="POST" action="{{ route('hrd.pensiunan.update', $pensiunan) }}" novalidate>
    @csrf
    @method('PUT')

    @include('hrd.pensiunan._form', ['pensiunan' => $pensiunan])

    {{-- Action Buttons Footer --}}
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('hrd.pensiunan.index') }}" class="btn btn-jm-outline">
            <i class="bi bi-x-circle" aria-hidden="true"></i>
            <span>Batalkan</span>
        </a>
        <button type="submit" class="btn btn-jm-gold px-4">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

@endsection

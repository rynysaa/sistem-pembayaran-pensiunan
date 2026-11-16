{{-- Pemilik Rekening --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-person-badge text-primary" aria-hidden="true"></i>
            <span>Pemilik Rekening Pensiunan</span>
        </h2>
        <span class="small text-muted">Hubungkan nomor rekening dengan pensiunan terdaftar</span>
    </div>
    <div class="corporate-card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label-corporate" for="pensiunan_id">
                    Pilih Karyawan Pensiunan <span class="text-danger">*</span>
                </label>
                <select id="pensiunan_id"
                        name="pensiunan_id"
                        class="form-select form-select-corporate @error('pensiunan_id') is-invalid @enderror"
                        {{ isset($rekening) ? 'disabled' : '' }}
                        required>
                    <option value="">-- Pilih pensiunan --</option>
                    @foreach($pensiunans as $p)
                        <option value="{{ $p->id }}"
                            {{ old('pensiunan_id', $selectedPensiunan?->id ?? ($rekening->pensiunan_id ?? '')) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (NIP: {{ $p->nip }}) - {{ $p->jabatan_terakhir }}
                        </option>
                    @endforeach
                </select>
                @isset($rekening)
                    <input type="hidden" name="pensiunan_id" value="{{ $rekening->pensiunan_id }}">
                    <div class="form-text text-muted mt-1 small">
                        <i class="bi bi-lock me-1"></i>
                        Identitas pensiunan pemilik rekening telah terkunci untuk menjaga keutuhan riwayat transaksi.
                    </div>
                @endisset
                @error('pensiunan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Data Perbankan --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-bank2 text-secondary" aria-hidden="true"></i>
            <span>Informasi Lembaga Perbankan &amp; Akun</span>
        </h2>
    </div>
    <div class="corporate-card-body">
        <div class="row g-3">
            {{-- Nama Bank --}}
            <div class="col-md-5">
                <label class="form-label-corporate" for="nama_bank">
                    Nama Lembaga Bank <span class="text-danger">*</span>
                </label>
                <select id="nama_bank"
                        name="nama_bank"
                        class="form-select form-select-corporate @error('nama_bank') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih bank penerima --</option>
                    @php
                        $banks = [
                            'Bank Mandiri', 'Bank BRI', 'Bank BNI', 'Bank BCA',
                            'Bank BTN', 'Bank CIMB Niaga', 'Bank Danamon',
                            'Bank Permata', 'Bank Syariah Indonesia (BSI)',
                            'Bank Jatim', 'Bank Jabar Banten (BJB)', 'Lainnya',
                        ];
                        $selectedBank = old('nama_bank', $rekening->nama_bank ?? '');
                    @endphp
                    @foreach($banks as $bank)
                        <option value="{{ $bank }}" {{ $selectedBank === $bank ? 'selected' : '' }}>
                            {{ $bank }}
                        </option>
                    @endforeach
                </select>
                @error('nama_bank')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nomor Rekening --}}
            <div class="col-md-7">
                <label class="form-label-corporate" for="nomor_rekening">
                    Nomor Rekening Bank <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nomor_rekening"
                       name="nomor_rekening"
                       class="form-control form-control-corporate font-monospace @error('nomor_rekening') is-invalid @enderror"
                       value="{{ old('nomor_rekening', $rekening->nomor_rekening ?? '') }}"
                       placeholder="Contoh: 1234567890"
                       required>
                @error('nomor_rekening')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Atas Nama --}}
            <div class="col-md-8">
                <label class="form-label-corporate" for="atas_nama">
                    Nama Pemilik Rekening (Sesuai Buku Tabungan) <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="atas_nama"
                       name="atas_nama"
                       class="form-control form-control-corporate @error('atas_nama') is-invalid @enderror"
                       value="{{ old('atas_nama', $rekening->atas_nama ?? '') }}"
                       placeholder="Nama lengkap pemilik rekening"
                       required>
                <div class="form-text text-muted small mt-1">
                    Pastikan ejaan nama sama persis dengan yang tercetak di buku tabungan atau rekening koran.
                </div>
                @error('atas_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-4">
                <label class="form-label-corporate" for="status">
                    Status Rekening <span class="text-danger">*</span>
                </label>
                <select id="status"
                        name="status"
                        class="form-select form-select-corporate @error('status') is-invalid @enderror"
                        required>
                    <option value="aktif" {{ old('status', $rekening->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>
                        Aktif (Digunakan Penyaluran)
                    </option>
                    <option value="nonaktif" {{ old('status', $rekening->status ?? '') === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif (Ditangguhkan)
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

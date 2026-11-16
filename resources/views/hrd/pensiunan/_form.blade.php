{{-- Data Identitas Pribadi --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-person-vcard text-primary" aria-hidden="true"></i>
            <span>Data Identitas Pribadi</span>
        </h2>
        <span class="small text-muted">Kolom bertanda <span class="text-danger">*</span> wajib diisi</span>
    </div>
    <div class="corporate-card-body">
        <div class="row g-3">
            {{-- NIP --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="nip">
                    Nomor Induk Pegawai (NIP) <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nip"
                       name="nip"
                       class="form-control form-control-corporate @error('nip') is-invalid @enderror"
                       value="{{ old('nip', $pensiunan->nip ?? '') }}"
                       placeholder="Contoh: JM-001-1985"
                       required>
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- NIK --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="nik">
                    Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nik"
                       name="nik"
                       class="form-control form-control-corporate @error('nik') is-invalid @enderror"
                       value="{{ old('nik', $pensiunan->nik ?? '') }}"
                       placeholder="16 digit nomor KTP"
                       maxlength="16"
                       required>
                @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Lengkap --}}
            <div class="col-md-8">
                <label class="form-label-corporate" for="nama">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nama"
                       name="nama"
                       class="form-control form-control-corporate @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $pensiunan->nama ?? '') }}"
                       placeholder="Nama lengkap sesuai KTP"
                       required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="col-md-4">
                <label class="form-label-corporate" for="jenis_kelamin">
                    Jenis Kelamin <span class="text-danger">*</span>
                </label>
                <select id="jenis_kelamin"
                        name="jenis_kelamin"
                        class="form-select form-select-corporate @error('jenis_kelamin') is-invalid @enderror"
                        required>
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $pensiunan->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $pensiunan->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
                @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="tanggal_lahir">
                    Tanggal Lahir <span class="text-danger">*</span>
                </label>
                <input type="date"
                       id="tanggal_lahir"
                       name="tanggal_lahir"
                       class="form-control form-control-corporate @error('tanggal_lahir') is-invalid @enderror"
                       value="{{ old('tanggal_lahir', isset($pensiunan) ? $pensiunan->tanggal_lahir?->format('Y-m-d') : '') }}"
                       required>
                @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nomor Telepon --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="no_telepon">
                    Nomor Telepon / WhatsApp <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="no_telepon"
                       name="no_telepon"
                       class="form-control form-control-corporate @error('no_telepon') is-invalid @enderror"
                       value="{{ old('no_telepon', $pensiunan->no_telepon ?? '') }}"
                       placeholder="Contoh: 081234567890"
                       required>
                @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="col-12">
                <label class="form-label-corporate" for="alamat">
                    Alamat Domisili Lengkap <span class="text-danger">*</span>
                </label>
                <textarea id="alamat"
                          name="alamat"
                          rows="3"
                          class="form-control form-control-corporate @error('alamat') is-invalid @enderror"
                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos"
                          required>{{ old('alamat', $pensiunan->alamat ?? '') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Kepegawaian --}}
<div class="corporate-card mb-4">
    <div class="corporate-card-header">
        <h2 class="corporate-card-title">
            <i class="bi bi-briefcase text-secondary" aria-hidden="true"></i>
            <span>Riwayat Kepegawaian &amp; Purna Tugas</span>
        </h2>
    </div>
    <div class="corporate-card-body">
        <div class="row g-3">
            {{-- Jabatan Terakhir --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="jabatan_terakhir">
                    Jabatan Terakhir <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="jabatan_terakhir"
                       name="jabatan_terakhir"
                       class="form-control form-control-corporate @error('jabatan_terakhir') is-invalid @enderror"
                       value="{{ old('jabatan_terakhir', $pensiunan->jabatan_terakhir ?? '') }}"
                       placeholder="Jabatan dinas terakhir sebelum pensiun"
                       required>
                @error('jabatan_terakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Kerja --}}
            <div class="col-md-6">
                <label class="form-label-corporate" for="unit_kerja">
                    Unit Kerja / Cabang Tol
                </label>
                <input type="text"
                       id="unit_kerja"
                       name="unit_kerja"
                       class="form-control form-control-corporate @error('unit_kerja') is-invalid @enderror"
                       value="{{ old('unit_kerja', $pensiunan->unit_kerja ?? '') }}"
                       placeholder="Contoh: Kantor Pusat, Ruas Jakarta-Cikampek">
                @error('unit_kerja')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Mulai Kerja --}}
            <div class="col-md-4">
                <label class="form-label-corporate" for="tanggal_mulai_kerja">
                    Tanggal Mulai Bekerja (TMT Awal)
                </label>
                <input type="date"
                       id="tanggal_mulai_kerja"
                       name="tanggal_mulai_kerja"
                       class="form-control form-control-corporate @error('tanggal_mulai_kerja') is-invalid @enderror"
                       value="{{ old('tanggal_mulai_kerja', isset($pensiunan) ? $pensiunan->tanggal_mulai_kerja?->format('Y-m-d') : '') }}">
                @error('tanggal_mulai_kerja')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Pensiun --}}
            <div class="col-md-4">
                <label class="form-label-corporate" for="tanggal_pensiun">
                    Tanggal Pensiun (TMT Pensiun) <span class="text-danger">*</span>
                </label>
                <input type="date"
                       id="tanggal_pensiun"
                       name="tanggal_pensiun"
                       class="form-control form-control-corporate @error('tanggal_pensiun') is-invalid @enderror"
                       value="{{ old('tanggal_pensiun', isset($pensiunan) ? $pensiunan->tanggal_pensiun?->format('Y-m-d') : '') }}"
                       required>
                @error('tanggal_pensiun')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-4">
                <label class="form-label-corporate" for="status">
                    Status Kepesertaan <span class="text-danger">*</span>
                </label>
                <select id="status"
                        name="status"
                        class="form-select form-select-corporate @error('status') is-invalid @enderror"
                        required>
                    <option value="aktif" {{ old('status', $pensiunan->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif" {{ old('status', $pensiunan->status ?? '') === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

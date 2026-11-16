<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Pensiunan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PensiunanController extends Controller
{
    /**
     * Daftar pensiunan dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Pensiunan::query()->latest();

        // Pencarian NIP, NIK, atau nama
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pensiunan = $query->paginate(10)->withQueryString();

        $totalAktif    = Pensiunan::where('status', 'aktif')->count();
        $totalNonaktif = Pensiunan::where('status', 'nonaktif')->count();
        $totalSemua    = Pensiunan::count();

        return view('hrd.pensiunan.index', compact(
            'pensiunan', 'totalAktif', 'totalNonaktif', 'totalSemua'
        ));
    }

    /**
     * Form tambah pensiunan.
     */
    public function create(): View
    {
        return view('hrd.pensiunan.create');
    }

    /**
     * Simpan pensiunan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $pensiunan = Pensiunan::create($validated);
        $pensiunan->verifikasi()->create(['status' => 'menunggu']);

        return redirect()
            ->route('hrd.pensiunan.index')
            ->with('success', 'Data pensiunan berhasil ditambahkan.');
    }

    /**
     * Detail pensiunan.
     */
    public function show(Pensiunan $pensiunan): View
    {
        $pensiunan->load(['rekening', 'latestVerifikasi']);

        return view('hrd.pensiunan.show', compact('pensiunan'));
    }

    /**
     * Form edit pensiunan.
     */
    public function edit(Pensiunan $pensiunan): View
    {
        return view('hrd.pensiunan.edit', compact('pensiunan'));
    }

    /**
     * Simpan perubahan data pensiunan.
     */
    public function update(Request $request, Pensiunan $pensiunan): RedirectResponse
    {
        $validated = $request->validate(
            $this->rules($pensiunan->id),
            $this->messages()
        );

        $pensiunan->update($validated);

        return redirect()
            ->route('hrd.pensiunan.index')
            ->with('success', 'Data pensiunan berhasil diperbarui.');
    }

    /**
     * Hapus pensiunan.
     */
    public function destroy(Pensiunan $pensiunan): RedirectResponse
    {
        $nama = $pensiunan->nama;
        $pensiunan->delete();

        return redirect()
            ->route('hrd.pensiunan.index')
            ->with('success', "Data pensiunan atas nama \"{$nama}\" berhasil dihapus.");
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    /**
     * Aturan validasi — NIP & NIK unik kecuali milik data yang sedang diedit.
     */
    private function rules(?int $ignoreId = null): array
    {
        $uniqueNip = 'unique:pensiunan,nip' . ($ignoreId ? ",{$ignoreId}" : '');
        $uniqueNik = 'unique:pensiunan,nik' . ($ignoreId ? ",{$ignoreId}" : '');

        return [
            'nip'                 => ['required', 'string', 'max:50', $uniqueNip],
            'nik'                 => ['required', 'string', 'digits:16', $uniqueNik],
            'nama'                => ['required', 'string', 'max:255'],
            'jenis_kelamin'       => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir'       => ['required', 'date', 'before:today'],
            'tanggal_mulai_kerja' => ['nullable', 'date', 'before_or_equal:tanggal_pensiun'],
            'tanggal_pensiun'     => ['required', 'date'],
            'jabatan_terakhir'    => ['required', 'string', 'max:255'],
            'unit_kerja'          => ['nullable', 'string', 'max:255'],
            'alamat'              => ['required', 'string', 'max:1000'],
            'no_telepon'          => ['required', 'string', 'max:20'],
            'status'              => ['required', 'in:aktif,nonaktif'],
        ];
    }

    /**
     * Pesan validasi berbahasa Indonesia.
     */
    private function messages(): array
    {
        return [
            'nip.required'                  => 'NIP wajib diisi.',
            'nip.max'                       => 'NIP maksimal 50 karakter.',
            'nip.unique'                    => 'NIP sudah terdaftar di sistem.',
            'nik.required'                  => 'NIK wajib diisi.',
            'nik.digits'                    => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique'                    => 'NIK sudah terdaftar di sistem.',
            'nama.required'                 => 'Nama lengkap wajib diisi.',
            'nama.max'                      => 'Nama maksimal 255 karakter.',
            'jenis_kelamin.required'        => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'              => 'Pilih jenis kelamin yang valid.',
            'tanggal_lahir.required'        => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'            => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before'          => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_mulai_kerja.date'      => 'Format tanggal mulai kerja tidak valid.',
            'tanggal_mulai_kerja.before_or_equal' => 'Tanggal mulai kerja harus sebelum atau sama dengan tanggal pensiun.',
            'tanggal_pensiun.required'      => 'Tanggal pensiun wajib diisi.',
            'tanggal_pensiun.date'          => 'Format tanggal pensiun tidak valid.',
            'jabatan_terakhir.required'     => 'Jabatan terakhir wajib diisi.',
            'jabatan_terakhir.max'          => 'Jabatan terakhir maksimal 255 karakter.',
            'unit_kerja.max'                => 'Unit kerja maksimal 255 karakter.',
            'alamat.required'               => 'Alamat wajib diisi.',
            'no_telepon.required'           => 'Nomor telepon wajib diisi.',
            'no_telepon.max'                => 'Nomor telepon maksimal 20 karakter.',
            'status.required'               => 'Status wajib dipilih.',
            'status.in'                     => 'Status harus aktif atau nonaktif.',
        ];
    }
}


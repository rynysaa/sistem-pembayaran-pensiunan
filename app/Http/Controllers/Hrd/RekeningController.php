<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Pensiunan;
use App\Models\RekeningPensiunan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekeningController extends Controller
{
    /**
     * Daftar semua rekening dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $query = RekeningPensiunan::with('pensiunan')
            ->join('pensiunan', 'rekening_pensiunan.pensiunan_id', '=', 'pensiunan.id')
            ->select('rekening_pensiunan.*')
            ->latest('rekening_pensiunan.created_at');

        // Pencarian nama pensiunan, NIP, atau nama bank
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pensiunan.nama',   'like', "%{$search}%")
                  ->orWhere('pensiunan.nip',  'like', "%{$search}%")
                  ->orWhere('rekening_pensiunan.nama_bank', 'like', "%{$search}%")
                  ->orWhere('rekening_pensiunan.nomor_rekening', 'like', "%{$search}%");
            });
        }

        // Filter pensiunan tertentu
        if ($pensiunanId = $request->input('pensiunan_id')) {
            $query->where('rekening_pensiunan.pensiunan_id', $pensiunanId);
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('rekening_pensiunan.status', $status);
        }

        $rekening   = $query->paginate(10)->withQueryString();
        $pensiunans = Pensiunan::orderBy('nama')->get(['id', 'nama', 'nip']);

        $totalRekening  = RekeningPensiunan::count();
        $totalAktif     = RekeningPensiunan::where('status', 'aktif')->count();
        $totalNonaktif  = RekeningPensiunan::where('status', 'nonaktif')->count();

        return view('hrd.rekening.index', compact(
            'rekening', 'pensiunans',
            'totalRekening', 'totalAktif', 'totalNonaktif'
        ));
    }

    /**
     * Form tambah rekening.
     */
    public function create(Request $request): View
    {
        $pensiunans      = Pensiunan::orderBy('nama')->get(['id', 'nama', 'nip']);
        $selectedPensiunan = $request->input('pensiunan_id')
            ? Pensiunan::find($request->input('pensiunan_id'))
            : null;

        return view('hrd.rekening.create', compact('pensiunans', 'selectedPensiunan'));
    }

    /**
     * Simpan rekening baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        RekeningPensiunan::create($validated);

        return redirect()
            ->route('hrd.rekening.index')
            ->with('success', 'Rekening berhasil ditambahkan.');
    }

    /**
     * Form edit rekening.
     */
    public function edit(RekeningPensiunan $rekening): View
    {
        $pensiunans = Pensiunan::orderBy('nama')->get(['id', 'nama', 'nip']);

        return view('hrd.rekening.edit', compact('rekening', 'pensiunans'));
    }

    /**
     * Simpan perubahan rekening.
     */
    public function update(Request $request, RekeningPensiunan $rekening): RedirectResponse
    {
        $validated = $request->validate(
            $this->rules($rekening->id),
            $this->messages()
        );

        $rekening->update($validated);

        return redirect()
            ->route('hrd.rekening.index')
            ->with('success', 'Data rekening berhasil diperbarui.');
    }

    /**
     * Hapus rekening.
     */
    public function destroy(RekeningPensiunan $rekening): RedirectResponse
    {
        $info = "No. {$rekening->nomor_rekening} ({$rekening->nama_bank})";
        $rekening->delete();

        return redirect()
            ->route('hrd.rekening.index')
            ->with('success', "Rekening {$info} berhasil dihapus.");
    }

    /**
     * Toggle status aktif / nonaktif.
     */
    public function toggleStatus(RekeningPensiunan $rekening): RedirectResponse
    {
        $rekening->status = $rekening->status === 'aktif' ? 'nonaktif' : 'aktif';
        $rekening->save();

        $label = $rekening->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Rekening berhasil {$label}.");
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function rules(?int $ignoreId = null): array
    {
        $uniqueNoRek = 'unique:rekening_pensiunan,nomor_rekening' . ($ignoreId ? ",{$ignoreId}" : '');

        return [
            'pensiunan_id'   => ['required', 'exists:pensiunan,id'],
            'nama_bank'      => ['required', 'string', 'max:100'],
            'nomor_rekening' => ['required', 'string', 'max:50', $uniqueNoRek],
            'atas_nama'      => ['required', 'string', 'max:150'],
            'status'         => ['required', 'in:aktif,nonaktif'],
        ];
    }

    private function messages(): array
    {
        return [
            'pensiunan_id.required'   => 'Pensiunan wajib dipilih.',
            'pensiunan_id.exists'     => 'Pensiunan yang dipilih tidak ditemukan.',
            'nama_bank.required'      => 'Nama bank wajib diisi.',
            'nama_bank.max'           => 'Nama bank maksimal 100 karakter.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.max'      => 'Nomor rekening maksimal 50 karakter.',
            'nomor_rekening.unique'   => 'Nomor rekening sudah terdaftar di sistem.',
            'atas_nama.required'      => 'Nama pemilik rekening wajib diisi.',
            'atas_nama.max'           => 'Nama pemilik maksimal 150 karakter.',
            'status.required'         => 'Status wajib dipilih.',
            'status.in'               => 'Status harus aktif atau nonaktif.',
        ];
    }
}


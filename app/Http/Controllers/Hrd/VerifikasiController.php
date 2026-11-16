<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Pensiunan;
use App\Models\VerifikasiData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerifikasiController extends Controller
{
    /**
     * Halaman daftar verifikasi data pensiunan.
     */
    public function index(Request $request): View
    {
        // Pastikan setiap pensiunan yang terdaftar memiliki data verifikasi
        $unverifiedPensiunan = Pensiunan::doesntHave('verifikasi')->get();
        foreach ($unverifiedPensiunan as $p) {
            $p->verifikasi()->create(['status' => 'menunggu']);
        }

        $query = VerifikasiData::with(['pensiunan', 'verifikator'])
            ->join('pensiunan', 'verifikasi_data.pensiunan_id', '=', 'pensiunan.id')
            ->select('verifikasi_data.*');

        // Pencarian berdasarkan nama atau NIP pensiunan
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pensiunan.nama', 'like', "%{$search}%")
                  ->orWhere('pensiunan.nip', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status verifikasi
        if ($status = $request->input('status')) {
            $query->where('verifikasi_data.status', $status);
        }

        // Urutkan status 'menunggu' prioritas di atas, lalu timestamp terbaru
        $verifikasis = $query->orderByRaw("CASE WHEN verifikasi_data.status = 'menunggu' THEN 1 WHEN verifikasi_data.status = 'ditolak' THEN 2 ELSE 3 END")
            ->latest('verifikasi_data.updated_at')
            ->paginate(10)
            ->withQueryString();

        // Alias kompatibilitas
        $verifikasi = $verifikasis;

        // Ringkasan jumlah data berdasarkan status
        $totalSemua    = VerifikasiData::count();
        $totalMenunggu = VerifikasiData::where('status', 'menunggu')->count();
        $totalDiterima = VerifikasiData::where('status', 'diterima')->count();
        $totalDitolak  = VerifikasiData::where('status', 'ditolak')->count();

        return view('hrd.verifikasi.index', compact(
            'verifikasis',
            'verifikasi',
            'totalSemua',
            'totalMenunggu',
            'totalDiterima',
            'totalDitolak'
        ));
    }

    /**
     * Halaman detail verifikasi data pensiunan.
     */
    public function show(VerifikasiData $verifikasi): View
    {
        $verifikasi->load(['pensiunan.rekening', 'verifikator']);

        return view('hrd.verifikasi.show', compact('verifikasi'));
    }

    /**
     * Proses verifikasi (terima atau tolak).
     */
    public function proses(Request $request, VerifikasiData $verifikasi): RedirectResponse
    {
        $validated = $request->validate([
            'status'  => ['required', 'in:diterima,ditolak'],
            'catatan' => ['required_if:status,ditolak', 'nullable', 'string', 'max:1000'],
        ], [
            'status.required'     => 'Status verifikasi wajib ditentukan.',
            'status.in'           => 'Status verifikasi harus berupa diterima atau ditolak.',
            'catatan.required_if' => 'Catatan alasan wajib diisi apabila data pensiunan ditolak.',
            'catatan.max'         => 'Catatan maksimal 1000 karakter.',
        ]);

        $statusBerubah = $verifikasi->status !== $validated['status'];

        DB::transaction(function () use ($verifikasi, $validated, $statusBerubah) {
            $verifikasi->update([
                'status'             => $validated['status'],
                'catatan'            => $validated['catatan'] ?? null,
                'diverifikasi_oleh'  => Auth::id(),
                'tanggal_verifikasi' => now(),
            ]);

            // Kirim notifikasi otomatis ke akun pensiunan jika terhubung
            if ($statusBerubah && $verifikasi->pensiunan && $verifikasi->pensiunan->user_id) {
                if ($validated['status'] === 'diterima') {
                    \App\Models\Notifikasi::create([
                        'user_id'      => $verifikasi->pensiunan->user_id,
                        'judul'        => 'Verifikasi Data Pensiunan Diterima',
                        'pesan'        => 'Selamat! Berkas data pensiunan Anda telah diverifikasi dan DITERIMA oleh HRD.' . (! empty($validated['catatan']) ? ' Catatan: ' . $validated['catatan'] : ''),
                        'jenis'        => 'verifikasi',
                        'sudah_dibaca' => false,
                    ]);
                } elseif ($validated['status'] === 'ditolak') {
                    \App\Models\Notifikasi::create([
                        'user_id'      => $verifikasi->pensiunan->user_id,
                        'judul'        => 'Verifikasi Data Pensiunan Ditolak',
                        'pesan'        => 'Mohon maaf, berkas data pensiunan Anda DITOLAK oleh HRD. Alasan penolakan: ' . ($validated['catatan'] ?? 'Data belum memenuhi persyaratan.'),
                        'jenis'        => 'verifikasi',
                        'sudah_dibaca' => false,
                    ]);
                }
            }
        });

        $statusLabel = $validated['status'] === 'diterima' ? 'diterima' : 'ditolak';
        $namaPensiunan = $verifikasi->pensiunan->nama ?? 'Pensiunan';

        return redirect()
            ->route('hrd.verifikasi.show', $verifikasi)
            ->with('success', "Data pensiunan atas nama \"{$namaPensiunan}\" berhasil {$statusLabel}.");
    }
}


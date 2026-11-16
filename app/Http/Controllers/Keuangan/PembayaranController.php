<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Pensiunan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran pensiunan dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $query = Pembayaran::with(['pensiunan.rekening', 'processor'])
            ->join('pensiunan', 'pembayaran.pensiunan_id', '=', 'pensiunan.id')
            ->select('pembayaran.*');

        // Pencarian berdasarkan nama atau NIP pensiunan
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pensiunan.nama', 'like', "%{$search}%")
                  ->orWhere('pensiunan.nip', 'like', "%{$search}%");
            });
        }

        // Filter periode (YYYY-MM)
        if ($periode = $request->input('periode')) {
            $query->where('pembayaran.periode', $periode);
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('pembayaran.status', $status);
        }

        $pembayaran = $query->latest('pembayaran.created_at')
            ->paginate(10)
            ->withQueryString();

        // Daftar periode yang ada untuk opsi filter
        $daftarPeriode = Pembayaran::select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');

        // Ringkasan data pembayaran
        $totalPembayaran = Pembayaran::count();
        $totalMenunggu   = Pembayaran::menunggu()->count();
        $totalDiproses   = Pembayaran::diproses()->count();
        $totalBerhasil   = Pembayaran::berhasil()->count();
        $totalGagal      = Pembayaran::gagal()->count();
        $totalNominal    = Pembayaran::berhasil()->sum('jumlah');

        return view('keuangan.pembayaran.index', compact(
            'pembayaran',
            'daftarPeriode',
            'totalPembayaran',
            'totalMenunggu',
            'totalDiproses',
            'totalBerhasil',
            'totalGagal',
            'totalNominal'
        ));
    }

    /**
     * Form pembuatan pembayaran baru untuk pensiunan terverifikasi.
     */
    public function create(): View
    {
        // Hanya pensiunan yang status verifikasinya sudah 'diterima'
        $pensiunans = Pensiunan::whereHas('verifikasi', function ($q) {
            $q->where('status', 'diterima');
        })->with(['rekening' => function ($q) {
            $q->where('status', 'aktif');
        }])->orderBy('nama')->get();

        $defaultPeriode = date('Y-m');

        return view('keuangan.pembayaran.create', compact('pensiunans', 'defaultPeriode'));
    }

    /**
     * Menyimpan data pembayaran pensiunan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pensiunan_id' => ['required', 'exists:pensiunan,id'],
            'periode'      => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'jumlah'       => ['required', 'numeric', 'gt:0'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
        ], [
            'pensiunan_id.required' => 'Pensiunan wajib dipilih.',
            'pensiunan_id.exists'   => 'Data pensiunan tidak ditemukan.',
            'periode.required'      => 'Periode pembayaran wajib diisi.',
            'periode.regex'         => 'Format periode harus YYYY-MM (contoh: 2026-11).',
            'jumlah.required'       => 'Nominal pembayaran wajib diisi.',
            'jumlah.numeric'        => 'Nominal pembayaran harus berupa angka.',
            'jumlah.gt'             => 'Nominal pembayaran harus lebih besar dari 0.',
            'keterangan.max'        => 'Keterangan maksimal 500 karakter.',
        ]);

        // Verifikasi bahwa pensiunan memang berstatus verifikasi diterima
        $pensiunan = Pensiunan::findOrFail($validated['pensiunan_id']);
        $isVerified = $pensiunan->verifikasi()->where('status', 'diterima')->exists();
        if (! $isVerified) {
            return back()->withInput()->withErrors([
                'pensiunan_id' => 'Pembayaran hanya dapat dibuat untuk pensiunan yang datanya telah disetujui (diterima) oleh HRD.',
            ]);
        }

        // Cegah pembayaran ganda untuk pensiunan dan periode yang sama
        $isDuplicate = Pembayaran::where('pensiunan_id', $validated['pensiunan_id'])
            ->where('periode', $validated['periode'])
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'periode' => "Pembayaran untuk pensiunan {$pensiunan->nama} pada periode {$validated['periode']} sudah pernah dibuat sebelumnya.",
            ]);
        }

        $pembayaran = Pembayaran::create([
            'pensiunan_id' => $validated['pensiunan_id'],
            'periode'      => $validated['periode'],
            'jumlah'       => $validated['jumlah'],
            'status'       => 'menunggu',
            'keterangan'   => $validated['keterangan'] ?? null,
        ]);

        // Notifikasi ke pensiunan terkait penerbitan pembayaran
        if ($pembayaran->pensiunan && $pembayaran->pensiunan->user_id) {
            Notifikasi::create([
                'user_id'      => $pembayaran->pensiunan->user_id,
                'judul'        => "Pembayaran Pensiun Periode {$pembayaran->periode} Menunggu Proses",
                'pesan'        => "Instruksi pembayaran manfaat pensiun periode {$pembayaran->periode} sebesar {$pembayaran->formatted_jumlah} telah diterbitkan dan sedang menunggu pemrosesan transfer bank.",
                'jenis'        => 'pembayaran',
                'sudah_dibaca' => false,
            ]);
        }

        return redirect()
            ->route('keuangan.pembayaran.show', $pembayaran)
            ->with('success', "Pembayaran periode {$pembayaran->periode} untuk pensiunan {$pensiunan->nama} berhasil dibuat dengan status menunggu.");
    }

    /**
     * Menampilkan detail pembayaran pensiunan.
     */
    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load(['pensiunan.rekening', 'pensiunan.verifikasi', 'processor']);

        return view('keuangan.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Memproses status pembayaran (menunggu -> diproses -> berhasil / gagal).
     */
    public function proses(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $validated = $request->validate([
            'status'     => ['required', 'in:diproses,berhasil,gagal'],
            'keterangan' => ['required_if:status,gagal', 'nullable', 'string', 'max:1000'],
        ], [
            'status.required'        => 'Status pemrosesan wajib dipilih.',
            'status.in'              => 'Status pemrosesan pembayaran tidak valid.',
            'keterangan.required_if' => 'Alasan wajib diisi apabila pembayaran berstatus gagal.',
            'keterangan.max'         => 'Keterangan maksimal 1000 karakter.',
        ]);

        $statusBerubah = $pembayaran->status !== $validated['status'];

        DB::transaction(function () use ($pembayaran, $validated, $statusBerubah) {
            $dataUpdate = [
                'status'       => $validated['status'],
                'processed_by' => Auth::id(),
            ];

            if ($validated['status'] === 'berhasil') {
                $dataUpdate['tanggal_pembayaran'] = now()->toDateString();
            }

            if (! empty($validated['keterangan'])) {
                $dataUpdate['keterangan'] = $validated['keterangan'];
            }

            $pembayaran->update($dataUpdate);

            // Integrasi Notifikasi ke user pensiunan jika terhubung dan status berubah
            if ($statusBerubah && $pembayaran->pensiunan && $pembayaran->pensiunan->user_id) {
                if ($validated['status'] === 'diproses') {
                    Notifikasi::create([
                        'user_id'      => $pembayaran->pensiunan->user_id,
                        'judul'        => "Pembayaran Pensiun Periode {$pembayaran->periode} Sedang Diproses",
                        'pesan'        => "Pembayaran manfaat pensiun periode {$pembayaran->periode} sebesar {$pembayaran->formatted_jumlah} saat ini sedang diproses transfer oleh Divisi Keuangan.",
                        'jenis'        => 'pembayaran',
                        'sudah_dibaca' => false,
                    ]);
                } elseif ($validated['status'] === 'berhasil') {
                    Notifikasi::create([
                        'user_id'      => $pembayaran->pensiunan->user_id,
                        'judul'        => "Pembayaran Pensiun Periode {$pembayaran->periode} Berhasil",
                        'pesan'        => "Dana pensiun Anda untuk periode {$pembayaran->periode} sebesar {$pembayaran->formatted_jumlah} telah berhasil diproses ke rekening Anda." . (! empty($validated['keterangan']) ? ' Referensi: ' . $validated['keterangan'] : ''),
                        'jenis'        => 'pembayaran',
                        'sudah_dibaca' => false,
                    ]);
                } elseif ($validated['status'] === 'gagal') {
                    Notifikasi::create([
                        'user_id'      => $pembayaran->pensiunan->user_id,
                        'judul'        => "Pemberitahuan Kendala Pembayaran Periode {$pembayaran->periode}",
                        'pesan'        => "Pembayaran pensiun periode {$pembayaran->periode} belum dapat diselesaikan: " . ($validated['keterangan'] ?? 'Terdapat kendala administrasi rekening.'),
                        'jenis'        => 'pembayaran',
                        'sudah_dibaca' => false,
                    ]);
                }
            }
        });

        $label = match ($validated['status']) {
            'diproses' => 'sedang diproses',
            'berhasil' => 'berhasil diselesaikan',
            'gagal'    => 'ditandai gagal',
        };

        return redirect()
            ->route('keuangan.pembayaran.show', $pembayaran)
            ->with('success', "Status pembayaran periode {$pembayaran->periode} {$label}.");
    }
}


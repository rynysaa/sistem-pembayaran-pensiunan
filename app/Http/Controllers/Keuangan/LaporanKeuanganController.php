<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanKeuanganController extends Controller
{
    /**
     * Menampilkan laporan rekapitulasi dan riwayat pembayaran pensiunan.
     */
    public function index(Request $request): View
    {
        $periode = $request->input('periode');

        $query = Pembayaran::with(['pensiunan.rekening', 'processor'])
            ->latest('tanggal_pembayaran')
            ->latest('updated_at');

        if ($periode) {
            $query->where('periode', $periode);
        }

        $pembayaran = $query->paginate(15)->withQueryString();

        // Rekapitulasi agregat berdasarkan filter periode yang aktif
        $statsQuery = Pembayaran::query();
        if ($periode) {
            $statsQuery->where('periode', $periode);
        }

        $totalTransaksi = (clone $statsQuery)->count();
        $totalBerhasil  = (clone $statsQuery)->berhasil()->count();
        $totalDiproses  = (clone $statsQuery)->diproses()->count();
        $totalMenunggu  = (clone $statsQuery)->menunggu()->count();
        $totalGagal     = (clone $statsQuery)->gagal()->count();
        $nominalBerhasil = (clone $statsQuery)->berhasil()->sum('jumlah');

        // Daftar periode untuk opsi filter
        $daftarPeriode = Pembayaran::select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');

        return view('keuangan.laporan.index', compact(
            'pembayaran',
            'periode',
            'daftarPeriode',
            'totalTransaksi',
            'totalBerhasil',
            'totalDiproses',
            'totalMenunggu',
            'totalGagal',
            'nominalBerhasil'
        ));
    }
}


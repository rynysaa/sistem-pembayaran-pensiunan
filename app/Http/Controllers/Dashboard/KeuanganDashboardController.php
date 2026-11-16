<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KeuanganDashboardController extends Controller
{
    public function index(): View
    {
        $totalPembayaran = Pembayaran::count();
        $menungguCount   = Pembayaran::menunggu()->count();
        $diprosesCount   = Pembayaran::diproses()->count();
        $berhasilCount   = Pembayaran::berhasil()->count();
        $gagalCount      = Pembayaran::gagal()->count();
        $totalNominal    = Pembayaran::berhasil()->sum('jumlah');

        // Ringkasan pembayaran berdasarkan periode
        $ringkasanPeriode = Pembayaran::select(
                'periode',
                DB::raw('count(*) as total_transaksi'),
                DB::raw("sum(case when status = 'berhasil' then 1 else 0 end) as total_berhasil"),
                DB::raw("sum(case when status = 'diproses' then 1 else 0 end) as total_diproses"),
                DB::raw("sum(case when status = 'menunggu' then 1 else 0 end) as total_menunggu"),
                DB::raw("sum(case when status = 'gagal' then 1 else 0 end) as total_gagal"),
                DB::raw("sum(case when status = 'berhasil' then jumlah else 0 end) as nominal_berhasil")
            )
            ->groupBy('periode')
            ->orderByDesc('periode')
            ->limit(6)
            ->get();

        // 5 transaksi pembayaran terbaru
        $pembayaranTerbaru = Pembayaran::with(['pensiunan', 'processor'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('dashboard.keuangan', compact(
            'totalPembayaran',
            'menungguCount',
            'diprosesCount',
            'berhasilCount',
            'gagalCount',
            'totalNominal',
            'ringkasanPeriode',
            'pembayaranTerbaru'
        ));
    }
}

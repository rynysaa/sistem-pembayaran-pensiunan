<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PensiunanDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $pensiunan = $user->pensiunan;

        $notifUnreadCount = $user->notifikasi()->where('sudah_dibaca', false)->count();

        $totalDiterimaNominal = $pensiunan
            ? $pensiunan->pembayaran()->where('status', 'berhasil')->sum('jumlah')
            : 0;

        $pembayaranBulanIni = $pensiunan
            ? $pensiunan->pembayaran()->where('periode', date('Y-m'))->first()
            : null;

        $verifikasiStatus = $pensiunan?->latestVerifikasi?->status ?? 'menunggu';

        $notifikasiTerbaru = $user->notifikasi()->latest()->limit(5)->get();

        // Data profil rekening dan riwayat transaksi untuk pengguna pensiunan
        $rekeningAktif = $pensiunan
            ? $pensiunan->rekenings()->where('status', 'aktif')->first()
            : null;

        $riwayatPembayaran = $pensiunan
            ? $pensiunan->pembayaran()->latest('periode')->limit(8)->get()
            : collect();

        return view('dashboard.pensiunan', compact(
            'pensiunan',
            'notifUnreadCount',
            'totalDiterimaNominal',
            'pembayaranBulanIni',
            'verifikasiStatus',
            'notifikasiTerbaru',
            'rekeningAktif',
            'riwayatPembayaran'
        ));
    }
}

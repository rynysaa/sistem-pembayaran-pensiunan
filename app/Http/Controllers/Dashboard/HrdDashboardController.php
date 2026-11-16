<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pensiunan;
use App\Models\VerifikasiData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HrdDashboardController extends Controller
{
    public function index(): View
    {
        $totalPensiunan    = Pensiunan::count();
        $pensiunanAktif    = Pensiunan::where('status', 'aktif')->count();
        $pensiunanNonaktif = Pensiunan::where('status', 'nonaktif')->count();
        $menungguVerifikasi = VerifikasiData::where('status', 'menunggu')->count();

        // Data tambahan untuk operasional HRD
        $verifikasiDiterima = VerifikasiData::where('status', 'diterima')->count();
        $verifikasiDitolak  = VerifikasiData::where('status', 'ditolak')->count();

        $pensiunanTerbaru = Pensiunan::with(['latestVerifikasi', 'rekening'])
            ->latest()
            ->limit(5)
            ->get();

        $antreanVerifikasi = VerifikasiData::with('pensiunan')
            ->where('status', 'menunggu')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.hrd', compact(
            'totalPensiunan',
            'pensiunanAktif',
            'pensiunanNonaktif',
            'menungguVerifikasi',
            'verifikasiDiterima',
            'verifikasiDitolak',
            'pensiunanTerbaru',
            'antreanVerifikasi'
        ));
    }
}

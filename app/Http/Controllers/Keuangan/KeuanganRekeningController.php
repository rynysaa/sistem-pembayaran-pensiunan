<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pensiunan;
use App\Models\RekeningPensiunan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeuanganRekeningController extends Controller
{
    /**
     * Menampilkan daftar rekening pensiunan sebagai referensi divisi keuangan.
     */
    public function index(Request $request): View
    {
        $query = RekeningPensiunan::with('pensiunan')
            ->join('pensiunan', 'rekening_pensiunan.pensiunan_id', '=', 'pensiunan.id')
            ->select('rekening_pensiunan.*')
            ->latest('rekening_pensiunan.created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pensiunan.nama', 'like', "%{$search}%")
                  ->orWhere('pensiunan.nip', 'like', "%{$search}%")
                  ->orWhere('rekening_pensiunan.nama_bank', 'like', "%{$search}%")
                  ->orWhere('rekening_pensiunan.nomor_rekening', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('rekening_pensiunan.status', $status);
        }

        $rekening = $query->paginate(10)->withQueryString();

        $totalRekening = RekeningPensiunan::count();
        $totalAktif    = RekeningPensiunan::where('status', 'aktif')->count();
        $totalNonaktif = RekeningPensiunan::where('status', 'nonaktif')->count();

        return view('keuangan.rekening.index', compact(
            'rekening',
            'totalRekening',
            'totalAktif',
            'totalNonaktif'
        ));
    }
}


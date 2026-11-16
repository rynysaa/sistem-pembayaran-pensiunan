<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi milik pengguna yang sedang login.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tab  = $request->input('tab', 'semua'); // 'semua', 'belum_dibaca', 'sudah_dibaca'

        $query = $user->notifikasi()->latest();

        if ($tab === 'belum_dibaca') {
            $query->belumDibaca();
        } elseif ($tab === 'sudah_dibaca') {
            $query->sudahDibaca();
        }

        $notifikasi = $query->paginate(10)->withQueryString();

        // Data terpisah untuk tampilan ringkasan
        $notifikasiTerbaru = $user->notifikasi()
            ->belumDibaca()
            ->latest()
            ->limit(5)
            ->get();

        $totalSemua       = $user->notifikasi()->count();
        $totalBelumDibaca = $user->notifikasi()->belumDibaca()->count();
        $totalSudahDibaca = $user->notifikasi()->sudahDibaca()->count();

        return view('notifikasi.index', compact(
            'notifikasi',
            'notifikasiTerbaru',
            'tab',
            'totalSemua',
            'totalBelumDibaca',
            'totalSudahDibaca'
        ));
    }

    /**
     * Menampilkan detail notifikasi dan menandainya sebagai sudah dibaca.
     */
    public function show(Notifikasi $notifikasi): View
    {
        // Otorisasi: pengguna hanya dapat melihat notifikasi miliknya sendiri
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat notifikasi ini.');
        }

        if (! $notifikasi->sudah_dibaca) {
            $notifikasi->markAsRead();
        }

        return view('notifikasi.show', compact('notifikasi'));
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Notifikasi $notifikasi): RedirectResponse
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah notifikasi ini.');
        }

        $notifikasi->markAsRead();

        return back()->with('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca.');
    }

    /**
     * Menandai seluruh notifikasi milik pengguna sebagai sudah dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->notifikasi()->belumDibaca()->update([
            'sudah_dibaca' => true,
            'dibaca_pada'  => now(),
        ]);

        return back()->with('success', 'Seluruh notifikasi berhasil ditandai sebagai sudah dibaca.');
    }

    /**
     * Menghapus notifikasi milik pengguna.
     */
    public function destroy(Notifikasi $notifikasi): RedirectResponse
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus notifikasi ini.');
        }

        $notifikasi->delete();

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}


<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\HrdDashboardController;
use App\Http\Controllers\Dashboard\KeuanganDashboardController;
use App\Http\Controllers\Dashboard\PensiunanDashboardController;
use App\Http\Controllers\Hrd\PensiunanController;
use App\Http\Controllers\Hrd\RekeningController;
use App\Http\Controllers\Hrd\VerifikasiController;
use App\Http\Controllers\Keuangan\KeuanganRekeningController;
use App\Http\Controllers\Keuangan\LaporanKeuanganController;
use App\Http\Controllers\Keuangan\PembayaranController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

// ─── Root → redirect ke login ───────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ─── Autentikasi (tamu saja) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ─── Logout (harus sudah login) ──────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Dashboard HRD ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:hrd'])
    ->prefix('hrd')
    ->name('hrd.')
    ->group(function () {
        Route::get('/dashboard', [HrdDashboardController::class, 'index'])->name('dashboard');

        // ── CRUD Data Pensiunan ──────────────────────────────────────────────
        Route::resource('pensiunan', PensiunanController::class);

        // ── CRUD Rekening Pensiunan ──────────────────────────────────────────
        Route::resource('rekening', RekeningController::class)->except(['show']);
        Route::patch('rekening/{rekening}/toggle-status', [RekeningController::class, 'toggleStatus'])
            ->name('rekening.toggle-status');

        // ── Verifikasi Data Pensiunan ────────────────────────────────────────
        Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('verifikasi/{verifikasi}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::post('verifikasi/{verifikasi}/proses', [VerifikasiController::class, 'proses'])->name('verifikasi.proses');
    });

// ─── Dashboard Keuangan ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:keuangan'])
    ->prefix('keuangan')
    ->name('keuangan.')
    ->group(function () {
        Route::get('/dashboard', [KeuanganDashboardController::class, 'index'])->name('dashboard');

        // ── Modul Pembayaran Pensiunan ────────────────────────────────────────
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/{pembayaran}/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');

        // ── Laporan Keuangan ──────────────────────────────────────────────────
        Route::get('/laporan', [LaporanKeuanganController::class, 'index'])->name('laporan.index');

        // ── Data Rekening Pensiunan (Referensi) ────────────────────────────────
        Route::get('/rekening', [KeuanganRekeningController::class, 'index'])->name('rekening.index');
    });

// ─── Dashboard Pensiunan ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:pensiunan'])
    ->prefix('pensiunan')
    ->name('pensiunan.')
    ->group(function () {
        Route::get('/dashboard', [PensiunanDashboardController::class, 'index'])->name('dashboard');
    });

// ─── Modul Notifikasi (Seluruh Pengguna / Multi-role) ─────────────────────────
Route::middleware('auth')
    ->prefix('notifikasi')
    ->name('notifikasi.')
    ->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/tandai-semua-dibaca', [NotifikasiController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/{notifikasi}', [NotifikasiController::class, 'show'])->name('show');
        Route::patch('/{notifikasi}/baca', [NotifikasiController::class, 'markAsRead'])->name('mark-as-read');
        Route::delete('/{notifikasi}', [NotifikasiController::class, 'destroy'])->name('destroy');
    });

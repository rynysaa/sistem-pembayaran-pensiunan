<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'jenis',
        'sudah_dibaca',
        'dibaca_pada',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibaca_pada'  => 'datetime',
    ];

    /**
     * User penerima notifikasi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: notifikasi yang belum dibaca.
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('sudah_dibaca', false);
    }

    /**
     * Scope: notifikasi yang sudah dibaca.
     */
    public function scopeSudahDibaca($query)
    {
        return $query->where('sudah_dibaca', true);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'sudah_dibaca' => true,
            'dibaca_pada'  => now(),
        ]);
    }

    /**
     * Helper icon class Bootstrap Icon.
     */
    public function getIconAttribute(): string
    {
        $judulLower = strtolower($this->judul);

        if (str_contains($judulLower, 'diterima') || str_contains($judulLower, 'berhasil')) {
            return 'bi-check-circle-fill text-success';
        }
        if (str_contains($judulLower, 'ditolak') || str_contains($judulLower, 'gagal') || str_contains($judulLower, 'kendala')) {
            return 'bi-x-circle-fill text-danger';
        }
        if (str_contains($judulLower, 'diproses')) {
            return 'bi-arrow-repeat text-info';
        }
        if (str_contains($judulLower, 'menunggu') || str_contains($judulLower, 'diterbitkan')) {
            return 'bi-hourglass-split text-warning';
        }

        return match ($this->jenis) {
            'verifikasi' => 'bi-clipboard-check text-primary',
            'pembayaran' => 'bi-credit-card text-success',
            default      => 'bi-info-circle text-primary',
        };
    }

    /**
     * Helper badge class Bootstrap.
     */
    public function getBadgeClassAttribute(): string
    {
        $judulLower = strtolower($this->judul);

        if (str_contains($judulLower, 'diterima') || str_contains($judulLower, 'berhasil')) {
            return 'bg-success';
        }
        if (str_contains($judulLower, 'ditolak') || str_contains($judulLower, 'gagal') || str_contains($judulLower, 'kendala')) {
            return 'bg-danger';
        }
        if (str_contains($judulLower, 'diproses')) {
            return 'bg-info text-white';
        }
        if (str_contains($judulLower, 'menunggu') || str_contains($judulLower, 'diterbitkan')) {
            return 'bg-warning text-dark';
        }

        return match ($this->jenis) {
            'verifikasi' => 'bg-primary',
            'pembayaran' => 'bg-success',
            default      => 'bg-secondary',
        };
    }
}

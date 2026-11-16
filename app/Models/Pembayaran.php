<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pensiunan_id',
        'processed_by',
        'periode',
        'tanggal_pembayaran',
        'jumlah',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah'             => 'decimal:2',
    ];

    /**
     * Pembayaran ini milik pensiunan tertentu.
     */
    public function pensiunan(): BelongsTo
    {
        return $this->belongsTo(Pensiunan::class);
    }

    /**
     * User (keuangan/HRD) yang memproses pembayaran ini.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Format nominal dalam Rupiah.
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->jumlah, 0, ',', '.');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeBerhasil($query)
    {
        return $query->where('status', 'berhasil');
    }

    public function scopeGagal($query)
    {
        return $query->where('status', 'gagal');
    }
}


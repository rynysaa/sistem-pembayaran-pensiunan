<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiData extends Model
{
    protected $table = 'verifikasi_data';

    protected $fillable = [
        'pensiunan_id',
        'diverifikasi_oleh',
        'status',
        'tanggal_verifikasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Data pensiunan yang diverifikasi.
     */
    public function pensiunan(): BelongsTo
    {
        return $this->belongsTo(Pensiunan::class);
    }

    /**
     * User (HRD) yang melakukan verifikasi.
     */
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}


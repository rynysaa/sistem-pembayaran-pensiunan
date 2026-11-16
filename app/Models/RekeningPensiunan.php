<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekeningPensiunan extends Model
{
    protected $table = 'rekening_pensiunan';

    protected $fillable = [
        'pensiunan_id',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Rekening ini milik pensiunan tertentu.
     */
    public function pensiunan(): BelongsTo
    {
        return $this->belongsTo(Pensiunan::class);
    }
}


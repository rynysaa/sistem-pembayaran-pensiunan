<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pensiunan extends Model
{
    protected $table = 'pensiunan';

    protected $fillable = [
        'user_id',
        'nip',
        'nik',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tanggal_mulai_kerja',
        'tanggal_pensiun',
        'jabatan_terakhir',
        'unit_kerja',
        'alamat',
        'no_telepon',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_mulai_kerja' => 'date',
        'tanggal_pensiun' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rekening(): HasMany
    {
        return $this->hasMany(RekeningPensiunan::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function verifikasi(): HasMany
    {
        return $this->hasMany(VerifikasiData::class);
    }

    public function latestVerifikasi(): HasOne
    {
        return $this->hasOne(VerifikasiData::class)->latestOfMany();
    }
}

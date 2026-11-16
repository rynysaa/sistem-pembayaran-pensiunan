<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Helper role ───────────────────────────────────────────────

    public function isHrd(): bool
    {
        return $this->role === 'hrd';
    }

    public function isKeuangan(): bool
    {
        return $this->role === 'keuangan';
    }

    public function isPensiunan(): bool
    {
        return $this->role === 'pensiunan';
    }

    // ─── Relasi ────────────────────────────────────────────────────

    /**
     * Data pensiunan yang terhubung dengan user ini (role: pensiunan).
     */
    public function pensiunan(): HasOne
    {
        return $this->hasOne(Pensiunan::class);
    }

    /**
     * Pembayaran yang diproses oleh user ini (role: keuangan).
     */
    public function pembayaranDiproses(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'processed_by');
    }

    /**
     * Verifikasi data yang dilakukan oleh user ini (role: hrd).
     */
    public function verifikasiDilakukan(): HasMany
    {
        return $this->hasMany(VerifikasiData::class, 'diverifikasi_oleh');
    }

    /**
     * Notifikasi milik user ini.
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    /**
     * Log aktivitas user ini.
     */
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class);
    }
}

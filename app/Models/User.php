<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'role',
        'ptk_id',
        'peserta_didik_id',
        'mitra_id',
        'jurusan_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->nama ?? $this->username ?? '';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKepsek(): bool
    {
        return $this->role === 'kepsek';
    }

    public function isWakaHubin(): bool
    {
        return $this->role === 'waka_hubin';
    }

    public function isKajur(): bool
    {
        return $this->role === 'kajur';
    }

    public function isGuruPembimbing(): bool
    {
        return $this->role === 'guru_pembimbing';
    }

    public function isPembimbingIndustri(): bool
    {
        return $this->role === 'pembimbing_industri';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefSiswa extends Model
{
    use HasFactory;

    protected $table = 'ref_siswa';
    protected $primaryKey = 'peserta_didik_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'peserta_didik_id',
        'nisn',
        'nipd',
        'nik',
        'nama',
        'jenis_kelamin',
        'nama_rombel',
        'rombongan_belajar_id',
        'jurusan_id',
        'no_hp',
        'status_kesiapan',
        'catatan_kesiapan',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(RefJurusan::class, 'jurusan_id', 'jurusan_id');
    }

    public function scopeForDepartment(Builder $query, ?string $jurusanId): Builder
    {
        if (empty($jurusanId)) {
            return $query;
        }

        return $query->where('jurusan_id', $jurusanId);
    }

    public function scopeSiap(Builder $query): Builder
    {
        return $query->where('status_kesiapan', 'siap');
    }
}

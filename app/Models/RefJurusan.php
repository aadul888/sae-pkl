<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefJurusan extends Model
{
    use HasFactory;

    protected $table = 'ref_jurusan';
    protected $primaryKey = 'jurusan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'jurusan_id',
        'nama_jurusan',
        'bidang_keahlian',
        'program_keahlian',
        'kajur_ptk_id',
        'kajur_nama',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(RefSiswa::class, 'jurusan_id', 'jurusan_id');
    }
}

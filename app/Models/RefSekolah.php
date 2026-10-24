<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSekolah extends Model
{
    use HasFactory;

    protected $table = 'ref_sekolah';
    protected $primaryKey = 'sekolah_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sekolah_id',
        'npsn',
        'nama',
        'alamat',
        'kepala_sekolah_nama',
        'kepala_sekolah_nip',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];
}

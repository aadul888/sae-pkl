<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefGuru extends Model
{
    use HasFactory;

    protected $table = 'ref_guru';
    protected $primaryKey = 'ptk_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ptk_id',
        'nuptk',
        'nip',
        'nama',
        'jenis_ptk',
        'no_hp',
        'is_active',
        'synced_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'synced_at' => 'datetime',
    ];
}

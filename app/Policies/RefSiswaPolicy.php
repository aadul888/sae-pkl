<?php

namespace App\Policies;

use App\Models\RefSiswa;
use App\Models\User;

class RefSiswaPolicy
{
    /**
     * Memeriksa apakah pengguna berhak melihat daftar siswa secara umum.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'kepsek', 'waka_hubin', 'kajur', 'guru_pembimbing'], true);
    }

    /**
     * Memeriksa apakah pengguna berhak melihat data detail seorang siswa tertentu.
     */
    public function view(User $user, RefSiswa $siswa): bool
    {
        // 1. Admin, Kepsek, Waka Hubin memiliki akses lintas jurusan dalam satu sekolah
        if (in_array($user->role, ['admin', 'kepsek', 'waka_hubin'], true)) {
            return true;
        }

        // 2. Kajur HANYA berhak mengakses siswa yang berada di jurusannya sendiri
        if ($user->isKajur()) {
            return !empty($user->jurusan_id) && $user->jurusan_id === $siswa->jurusan_id;
        }

        // 3. Siswa hanya berhak melihat datanya sendiri
        if ($user->isSiswa()) {
            return !empty($user->peserta_didik_id) && $user->peserta_didik_id === $siswa->peserta_didik_id;
        }

        return false;
    }

    /**
     * Memeriksa apakah pengguna berhak memverifikasi kesiapan siswa.
     */
    public function verifyKesiapan(User $user, RefSiswa $siswa): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isKajur()) {
            return !empty($user->jurusan_id) && $user->jurusan_id === $siswa->jurusan_id;
        }

        return false;
    }
}

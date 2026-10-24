<?php

namespace App\Services\Integration;

use App\Contracts\SaeIntegrationInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SaeDatabaseDriver implements SaeIntegrationInterface
{
    protected string $connection = 'mysql_sae_master';

    /**
     * Memeriksa apakah koneksi ke database SAE Utama tersedia.
     */
    public function isAvailable(): bool
    {
        try {
            DB::connection($this->connection)->getPdo();
            return true;
        } catch (\Throwable $e) {
            Log::warning('Koneksi database SAE Utama tidak tersedia: ' . $e->getMessage());
            return false;
        }
    }

    public function getSekolah(): ?array
    {
        if (!$this->isAvailable()) {
            return null;
        }

        try {
            if (!Schema::connection($this->connection)->hasTable('sekolah')) {
                return null;
            }

            $row = DB::connection($this->connection)
                ->table('sekolah')
                ->select(['sekolah_id', 'npsn', 'nama', 'alamat_jalan'])
                ->first();

            if (!$row) {
                return null;
            }

            return [
                'sekolah_id' => (string) $row->sekolah_id,
                'npsn' => $row->npsn ?? null,
                'nama' => $row->nama ?? 'Sekolah',
                'alamat' => $row->alamat_jalan ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data sekolah dari SAE Utama: ' . $e->getMessage());
            return null;
        }
    }

    public function getJurusanList(): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        try {
            $results = [];

            // 1. Coba dari tabel kompetensi_keahlian jika ada
            if (Schema::connection($this->connection)->hasTable('kompetensi_keahlian')) {
                $rows = DB::connection($this->connection)
                    ->table('kompetensi_keahlian')
                    ->where('is_active', true)
                    ->get();

                foreach ($rows as $r) {
                    $results[$r->kode ?? $r->id] = [
                        'jurusan_id' => (string) ($r->kode ?? $r->id),
                        'nama_jurusan' => $r->nama,
                        'bidang_keahlian' => $r->bidang_keahlian ?? null,
                        'program_keahlian' => $r->program_keahlian ?? null,
                    ];
                }
            }

            // 2. Fallback / lengkapi dari atribut rombongan_belajar
            if (empty($results) && Schema::connection($this->connection)->hasTable('rombongan_belajar')) {
                $rows = DB::connection($this->connection)
                    ->table('rombongan_belajar')
                    ->whereNotNull('jurusan_id')
                    ->select('jurusan_id', 'jurusan_id_str')
                    ->distinct()
                    ->get();

                foreach ($rows as $r) {
                    if (!empty($r->jurusan_id)) {
                        $results[$r->jurusan_id] = [
                            'jurusan_id' => (string) $r->jurusan_id,
                            'nama_jurusan' => $r->jurusan_id_str ?? $r->jurusan_id,
                            'bidang_keahlian' => null,
                            'program_keahlian' => null,
                        ];
                    }
                }
            }

            return array_values($results);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil daftar jurusan dari SAE Utama: ' . $e->getMessage());
            return [];
        }
    }

    public function getGtkList(): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        try {
            if (!Schema::connection($this->connection)->hasTable('gtk')) {
                return [];
            }

            $rows = DB::connection($this->connection)
                ->table('gtk')
                ->select([
                    'ptk_id',
                    'nuptk',
                    'nip',
                    'nama',
                    'jenis_ptk_id_str as jenis_ptk',
                    'no_hp',
                ])
                ->get();

            return $rows->map(fn($r) => [
                'ptk_id' => (string) $r->ptk_id,
                'nuptk' => $r->nuptk ?? null,
                'nip' => $r->nip ?? null,
                'nama' => $r->nama,
                'jenis_ptk' => $r->jenis_ptk ?? null,
                'no_hp' => $r->no_hp ?? null,
                'is_active' => true,
            ])->toArray();
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil daftar GTK dari SAE Utama: ' . $e->getMessage());
            return [];
        }
    }

    public function getPesertaDidikByJurusan(?string $jurusanId = null): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        try {
            if (!Schema::connection($this->connection)->hasTable('peserta_didik')) {
                return [];
            }

            $query = DB::connection($this->connection)
                ->table('peserta_didik')
                ->select([
                    'peserta_didik_id',
                    'nisn',
                    'nipd',
                    'nik',
                    'nama',
                    'jenis_kelamin',
                    'nama_rombel',
                    'rombongan_belajar_id',
                    'nomor_telepon_seluler as no_hp',
                ]);

            if ($jurusanId) {
                // Filter berdasarkan jurusan_id jika kolom ada atau via rombel
                if (Schema::connection($this->connection)->hasColumn('peserta_didik', 'jurusan_id')) {
                    $query->where('jurusan_id', $jurusanId);
                } elseif (Schema::connection($this->connection)->hasTable('rombongan_belajar')) {
                    $query->join('rombongan_belajar', 'peserta_didik.rombongan_belajar_id', '=', 'rombongan_belajar.rombongan_belajar_id')
                          ->where('rombongan_belajar.jurusan_id', $jurusanId);
                }
            }

            $rows = $query->get();

            return $rows->map(function ($r) use ($jurusanId) {
                // Estimasi jurusan_id jika null
                $inferredJurusan = $jurusanId ?? ($r->jurusan_id ?? null);
                if (!$inferredJurusan && !empty($r->nama_rombel)) {
                    // Extract token jurusan dari nama rombel (misal 'XII RPL 1' -> 'RPL')
                    $parts = explode(' ', trim($r->nama_rombel));
                    if (count($parts) >= 2) {
                        $inferredJurusan = $parts[1];
                    }
                }

                return [
                    'peserta_didik_id' => (string) $r->peserta_didik_id,
                    'nisn' => $r->nisn ?? '',
                    'nipd' => $r->nipd ?? null,
                    'nik' => $r->nik ?? null,
                    'nama' => $r->nama,
                    'jenis_kelamin' => strtoupper(substr((string) ($r->jenis_kelamin ?? 'L'), 0, 1)),
                    'nama_rombel' => $r->nama_rombel ?? null,
                    'rombongan_belajar_id' => (string) ($r->rombongan_belajar_id ?? ''),
                    'jurusan_id' => (string) ($inferredJurusan ?? 'UMUM'),
                    'no_hp' => $r->no_hp ?? null,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil peserta didik dari SAE Utama: ' . $e->getMessage());
            return [];
        }
    }

    public function getActiveDuties(): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        try {
            if (!Schema::connection($this->connection)->hasTable('ptk_tugas_tambahan') ||
                !Schema::connection($this->connection)->hasTable('ref_tugas_tambahan')) {
                return [];
            }

            $rows = DB::connection($this->connection)
                ->table('ptk_tugas_tambahan as ptt')
                ->join('ref_tugas_tambahan as rtt', 'ptt.tugas_tambahan_id', '=', 'rtt.id')
                ->leftJoin('gtk', 'ptt.ptk_id', '=', 'gtk.ptk_id')
                ->where('ptt.is_active', true)
                ->whereIn('rtt.kode', ['WAKA_HUBIN', 'KAPROG'])
                ->select([
                    'ptt.ptk_id',
                    'gtk.nama as nama_guru',
                    'rtt.kode as duty_code',
                    'ptt.jurusan_id',
                ])
                ->get();

            return $rows->map(fn($r) => [
                'ptk_id' => (string) $r->ptk_id,
                'nama' => $r->nama_guru ?? '',
                'duty_code' => $r->duty_code,
                'jurusan_id' => $r->jurusan_id ?? null,
            ])->toArray();
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil tugas tambahan dari SAE Utama: ' . $e->getMessage());
            return [];
        }
    }

    public function verifyUserCredential(string $username, string $password): ?array
    {
        if (!$this->isAvailable()) {
            return null;
        }

        try {
            if (!Schema::connection($this->connection)->hasTable('pengguna')) {
                return null;
            }

            $user = DB::connection($this->connection)
                ->table('pengguna')
                ->where('username', $username)
                ->first();

            if (!$user) {
                return null;
            }

            $valid = false;
            if (!empty($user->password)) {
                if (password_verify($password, $user->password) || $user->password === $password) {
                    $valid = true;
                }
            }

            if (!$valid) {
                return null;
            }

            return [
                'pengguna_id' => (string) $user->pengguna_id,
                'username' => $user->username,
                'nama' => $user->nama ?? $user->username,
                'ptk_id' => $user->ptk_id ?? null,
                'peserta_didik_id' => $user->peserta_didik_id ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Gagal memverifikasi kredensial SAE Utama: ' . $e->getMessage());
            return null;
        }
    }
}

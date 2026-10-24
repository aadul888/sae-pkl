<?php

namespace App\Console\Commands;

use App\Contracts\SaeIntegrationInterface;
use App\Models\AuditLog;
use App\Models\RefGuru;
use App\Models\RefJurusan;
use App\Models\RefSekolah;
use App\Models\RefSiswa;
use Illuminate\Console\Command;

class SyncSaeMasterCommand extends Command
{
    protected $signature = 'sae:sync-master';
    protected $description = 'Sinkronisasi data master (Sekolah, Jurusan, Guru, Siswa) dari SAE Utama ke tabel referensi lokal';

    public function handle(SaeIntegrationInterface $sae): int
    {
        $this->info('Memulai sinkronisasi data master dari SAE Utama...');

        $now = now();
        $counts = [
            'sekolah' => 0,
            'jurusan' => 0,
            'guru' => 0,
            'siswa' => 0,
        ];

        // 1. Sinkronisasi Profil Sekolah
        $this->line('1. Mengambil profil sekolah...');
        $sekolahData = $sae->getSekolah();
        if ($sekolahData) {
            RefSekolah::updateOrCreate(
                ['sekolah_id' => $sekolahData['sekolah_id']],
                array_merge($sekolahData, ['synced_at' => $now])
            );
            $counts['sekolah'] = 1;
            $this->info("   -> Sekolah tersinkronisasi: {$sekolahData['nama']}");
        } else {
            $this->warn('   -> Data sekolah tidak ditemukan atau koneksi belum siap.');
        }

        // 2. Sinkronisasi Jurusan
        $this->line('2. Mengambil daftar jurusan...');
        $jurusanList = $sae->getJurusanList();
        foreach ($jurusanList as $j) {
            RefJurusan::updateOrCreate(
                ['jurusan_id' => $j['jurusan_id']],
                array_merge($j, ['synced_at' => $now])
            );
            $counts['jurusan']++;
        }
        $this->info("   -> {$counts['jurusan']} jurusan berhasil disinkronkan.");

        // 3. Sinkronisasi GTK / Guru
        $this->line('3. Mengambil daftar GTK/Guru...');
        $gtkList = $sae->getGtkList();
        foreach ($gtkList as $g) {
            RefGuru::updateOrCreate(
                ['ptk_id' => $g['ptk_id']],
                array_merge($g, ['synced_at' => $now])
            );
            $counts['guru']++;
        }
        $this->info("   -> {$counts['guru']} GTK/Guru berhasil disinkronkan.");

        // 4. Sinkronisasi Penugasan Kajur
        $this->line('4. Menyelaraskan tugas tambahan Kajur...');
        $duties = $sae->getActiveDuties();
        foreach ($duties as $duty) {
            if ($duty['duty_code'] === 'KAPROG' && !empty($duty['jurusan_id'])) {
                RefJurusan::where('jurusan_id', $duty['jurusan_id'])->update([
                    'kajur_ptk_id' => $duty['ptk_id'],
                    'kajur_nama' => $duty['nama'],
                    'synced_at' => $now,
                ]);
            }
        }

        // 5. Sinkronisasi Siswa
        $this->line('5. Mengambil data peserta didik...');
        $siswaList = $sae->getPesertaDidikByJurusan();
        foreach ($siswaList as $s) {
            RefSiswa::updateOrCreate(
                ['peserta_didik_id' => $s['peserta_didik_id']],
                array_merge($s, ['synced_at' => $now])
            );
            $counts['siswa']++;
        }
        $this->info("   -> {$counts['siswa']} siswa berhasil disinkronkan.");

        // Catat Audit Trail
        AuditLog::record('master.sync', null, null, $counts);

        $this->info('Sinkronisasi data master selesai dengan sukses!');
        return Command::SUCCESS;
    }
}

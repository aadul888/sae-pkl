# IMPLEMENTATION_PLAN.md — Rencana Implementasi Bertahap

Dokumen ini merinci roadmap implementasi **SAE PKL** dari Fase 0 hingga Fase 7. Setiap fase memiliki ruang lingkup terbatas, deliverable terukur, dan titik henti (*checkpoint*) untuk persetujuan pengguna.

---

## Ringkasan Roadmap Implementasi

```
[ Fase 0: Audit & Perencanaan ]  <─── (Tahap Saat Ini - Selesai)
           │
           ▼ (Persetujuan Pengguna)
[ Fase 1: Inisialisasi Proyek & Pondasi Sistem ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 2: Lapisan Integrasi SAE Utama & RBAC ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 3: Modul Persiapan, Mitra DU/DI & Penempatan ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 4: Modul Operasional (Presensi, Jurnal & Monitoring) ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 5: Modul Penilaian, Sertifikasi & Pelaporan ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 6: Dashboard Spesifik Peran & Pengujian Komprehensif ]
           │
           ▼ (Persetujuan Pengguna)
[ Fase 7: Finalisasi & Deployment Handover ]
```

---

## Rincian Setiap Fase

### Fase 0: Audit Lingkungan, Analisis & Dokumentasi Arsitektur *(Selesai)*
- **Tujuan:** Memeriksa kesiapan lingkungan, mengaudit struktur SAE Utama, memetakan kebutuhan, dan menyusun dokumen fondasi.
- **Hasil:** Dokumen `AGENTS.md`, `README.md`, `REQUISITES.md`, `ARCHITECTURE.md`, `DATABASE_DESIGN.md`, `INTEGRATION_SAE.md`, `MODULES.md`, `IMPLEMENTATION_PLAN.md`, `WORKLOG.md`.
- **Kriteria Berhenti:** Dokumentasi lengkap, audit diverifikasi, laporan Fase 0 diserahkan ke pengguna.

---

### Fase 1: Inisialisasi Proyek & Pondasi Sistem
- **Tujuan:** Menyiapkan kerangka kerja **Laravel 13** berjalan pada **PHP 8.3+** di direktori proyek, konfigurasi database, layout Blade dasar, dan dependensi minimal.
- **Langkah Persiapan Lingkungan:**
  - Mengarahkan eksekusi CLI Composer/Artisan ke binary PHP 8.3 bawaan Laragon (`C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`) atau beralih versi PHP di menu Laragon.
- **Rencana Pekerjaan:**
  1. Inisialisasi Laravel 13 bersih via Composer (`php composer create-project laravel/laravel .`).
  2. Konfigurasi file `.env` (koneksi MySQL `sae_pkl`, application key, timezone `Asia/Jakarta`).
  3. Pembuatan layout dasar Blade yang bersih, responsif, dan terstruktur.
  4. Pengaturan skema migrasi tabel fondasi awal: `users`, `audit_logs`, `settings`. (Tabel referensi `ref_*` belum dibuat di fase ini).
  5. Pembuatan seeder akun Admin default untuk akses awal sistem.
- **Kriteria Penerimaan:** Proyek Laravel berjalan normal (`php artisan serve` respons 200 OK), koneksi database `sae_pkl` terhubung, tabel `users`, `audit_logs`, dan `settings` berhasil dimigrasi.

---

### Fase 2: Lapisan Integrasi SAE Utama & RBAC
- **Tujuan:** Membangun kontrak `SaeIntegrationInterface`, adapter driver (Read-Only DB / HTTP API), dan otorisasi peran.
- **Rencana Pekerjaan:**
  1. Implementasi `SaeIntegrationInterface` dan `SaeDatabaseDriver` (atau `SaeHttpApiDriver` sesuai keputusan user).
  2. Migrasi tabel snapshot referensi: `ref_sekolah`, `ref_jurusan`, `ref_guru`, `ref_siswa`.
  3. Perintah sinkronisasi Artisan (`php artisan sae:sync-master`) dan antarmuka sinkronisasi Admin.
  4. Middleware dan Policy otorisasi berdasarkan peran (Admin, Kepsek, Hubin, Kajur, Guru, Industri, Siswa).
- **Kriteria Penerimaan:** Sinkronisasi berhasil membaca data dari SAE Utama; Kajur jurusan A terbukti secara tes ditolak mengakses data jurusan B.

---

### Fase 3: Modul Persiapan, Mitra DU/DI & Penempatan
- **Tujuan:** Mengelola periode PKL, direktori mitra industri, kuota, verifikasi kesiapan siswa, dan alur penempatan.
- **Rencana Pekerjaan:**
  1. Migrasi dan CRUD `pkl_periode`, `mitra_industri`, `mitra_kuota`.
  2. Alur verifikasi kesiapan siswa oleh Kajur.
  3. Alur pengusulan dan penetapan penempatan siswa (`penempatan_pkl`).
  4. Penugasan Guru Pembimbing dan Pembimbing Industri (`penugasan_pembimbing`).
- **Kriteria Penerimaan:** Siswa berhasil ditempatkan sesuai kuota; validasi menolak penempatan jika kuota penuh atau siswa belum diverifikasi siap.

---

### Fase 4: Modul Operasional (Presensi, Jurnal & Monitoring)
- **Tujuan:** Mengakomodasi rutinitas harian PKL (presensi, jurnal harian, dan monitoring guru).
- **Rencana Pekerjaan:**
  1. Modul Presensi Siswa (input presensi, validasi pembimbing, toleransi GPS/izin).
  2. Modul Jurnal Harian (input kegiatan, upload foto kegiatan, review & feedback pembimbing).
  3. Modul Kunjungan Monitoring oleh Guru Pembimbing (laporan perkembangan, kendala, foto kunjungan).
  4. Arsitektur pengiriman idempoten data kehadiran PKL ke pelaporan terpusat SAE Utama berjejak audit.
- **Kriteria Penerimaan:** Siswa dapat submit presensi dan jurnal; pembimbing industri & guru dapat memvalidasi dan memberikan feedback. Catatan kehadiran siap disinkronkan ke SAE Utama tanpa duplikasi.

---

### Fase 5: Modul Penilaian, Sertifikasi & Pelaporan
- **Tujuan:** Evaluasi capaian PKL, perhitungan nilai akhir gabungan, cetak sertifikat, dan rekapitulasi data.
- **Rencana Pekerjaan:**
  1. Form penilaian oleh Pembimbing Industri dan Guru Pembimbing.
  2. Kalkulasi nilai terbobot otomatis dan penentuan predikat.
  3. Penerbitan sertifikat digital ber-QR Code verifikasi unik (`sertifikat_pkl`).
  4. Ekspor laporan PDF dan Excel (rekap presensi, nilai, laporan per jurusan & tingkat sekolah).
- **Kriteria Penerimaan:** Nilai terhitung presisi; sertifikat terbit dengan QR code publik yang dapat divalidasi; ekspor PDF/Excel berjalan mulus.

---

### Fase 6: Dashboard Spesifik Peran & Pengujian Komprehensif
- **Tujuan:** Merancang dashboard khusus untuk 7 peran dan menjalankan pengujian keamanan & alur bisnis menyeluruh.
- **Rencana Pekerjaan:**
  1. Dashboard Admin, Kepsek, Waka Hubin, Kajur, Guru, Industri, Siswa.
  2. Penulisan dan eksekusi Feature Tests & Unit Tests (cakupan RBAC, IDOR, alur penempatan, kalkulasi nilai).
  3. Optimasi query (penanganan N+1 problem, indexing).
- **Kriteria Penerimaan:** Seluruh unit test dan feature test lolos 100%.

---

### Fase 7: Finalisasi & Deployment Handover
- **Tujuan:** Pembersihan kode, dokumentasi operasional, dan panduan deployment.
- **Rencana Pekerjaan:**
  1. Pembersihan cache, artefak sementara, dan pengecekan linter.
  2. Pembuatan panduan instalasi di lingkungan Laragon / Production.
  3. Serah terima sistem ke pengguna.
- **Kriteria Penerimaan:** Aplikasi siap pakai secara mandiri oleh pihak sekolah.

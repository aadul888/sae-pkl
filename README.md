# SAE PKL (Sistem Aplikasi Edukasi — Praktik Kerja Lapangan)

Sistem Informasi Manajemen Praktik Kerja Lapangan (PKL) tingkat SMK berbasis Laravel, dirancang sebagai modul satelit terintegrasi di dalam ekosistem **SAE (Sistem Aplikasi Edukasi)**.

---

## 1. Ringkasan Proyek
SAE PKL mendigitalisasi siklus hidup lengkap pelaksanaan Praktik Kerja Lapangan (PKL) di SMK, mulai dari:
1. **Perencanaan & Verifikasi:** Pemetaan mitra DU/DI, penetapan kuota industri, dan verifikasi kesiapan siswa oleh Kepala Jurusan (Kajur).
2. **Penempatan & Penugasan:** Alur usulan dan validasi penempatan oleh Waka Hubin, serta penugasan Guru Pembimbing dan Pembimbing Industri.
3. **Pelaksanaan & Monitoring:** Presensi mandiri/tervalidasi siswa, jurnal kegiatan harian, umpan balik pembimbing, dan catatan kunjungan monitoring berkala.
4. **Penilaian & Sertifikasi:** Penilaian multidimensi (sekolah dan industri), rekapitulasi nilai, serta penerbitan dokumen sertifikat PKL resmi.
5. **Pelaporan & Audit:** Rekapitulasi lintas jurusan, pelaporan tingkat sekolah, serta audit log aktivitas pengguna.

---

## 2. Prinsip Integrasi SAE Utama
SAE PKL **tidak mengelola master data siswa, guru, jurusan, atau sekolah secara mandiri**.
- Identitas siswa merujuk pada `peserta_didik_id` resmi SAE Utama.
- Identitas guru merujuk pada `ptk_id` resmi SAE Utama.
- Identitas jurusan/kompetensi keahlian merujuk pada `jurusan_id` resmi SAE Utama.
- Struktur penugasan Kajur dan Waka Hubin merujuk pada `ptk_tugas_tambahan` di SAE Utama.
- Seluruh data induk disinkronkan melalui layer integrasi resmi (REST API HTTPS / Direct Adapter) tanpa perlu input ulang manual.

---

## 3. Peran Pengguna (RBAC)
- **Admin SAE PKL:** Konfigurasi operasional sistem, manajemen integrasi, dan audit log.
- **Kepala Sekolah:** Dashboard eksekutif, pemantauan progres lintas jurusan, dan supervisi.
- **Waka Hubin (Hubungan Industri):** Manajemen mitra DU/DI, penetapan kuota, validasi penempatan, dan rekapitulasi sekolah.
- **Kepala Jurusan (Kajur):** Verifikasi kesiapan siswa, usulan penempatan & pembimbing khusus jurusannya.
- **Guru Pembimbing PKL:** Monitoring bimbingan, verifikasi jurnal, dan penilaian sekolah.
- **Pembimbing Industri:** Validasi presensi tempat PKL, umpan balik jurnal, dan evaluasi industri.
- **Siswa:** Pengisian presensi, jurnal harian, pemantauan status, dan unduh sertifikat.

---

## 4. Struktur Teknologi (Tech Stack)
- **Framework:** Laravel 13.x
- **Bahasa:** PHP 8.3+
- **Database:** MySQL 8.4+ / MariaDB
- **Frontend:** Blade Server-Rendered Views + CSS utility/framework responsif
- **Autentikasi & Integrasi:** Laravel Session & Policy RBAC / SaeIntegrationInterface Adapter

---

## 5. Panduan Menjalankan & Menguji Proyek
- **Menjalankan Migrasi Database:**
  ```cmd
  cmd.exe /c "artisan.bat migrate"
  ```
- **Menyiapkan Akun Administrator:**
  ```cmd
  cmd.exe /c "artisan.bat sae:admin-setup"
  ```
- **Sinkronisasi Data Master dari SAE Utama:**
  ```cmd
  cmd.exe /c "artisan.bat sae:sync-master"
  ```
- **Menjalankan Server Lokal:**
  ```cmd
  cmd.exe /c "artisan.bat serve"
  ```
- **Menjalankan Automated Tests (PHPUnit):**
  ```cmd
  cmd.exe /c "php83.bat vendor/bin/phpunit"
  ```

---

## 6. Status Pengembangan Saat Ini
- **Fase 0 (Perencanaan & Audit):** Selesai 100%.
- **Fase 1 (Pondasi Sistem & Autentikasi):** Selesai 100% (Users, Settings, AuditLogs, Login/Logout, Rate Limiting, Update Password).
- **Fase 2 (Integrasi Master & RBAC):** Selesai 100% (SaeIntegrationInterface, SaeDatabaseDriver, RefSekolah, RefJurusan, RefGuru, RefSiswa, EnsureUserHasRole, RefSiswaPolicy dengan isolasi jurusan SEC-01 & SEC-02).
- **Fase 3 (Modul Persiapan, Mitra DU/DI & Penempatan):** Tahap pengembangan berikutnya (menunggu instruksi).

---

## 7. Dokumentasi Terkait
- [AGENTS.md](./AGENTS.md) — Aturan kerja AI Coding Agent.
- [REQUISITES.md](./REQUISITES.md) — Kebutuhan fungsional dan nonfungsional.
- [ARCHITECTURE.md](./ARCHITECTURE.md) — Arsitektur teknis, keamanan, dan alur integrasi.
- [DATABASE_DESIGN.md](./DATABASE_DESIGN.md) — Rancangan skema dan relasi entitas.
- [INTEGRATION_SAE.md](./INTEGRATION_SAE.md) — Kontrak integrasi dengan SAE Utama.
- [MODULES.md](./MODULES.md) — Rincian 20 modul operasional.
- [IMPLEMENTATION_PLAN.md](./IMPLEMENTATION_PLAN.md) — Roadmap implementasi bertahap.
- [WORKLOG.md](./WORKLOG.md) — Catatan log aktivitas dan keputusan arsitektur.

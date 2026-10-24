# AGENTS.md — Aturan Kerja dan Batasan AI Coding Agent

Dokumen ini mendefinisikan batasan, protokol kerja, dan panduan disiplin untuk AI Coding Agent dalam pengembangan proyek **SAE PKL (Sistem Aplikasi Edukasi — Praktik Kerja Lapangan)**.

---

## 1. Prinsip Utama (Core Principles)
1. **Disiplin Bertahap (Phased Execution):**
   - Kerjakan hanya fase yang secara eksplisit disetujui pengguna.
   - Dilarang melompat ke fase berikutnya sebelum fase saat ini selesai, diverifikasi, dan disetujui.
2. **Kedaulatan Data Induk SAE Utama (Master Data Sovereignty):**
   - DILARANG membuat master data siswa, guru, jurusan, atau sekolah tandingan di SAE PKL yang memerlukan input manual ulang.
   - Semua entitas primer wajib mengacu pada identitas resmi dari SAE Utama (`peserta_didik_id`, `ptk_id`, `jurusan_id`, `sekolah_id`).
3. **Keringkasan dan Efisiensi Kode (Minimalism & YAGNI):**
   - Bangun hanya apa yang diminta. Hindari over-engineering, abstraksi prematur, dan dependensi pihak ketiga yang tidak esensial.
   - Buat file controller, model, service, dan blade terpisah dan terfokus (< 300-400 baris per file).
4. **Keamanan dan Integritas (Security-First):**
   - Wajib menerapkan server-side authorization (Policy / Middleware) di setiap endpoint. Proteksi antarmuka (UI hidden) tidak dianggap sebagai perlindungan.
   - Tidak pernah menyimpan kredensial, token API, atau rahasia di dalam kode repositori.
5. **Transparansi Kerja (Documented Decisions):**
   - Setiap keputusan arsitektur, perubahan skema, hasil pengujian, dan kendala wajib dicatat di `WORKLOG.md`.

---

## 2. Batasan dan Larangan Keras (Hard Constraints)
- **DILARANG** melakukan modifikasi, penulisan ulang, atau penghapusan file di proyek SAE Utama (`c:\laragon\www\sae`). Akses ke SAE Utama bersifat READ-ONLY untuk keperluan audit referensi dan kontrak integrasi.
- **DILARANG** menjalankan perintah migrasi destruktif (`migrate:fresh`, `migrate:reset`, `db:wipe`) di lingkungan produksi atau tanpa konfirmasi eksplisit dari pengguna.
- **DILARANG** mengarang format data, token API, endpoint, atau kolom database yang belum divalidasi.
- **DILARANG** memalsukan hasil pengujian (mocking data tanpa label jelas, atau mengklaim tes lolos tanpa eksekusi nyata).

---

## 3. Protokol Setiap Fase Pengerjaan

### Sebelum Memulai Fase
1. Baca dokumen acuan: `REQUISITES.md`, `ARCHITECTURE.md`, `DATABASE_DESIGN.md`, `MODULES.md`, `IMPLEMENTATION_PLAN.md`.
2. Identifikasi dependensi, asumsi, dan potensi celah informasi.
3. Susun daftar rencana file yang akan dibuat/diubah.
4. Tentukan kriteria penerimaan dan skenario uji.

### Selama Pengerjaan
1. Gunakan perintah ecosystem resmi (Composer, Artisan CLI).
2. Lakukan perubahan bertahap: buat migrasi -> model -> policy/service -> controller -> request validation -> view/test.
3. Jalankan pengujian otomatis atau pemeriksaan sintaks PHP (`php -l`) secara berkala.
4. Tandai penyederhanaan yang disengaja dengan format:
   `// ponytail: [alasan penyederhanaan dan path peningkatan]`

### Setelah Menyelesaikan Fase
1. Ringkas perubahan yang telah diimplementasikan.
2. Cantumkan daftar file baru atau termodifikasi.
3. Laporkan hasil eksekusi pengujian secara faktual.
4. Catat risiko residual dan item tunda di `WORKLOG.md`.
5. Tampilkan laporan ke pengguna dan **BERHENTI** menunggu konfirmasi sebelum melangkah ke fase berikutnya.

---

## 4. Panduan Eksekusi Teknis di Lingkungan Windows / Laragon
- **Runtime PHP:** Proyek menggunakan **PHP 8.3**. Di lingkungan terminal Windows/Laragon, gunakan wrapper yang telah disediakan:
  * `artisan.bat` untuk menjalankan perintah artisan (misal: `cmd.exe /c "artisan.bat migrate"`).
  * `php83.bat` untuk mengeksekusi PHP 8.3 secara langsung (misal: `cmd.exe /c "php83.bat vendor/bin/phpunit"`).
- **Perintah Setup Admin Aman:**
  * `cmd.exe /c "artisan.bat sae:admin-setup"`
- **Perintah Sinkronisasi Data Master SAE:**
  * `cmd.exe /c "artisan.bat sae:sync-master"`
- **Menjalankan Pengujian Otomatis:**
  * `cmd.exe /c "php83.bat vendor/bin/phpunit"`
- **Prasyarat Masuk ke Fase 3 (Modul Persiapan, Mitra DU/DI & Penempatan):**
  * Seluruh 19 automated tests fondasi wajib lolos 100%.
  * Database SAE Utama berstatus READ-ONLY dan tidak dimodifikasi.
  * Otorisasi role dan department policy tetap ditegakkan di sisi server.


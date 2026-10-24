# DATABASE_DESIGN.md — Rancangan Basis Data SAE PKL

Dokumen ini memetakan rancangan entitas relasional basis data untuk **SAE PKL**. Dokumen ini adalah rancangan konseptual dan logis; **tidak ada migrasi yang dijalankan pada Fase 0**.

---

## 1. Prinsip Desain Basis Data
1. **Pemisahan Tegas Master vs Operasional:**
   - Tabel master (`peserta_didik`, `gtk`, `kompetensi_keahlian`, `sekolah`) berada di SAE Utama.
   - Database SAE PKL hanya menyimpan tabel operasional PKL yang merujuk pada UUID identitas resmi SAE Utama.
2. **Snapshot Referensi Lokal Minimal:**
   - Untuk performa tampilan dan integritas histori, tabel snapshot lokal (`ref_siswa`, `ref_guru`, `ref_jurusan`) dapat menyimpan salinan terindeks yang diperbarui secara periodik melalui modul sinkronisasi.
3. **Integritas Relasional:**
   - Foreign Key Constraints digunakan pada relasi antar-tabel operasional internal.
   - Kolom identitas eksternal (`peserta_didik_id`, `ptk_id`, `jurusan_id`) diberi tipe data `VARCHAR(50)` terindeks.

---

## 2. Struktur Entitas & Kamus Data

### A. Entitas Referensi Lokal [STATUS: TERIMPLEMENTASI (Fase 2)]
- **`ref_sekolah`**
  - `sekolah_id` (VARCHAR 50, PK) — UUID dari `sekolah.sekolah_id`
  - `npsn` (VARCHAR 20, index)
  - `nama` (VARCHAR 150)
  - `alamat` (TEXT, nullable)
  - `kepala_sekolah_nama` (VARCHAR 150, nullable)
  - `kepala_sekolah_nip` (VARCHAR 30, nullable)
  - `synced_at` (TIMESTAMP)
  - `created_at`, `updated_at`

- **`ref_jurusan`**
  - `jurusan_id` (VARCHAR 50, PK) — Kode/UUID dari `rombongan_belajar.jurusan_id`
  - `nama_jurusan` (VARCHAR 150)
  - `bidang_keahlian` (VARCHAR 100, nullable)
  - `program_keahlian` (VARCHAR 100, nullable)
  - `kajur_ptk_id` (VARCHAR 50, nullable, index) — UUID PTK yang menjabat Kajur
  - `kajur_nama` (VARCHAR 150, nullable)
  - `synced_at` (TIMESTAMP)
  - `created_at`, `updated_at`

- **`ref_guru`**
  - `ptk_id` (VARCHAR 50, PK) — UUID dari `gtk.ptk_id`
  - `nuptk` (VARCHAR 30, nullable, index)
  - `nip` (VARCHAR 30, nullable, index)
  - `nama` (VARCHAR 150)
  - `jenis_ptk` (VARCHAR 100, nullable)
  - `no_hp` (VARCHAR 25, nullable)
  - `is_active` (BOOLEAN, default true)
  - `synced_at` (TIMESTAMP)
  - `created_at`, `updated_at`

- **`ref_siswa`**
  - `peserta_didik_id` (VARCHAR 50, PK) — UUID dari `peserta_didik.peserta_didik_id`
  - `nisn` (VARCHAR 20, index)
  - `nipd` (VARCHAR 25, nullable, index)
  - `nik` (VARCHAR 25, nullable, index)
  - `nama` (VARCHAR 150)
  - `jenis_kelamin` (ENUM('L', 'P'))
  - `nama_rombel` (VARCHAR 50, nullable)
  - `rombongan_belajar_id` (VARCHAR 50, nullable, index)
  - `jurusan_id` (VARCHAR 50, index)
  - `no_hp` (VARCHAR 25, nullable)
  - `status_kesiapan` (ENUM('belum_verifikasi', 'siap', 'tidak_siap'), default 'belum_verifikasi')
  - `catatan_kesiapan` (TEXT, nullable)
  - `synced_at` (TIMESTAMP)
  - `created_at`, `updated_at`

---

### B. Entitas Pengguna & Konfigurasi Fondasi [STATUS: TERIMPLEMENTASI (Fase 1)]
- **`users`**
  - `id` (BIGINT, PK, auto-increment)
  - `username` (VARCHAR 100, unique)
  - `nama` (VARCHAR 150)
  - `email` (VARCHAR 150, nullable)
  - `password` (VARCHAR 255)
  - `role` (ENUM('admin', 'kepsek', 'waka_hubin', 'kajur', 'guru_pembimbing', 'pembimbing_industri', 'siswa'))
  - `ptk_id` (VARCHAR 50, nullable, index) — Tautan ke ID resmi GTK SAE Utama
  - `peserta_didik_id` (VARCHAR 50, nullable, index) — Tautan ke ID resmi Siswa SAE Utama
  - `mitra_id` (BIGINT, nullable, index) — Tautan ke mitra industri (khusus pembimbing industri)
  - `jurusan_id` (VARCHAR 50, nullable, index) — Tautan kode jurusan (khusus isolasi wewenang Kajur)
  - `is_active` (BOOLEAN, default true)
  - `remember_token` (VARCHAR 100, nullable)
  - `created_at`, `updated_at`

- **`settings`**
  - `id` (BIGINT, PK, auto-increment)
  - `key` (VARCHAR 100, unique)
  - `value` (TEXT, nullable)
  - `type` (VARCHAR 30, default 'string') — 'string', 'boolean', 'json', 'integer'
  - `group` (VARCHAR 50, default 'general') — 'general', 'pkl', 'integration'
  - `created_at`, `updated_at`

- **`audit_logs`**
  - `id` (BIGINT, PK, auto-increment)
  - `user_id` (BIGINT, nullable, FK -> users.id)
  - `role` (VARCHAR 50, nullable)
  - `action` (VARCHAR 100) — misal 'login', 'penempatan.approve', 'settings.update'
  - `auditable_type` (VARCHAR 100, nullable)
  - `auditable_id` (VARCHAR 50, nullable)
  - `old_values` (JSON, nullable)
  - `new_values` (JSON, nullable)
  - `ip_address` (VARCHAR 45, nullable)
  - `user_agent` (TEXT, nullable)
  - `created_at` (TIMESTAMP)

---

### C. Entitas Operasional PKL [STATUS: RANCANGAN MASA DEPAN — BELUM DIMIGRASI (Fase 3 & Seterusnya)]

- **`pkl_periode`**
  - `id` (BIGINT, PK)
  - `tahun_ajaran` (VARCHAR 20) — misal '2026/2027'
  - `semester` (ENUM('ganjil', 'genap'))
  - `nama_periode` (VARCHAR 100) — misal 'PKL Gelombang 1 Tahun 2026'
  - `tanggal_mulai` (DATE)
  - `tanggal_selesai` (DATE)
  - `status` (ENUM('draft', 'aktif', 'selesai', 'arsip'), default 'draft')
  - `pengaturan` (JSON, nullable) — konfigurasi batas jam jurnal, bobot nilai
  - `created_at`, `updated_at`

- **`mitra_industri`**
  - `id` (BIGINT, PK)
  - `nama_perusahaan` (VARCHAR 150)
  - `bidang_usaha` (VARCHAR 100)
  - `alamat` (TEXT)
  - `kota` (VARCHAR 100)
  - `nama_kontak` (VARCHAR 100)
  - `no_telepon` (VARCHAR 30)
  - `email` (VARCHAR 100, nullable)
  - `latitude` (DECIMAL 10,8, nullable)
  - `longitude` (DECIMAL 11,8, nullable)
  - `radius_meter` (INT, default 100) — untuk radius toleransi presensi GPS
  - `status_kerjasama` (ENUM('aktif', 'nonaktif', 'dalam_penjajakan'), default 'aktif')
  - `created_at`, `updated_at`

- **`mitra_kuota`**
  - `id` (BIGINT, PK)
  - `mitra_id` (BIGINT, FK -> mitra_industri.id)
  - `periode_id` (BIGINT, FK -> pkl_periode.id)
  - `jurusan_id` (VARCHAR 50, index) — FK logis -> ref_jurusan
  - `kuota_pria` (INT, default 0)
  - `kuota_wanita` (INT, default 0)
  - `kuota_total` (INT)
  - `kriteria_khusus` (TEXT, nullable)
  - `created_at`, `updated_at`

- **`penempatan_pkl`**
  - `id` (BIGINT, PK)
  - `periode_id` (BIGINT, FK -> pkl_periode.id)
  - `peserta_didik_id` (VARCHAR 50, index)
  - `jurusan_id` (VARCHAR 50, index)
  - `mitra_id` (BIGINT, FK -> mitra_industri.id)
  - `status_penempatan` (ENUM('diusulkan', 'disetujui_hubin', 'diterima_industri', 'berjalan', 'selesai', 'dibatalkan', 'pindah'))
  - `diajukan_oleh` (ENUM('siswa', 'kajur', 'waka_hubin'))
  - `tanggal_mulai_efektif` (DATE)
  - `tanggal_selesai_efektif` (DATE)
  - `catatan` (TEXT, nullable)
  - `created_at`, `updated_at`

- **`penugasan_pembimbing`**
  - `id` (BIGINT, PK)
  - `penempatan_id` (BIGINT, FK -> penempatan_pkl.id)
  - `guru_pembimbing_id` (VARCHAR 50, index) — FK logis -> ref_guru.ptk_id
  - `pembimbing_industri_id` (BIGINT, nullable, FK -> users.id)
  - `nomor_surat_tugas` (VARCHAR 100, nullable)
  - `is_active` (BOOLEAN, default true)
  - `created_at`, `updated_at`

- **`presensi_pkl`**
  - `id` (BIGINT, PK)
  - `penempatan_id` (BIGINT, FK -> penempatan_pkl.id)
  - `tanggal` (DATE, index)
  - `jam_masuk` (TIME, nullable)
  - `jam_keluar` (TIME, nullable)
  - `status_kehadiran` (ENUM('hadir', 'sakit', 'izin', 'alpha', 'libur_industri'))
  - `keterangan` (TEXT, nullable)
  - `lampiran_surat` (VARCHAR 255, nullable) — surat dokter / izin
  - `latitude_masuk` (DECIMAL 10,8, nullable)
  - `longitude_masuk` (DECIMAL 11,8, nullable)
  - `status_validasi` (ENUM('menunggu', 'disetujui', 'ditolak'), default 'menunggu')
  - `divalidasi_oleh` (BIGINT, nullable, FK -> users.id)
  - `divalidasi_pada` (TIMESTAMP, nullable)
  - `created_at`, `updated_at`

- **`jurnal_harian`**
  - `id` (BIGINT, PK)
  - `penempatan_id` (BIGINT, FK -> penempatan_pkl.id)
  - `tanggal` (DATE, index)
  - `divisi_bagian` (VARCHAR 100, nullable)
  - `ringkasan_kegiatan` (TEXT)
  - `hasil_capaian` (TEXT, nullable)
  - `kendala_solusi` (TEXT, nullable)
  - `foto_kegiatan` (VARCHAR 255, nullable)
  - `status_verifikasi` (ENUM('draft', 'diajukan', 'disetujui', 'revisi'), default 'diajukan')
  - `catatan_pembimbing_industri` (TEXT, nullable)
  - `catatan_guru_pembimbing` (TEXT, nullable)
  - `created_at`, `updated_at`

- **`monitoring_kunjungan`**
  - `id` (BIGINT, PK)
  - `periode_id` (BIGINT, FK -> pkl_periode.id)
  - `mitra_id` (BIGINT, FK -> mitra_industri.id)
  - `guru_pembimbing_id` (VARCHAR 50, index)
  - `tanggal_kunjungan` (DATE)
  - `kategori_kunjungan` (ENUM('antar_siswa', 'monitoring_berkala', 'penjemputan_evaluasi', 'penanganan_kasus'))
  - `catatan_perkembangan` (TEXT)
  - `catatan_industri` (TEXT, nullable)
  - `kendala_siswa` (TEXT, nullable)
  - `rencana_tindak_lanjut` (TEXT, nullable)
  - `foto_monitoring` (VARCHAR 255, nullable)
  - `created_at`, `updated_at`

- **`penilaian_pkl`**
  - `id` (BIGINT, PK)
  - `penempatan_id` (BIGINT, FK -> penempatan_pkl.id, unique)
  - `skor_industri_teknis` (DECIMAL 5,2, nullable)
  - `skor_industri_non_teknis` (DECIMAL 5,2, nullable) — etika, kedisiplinan
  - `nilai_akhir_industri` (DECIMAL 5,2, nullable)
  - `catatan_industri` (TEXT, nullable)
  - `skor_sekolah_jurnal` (DECIMAL 5,2, nullable)
  - `skor_sekolah_laporan` (DECIMAL 5,2, nullable)
  - `skor_sekolah_monitoring` (DECIMAL 5,2, nullable)
  - `nilai_akhir_sekolah` (DECIMAL 5,2, nullable)
  - `nilai_akhir_gabungan` (DECIMAL 5,2, nullable)
  - `predikat` (VARCHAR 10, nullable) — Sangat Baik, Baik, Cukup
  - `status_penilaian` (ENUM('draft', 'final'), default 'draft')
  - `created_at`, `updated_at`

- **`sertifikat_pkl`**
  - `id` (BIGINT, PK)
  - `penempatan_id` (BIGINT, FK -> penempatan_pkl.id, unique)
  - `nomor_sertifikat` (VARCHAR 100, unique)
  - `tanggal_terbit` (DATE)
  - `qr_token` (VARCHAR 100, unique) — untuk verifikasi publik dokumen
  - `file_path` (VARCHAR 255, nullable)
  - `status_penerbitan` (ENUM('draft', 'diterbitkan', 'dibatalkan'), default 'draft')
  - `created_at`, `updated_at`

---

## 3. Strategi Indexing & Performa
- Indeks komposit pada `penempatan_pkl`: `(periode_id, jurusan_id)`, `(periode_id, mitra_id)`.
- Indeks komposit pada `presensi_pkl`: `(penempatan_id, tanggal)`.
- Indeks komposit pada `jurnal_harian`: `(penempatan_id, tanggal)`.
- Indeks unik pada `penilaian_pkl`: `(penempatan_id)`.
- Indeks unik pada `sertifikat_pkl`: `(nomor_sertifikat)`, `(qr_token)`.

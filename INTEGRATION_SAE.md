# INTEGRATION_SAE.md — Kontrak dan Strategi Integrasi SAE Utama

Dokumen ini mendokumentasikan hasil audit teknis basis kode **SAE Utama** (`c:\laragon\www\sae`), kontrak integrasi data induk, informasi yang telah diverifikasi, serta informasi yang masih dibutuhkan.

---

## 1. Hasil Audit Teknis SAE Utama (Fakta Terverifikasi)

Berdasarkan pemeriksaan langsung terhadap repositori SAE Utama di `c:\laragon\www\sae`:

| Entitas | Nama Tabel di SAE Utama | Primary Key | Format Identitas | Keterangan & Karakteristik |
| :--- | :--- | :--- | :--- | :--- |
| **Siswa** | `peserta_didik` | `peserta_didik_id` | String UUID (Dapodik) | Berisi: NISN, NIK, NIPD, nama, rombel, dll. |
| **Guru/GTK** | `gtk` | `ptk_id` | String UUID (Dapodik) | Berisi: NUPTK, NIP, NIK, nama, jenis PTK, kontak. |
| **Jurusan** | `rombongan_belajar` & `kompetensi_keahlian` | `jurusan_id` | String | Diambil dari atribut rombel (`jurusan_id`, `jurusan_id_str`). |
| **Sekolah** | `sekolah` | `sekolah_id` | String UUID (Dapodik) | Berisi: NPSN, nama sekolah, alamat, koordinat peta. |
| **Tugas Tambahan** | `ptk_tugas_tambahan` | `id` | BigInt Auto-increment | Terkait `ref_tugas_tambahan`: `WAKA_HUBIN`, `KAPROG` (ada `jurusan_id`). |
| **Akun Pengguna** | `pengguna` | `pengguna_id` | String UUID | Autentikasi berbasis session web dan Sanctum API (`/api/v1/...`). |

---

## 2. Status Integrasi Saat Ini

### A. Informasi yang Berhasil Diverifikasi:
1. **Model Relasi Pengguna:** Akun guru dihubungkan dengan `ptk_id`, akun siswa dihubungkan dengan `peserta_didik_id`.
2. **Penentuan Peran Waka Hubin & Kajur:** SAE Utama memiliki tabel `ptk_tugas_tambahan` dengan relasi `ref_tugas_tambahan` ber-kode `'WAKA_HUBIN'` dan `'KAPROG'` (memiliki field `jurusan_id`). Hal ini memungkinkan SAE PKL mendeteksi otomatis siapa guru yang bertindak sebagai Waka Hubin dan Kajur.
3. **Endpoint API Eksisting:** SAE Utama telah memiliki Sanctum Token API di `/api/v1/auth/login` dan `/api/v1/auth/profile`.

### B. Informasi yang Belum Tersedia (Gaps):
1. **Endpoint Khusus Query Master PKL:** SAE Utama belum memiliki endpoint REST API siap pakai untuk mengambil daftar siswa per jurusan atau daftar GTK secara massal (hanya ada `/api/v1/auth/...` dan `/api/receive-data` dari Dapodik feeder).
2. **Kredensial Machine-to-Machine (M2M):** Belum ada definisi API token/Secret Key yang dialokasikan khusus untuk komunikasi antar-aplikasi SAE PKL <-> SAE Utama.

---

## 3. Rancangan Kontrak Integrasi (SaeIntegrationInterface)

Untuk mengisolasi logika integrasi agar SAE PKL tidak terikat langsung pada struktur database SAE Utama yang dinamis, dibuatkan contract antarmuka:

```php
namespace App\Contracts;

interface SaeIntegrationInterface
{
    /**
     * Mengambil identitas resmi sekolah.
     */
    public function getSekolah(): array;

    /**
     * Mengambil daftar jurusan / program keahlian aktif.
     */
    public function getJurusanList(): array;

    /**
     * Mengambil daftar GTK aktif.
     */
    public function getGtkList(): array;

    /**
     * Mengambil daftar siswa aktif berdasarkan filter jurusan atau rombel.
     */
    public function getPesertaDidikByJurusan(string $jurusanId): array;

    /**
     * Mengambil penugasan tugas tambahan aktif (Waka Hubin & Kajur per jurusan).
     */
    public function getActiveDuties(): array;

    /**
     * Memvalidasi kredensial pengguna terhadap SAE Utama (Single Sign-On / Autentikasi Delegasi).
     */
    public function verifyUserCredential(string $username, string $password): ?array;
}
```

---

## 4. Evaluasi dan Rekomendasi Driver Integrasi

Berdasarkan audit teknis, kebutuhan lingkungan Laragon lokal, serta batasan aturan proyek, berikut evaluasi komprehensif driver:

### Opsi 1: Direct Read-Only Database Driver (`SaeDatabaseDriver`) — *Rekomendasi Driver Awal*
- **Karakteristik & Mekanisme:**
  - Menggunakan koneksi database sekunder `mysql_sae_master` di `config/database.php` SAE PKL.
  - Mengakses tabel `peserta_didik`, `gtk`, `sekolah`, `rombongan_belajar`, `ptk_tugas_tambahan`, dan `ref_tugas_tambahan` langsung di instans MySQL/MariaDB yang sama.
- **Jaminan Keamanan Strict Read-Only:**
  1. **Tingkat Database:** Kredensial MySQL yang digunakan oleh koneksi `mysql_sae_master` dibatasi hanya dengan hak akses `SELECT`. Perintah `INSERT`, `UPDATE`, `DELETE`, `DROP`, `ALTER`, `TRUNCATE` ditolak secara mutlak oleh database engine.
  2. **Tingkat Aplikasi:** Driver tidak menggunakan Eloquent model mutatif untuk SAE Utama, melainkan query builder berarah baca saja (`DB::connection('mysql_sae_master')->table(...)->select(...)`).
  3. **Zero-Touch SAE Utama:** Memenuhi aturan mutlak bahwa repositori SAE Utama tidak dimodifikasi sebaris kode pun.
- **Ketergantungan Skema:** Driver ini bergantung pada kestabilan nama tabel dan kolom di SAE Utama. Seluruh pembacaan dipetakan ke Data Transfer Object (DTO) internal SAE PKL sebelum disimpan ke tabel snapshot lokal (`ref_*`).
- **Alasan Pemilihan Awal:** Solusi paling realistis, aman, dan berkinerja tinggi saat ini karena SAE Utama belum memiliki endpoint REST API siap pakai untuk data master PKL.

### Opsi 2: HTTP REST API Driver (`SaeHttpApiDriver`) — *Target Migrasi Jangka Menengah/Panjang*
- **Karakteristik:** Memanggil REST API HTTPS resmi di SAE Utama (`/api/v1/integration/...`).
- **Status Saat Ini:** Rute `routes/api.php` di SAE Utama saat ini hanya melayani `/api/v1/auth/...` (Sanctum) dan `/api/receive-data` (feeder Dapodik). Endpoint penyedia data master PKL belum ada.
- **Rencana Migrasi:** 
  - Karena seluruh logika bisnis SAE PKL hanya berkomunikasi melalui contract `SaeIntegrationInterface`, proses migrasi ke REST API di masa mendatang cukup dilakukan dengan mengimplementasikan `SaeHttpApiDriver` dan mengganti konfigurasi environment `SAE_INTEGRATION_DRIVER=http` tanpa merombak modul operasional PKL yang telah selesai.

---

## 6. Prinsip dan Kebutuhan Arsitektur Integrasi Kehadiran PKL ke SAE Utama (Terkonsolidasi)

Kelak pada saat modul operasional PKL (Fase 4) diimplementasikan, catatan kehadiran siswa PKL di tempat industri perlu disinkronkan kembali ke sistem pelaporan kehadiran terpusat di SAE Utama.

Prinsip dan kebutuhan arsitektur yang ditetapkan sejak awal fondasi ini:
1. **Penyimpanan Lokal Mandiri:** Seluruh transaksi presensi harian siswa PKL dicatat, divalidasi, dan disimpan di database operasional SAE PKL (`presensi_pkl`).
2. **Kanal Pengiriman Resmi:** SAE Utama menerima data kehadiran PKL melalui endpoint API resmi atau mekanisme penerima data terverifikasi (misal: `/api/v1/attendance/receive-pkl`).
3. **Pembedaan Status Kehadiran:** Kehadiran PKL harus dapat dibedakan secara tegas dari kehadiran reguler kelas di sekolah (menggunakan atribut penanda seperti `is_pkl = true` atau kategori rombel PKL).
4. **Idempoten (Idempotency):** Pengiriman ulang data kehadiran yang sama akibat retry jaringan tidak boleh menghasilkan duplikasi catatan di SAE Utama (menggunakan UUID komposit `peserta_didik_id + tanggal`).
5. **Ketahanan Data Lokal (Resilience):** Jika koneksi jaringan ke SAE Utama terputus, data kehadiran lokal tetap aman dan berstatus `pending_sync`, tanpa kehilangan riwayat kehadiran sedikit pun.
6. **Jejak Audit Lengkap:** Setiap koreksi data kehadiran oleh pembimbing serta setiap pengiriman batch ke SAE Utama wajib tercatat di `audit_logs`.

*Catatan Ruang Lingkup:* Sesuai batasan fondasi, tabel `presensi_pkl`, controller, endpoint penerima, dan proses sinkronisasi kehadiran ini **TIDAK diimplementasikan pada tahap finalisasi fondasi**, melainkan dialokasikan pada **Fase 4 (Modul Operasional PKL)**.


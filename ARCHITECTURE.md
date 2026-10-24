# ARCHITECTURE.md — Arsitektur Sistem SAE PKL

Dokumen ini menjelaskan rancangan arsitektur perangkat lunak, pola desain, batas keamanan, dan strategi integrasi untuk **SAE PKL**.

---

## 1. Ikhtisar Arsitektur (Architectural Overview)

SAE PKL dibangun di atas framework **Laravel 12** berjalan pada **PHP 8.3+**, mengadopsi pola arsitektur berlapis (*Layered Clean Architecture*) yang disesuaikan dengan ekosistem Laravel modern:

```
[ Browser / Mobile Client ]
           │
           ▼
   [ Route & Middleware Group ]  ───> [ CSRF, Session, Auth, Role & Permission ]
           │
           ▼
     [ Controllers ]            ───> Penanganan HTTP Request & Response tipis (< 150 baris)
           │
     ┌─────┴────────────────┐
     ▼                      ▼
[ Form Requests ]     [ Service / Action Classes ] ───> Logika Bisnis & Transaksi DB
(Validasi Input)            │
                            ▼
                     [ Eloquent Models ]           ───> Entitas, Scopes & Relasi
                            │
                            ▼
                   [ MySQL Database ]

[ External / Adapter Layer ]
           │
           ▼
[ SaeIntegrationService (Adapter Pattern) ]
     ├──> Mode 1: SaeDatabaseDriver (Direct Read-Only DB Connection, Strict SELECT)
     └──> Mode 2: SaeHttpApiDriver (REST API Client — Jalur Migrasi Masa Depan)
```

---

## 2. Pemisahan Tanggung Jawab (Separation of Concerns)

1. **HTTP Controllers:**
   - Hanya menerima request, memanggil Form Request untuk validasi, mendelegasikan pemrosesan ke Service/Action class, dan mengembalikan Response (View Blade atau JSON).
   - Dilarang menulis raw business logic atau query database yang rumit di dalam controller.
2. **Form Requests:**
   - Menangani sanitasi dan aturan validasi secara terisolasi.
   - Menyediakan pesan validasi dalam Bahasa Indonesia yang jelas.
3. **Services / Actions:**
   - Mengenkapsulasi alur bisnis kompleks (misalnya: kalkulasi nilai akhir, validasi penempatan kuota, proses penerbitan sertifikat).
   - Membungkus operasi multi-tabel dalam `DB::transaction()` untuk menjamin keutuhan data (*Atomicity*).
4. **Policies & Gates:**
   - Menegakkan otorisasi granular di sisi server sebelum aksi dieksekusi.
   - Contoh: `KajurPolicy::viewAny()` membatasi query hanya pada siswa dengan `jurusan_id` yang ditugaskan kepada Kajur.
5. **Eloquent Models:**
   - Merepresentasikan struktur tabel operasional SAE PKL.
   - Dilengkapi *Local Scopes* untuk filter umum (misal: `scopeActivePeriod()`, `scopeForDepartment($jurusanId)`).

---

## 3. Strategi Integrasi Data Induk SAE Utama (Adapter Pattern)

Untuk memenuhi aturan mutlak pada Bagian 3 Master Prompt, sistem menggunakan **Pola Adapter (Driver-based Architecture)** melalui contract `SaeIntegrationInterface`:

```php
namespace App\Contracts;

interface SaeIntegrationInterface
{
    public function getSekolah(): array;
    public function getJurusanList(): array;
    public function getGtkList(): array;
    public function getPesertaDidikByJurusan(string $jurusanId): array;
    public function getActiveDuties(): array; // Mengambil penugasan Waka Hubin & Kajur
    public function verifyUserCredential(string $username, string $password): ?array;
}
```

### Driver dan Kebijakan Integrasi:
1. **`SaeDatabaseDriver` (Driver Default Awal — Lingkungan Laragon Lokal):**
   - Menggunakan koneksi database sekunder `mysql_sae_master` di `config/database.php`.
   - **Jaminan Keamanan Strict Read-Only:**
     * Hak akses user MySQL khusus dibatasi hanya `SELECT` (`GRANT SELECT ON ...`).
     * Dilarang keras menjalankan `INSERT`, `UPDATE`, `DELETE`, `DROP`, atau manipulasi DDL pada database SAE Utama.
     * Kelas driver membungkus seluruh pembacaan dengan proteksi pengecualian jika terjadi mutasi data tak disengaja.
   - Sangat efisien untuk fase pengembangan lokal di mana SAE Utama dan SAE PKL berada pada satu server MariaDB/MySQL Laragon, tanpa mengubah kode SAE Utama.
2. **`SaeHttpApiDriver` (Jalur Migrasi Masa Depan — Arsitektur Multi-Server):**
   - Menggunakan HTTP Client (Guzzle) untuk memanggil API resmi SAE Utama (`/api/v1/...`).
   - Dapat diaktifkan melalui konfigurasi `.env` (`SAE_INTEGRATION_DRIVER=http`) tanpa mengubah logika bisnis operasional PKL.

### Kebutuhan Arsitektur Integrasi Kehadiran PKL ke SAE Utama (Fase 4):
- Data kehadiran siswa dicatat dan disimpan secara mandiri di database SAE PKL (`presensi_pkl`).
- SAE Utama menerima data kehadiran PKL melalui API resmi terautentikasi atau mekanisme penerima yang disetujui.
- Kehadiran PKL dapat dibedakan dari kehadiran reguler sekolah (misal: flag `is_pkl = true` atau kategori khusus).
- Mekanisme pengiriman idempoten (pengiriman ulang akibat retry jaringan tidak menghasilkan duplikasi).
- Ketahanan data lokal: kegagalan koneksi ke SAE Utama tidak boleh menghilangkan catatan lokal.
- Setiap koreksi dan pengiriman memiliki jejak audit lengkap di `audit_logs`.
*(Tabel kehadiran dan modul sinkronisasi kehadiran dialokasikan pada Fase 4 dan tidak dibuat pada fondasi ini).*

---

## 4. Keamanan dan Otorisasi (Security & RBAC)

### A. Lapisan Pertahanan Otorisasi (Defense in Depth)
- **Layer 1 (Route Middleware):** Memfilter akses dasar berdasarkan peran pengguna (`role:admin`, `role:kajur`, `role:waka_hubin`, dsb).
- **Layer 2 (Policy Checks):** Memverifikasi kepemilikan data sebelum membaca atau mengubah record (`$this->authorize('update', $penempatan)`).
- **Layer 3 (Database Scoping):** Mencegah kebocoran data dengan menambahkan filter otomatis pada query model:
  - Kajur selalu terikat: `where('jurusan_id', auth()->user()->jurusan_id)`.
  - Waka Hubin: Memiliki cakupan seluruh jurusan dalam sekolah (`jurusan_id = null`).
  - Pembimbing Industri selalu terikat: `where('mitra_id', auth()->user()->mitra_id)`.
  - Guru Pembimbing selalu terikat: `where('guru_pembimbing_id', auth()->user()->ptk_id)`.
  - Siswa selalu terikat: `where('peserta_didik_id', auth()->user()->peserta_didik_id)`.

### B. Proteksi Ekstra
- Token CSRF di seluruh form mutasi data.
- Rate Limiting pada rute autentikasi (`throttle:5,1`).
- Upload dokumen/foto divalidasi MIME type ketat (hanya PDF, JPG, PNG) dan disimpan dengan nama acak aman (*hash name*).

---

## 5. Pemisahan Hak Akses Login dari Sinkronisasi Data

Penting: **Sinkronisasi data master ke tabel referensi lokal (`ref_siswa`, `ref_guru`) TIDAK otomatis memberikan hak akses login.**
1. Data di `ref_siswa` dan `ref_guru` murni data administratif/operasional.
2. Akun login di tabel `users` dikelola terpisah:
   - Akun Siswa diaktifkan hanya ketika siswa tersebut masuk dalam periode PKL aktif dan diverifikasi siap oleh Kajur.
   - Akun Guru diaktifkan berdasarkan penugasan sebagai Guru Pembimbing, Kajur, atau Waka Hubin.
   - Akun Pembimbing Industri dibuat khusus secara lokal terikat pada `mitra_id` dan dikelola oleh Waka Hubin.
   - Dilarang menyalin hash password atau data autentikasi mentah dari database SAE Utama.
   - Admin dapat menonaktifkan akun login sewaktu-waktu tanpa menghapus histori data referensi siswa/guru.

---

## 6. Audit Trail & Logging
Tabel `audit_logs` mencatat seluruh peristiwa penting:
- `user_id`, `role`, `action` (create, update, delete, approve, reject).
- `auditable_type`, `auditable_id`.
- `old_values`, `new_values` (JSON diff).
- `ip_address`, `user_agent`, `created_at`.

---

## 7. Matriks Skenario Pengujian Keamanan & Otorisasi (Test Specification)

Berikut skenario pengujian wajib (*automated feature tests*) yang harus disiapkan untuk mencegah eskalasi wewenang dan kebocoran data:

| ID Uji | Skenario Uji | Aktor / Kondisi | Ekspektasi Hasil |
| :--- | :--- | :--- | :--- |
| **SEC-01** | Isolasi Data Kajur | Kajur Jurusan A mencoba mengakses detail/verifikasi siswa Jurusan B (`/kesiapan/{siswa_jurusan_b}`). | **HTTP 403 Forbidden** |
| **SEC-02** | Lingkup Data Waka Hubin | Waka Hubin mengakses daftar siswa & penempatan seluruh jurusan. | **HTTP 200 OK** (Data lintas jurusan tampil lengkap) |
| **SEC-03** | Pembatasan Guru Pembimbing | Guru X mencoba mengakses atau memvalidasi jurnal siswa yang dibimbing oleh Guru Y. | **HTTP 403 Forbidden** |
| **SEC-04** | Pembatasan Pembimbing Industri | Pembimbing Industri PT A mencoba melihat presensi/jurnal siswa di PT B. | **HTTP 403 Forbidden** |
| **SEC-05** | Isolasi Privasi Siswa | Siswa A mencoba melihat atau mengedit jurnal/presensi Siswa B via manipulasi ID URL. | **HTTP 403 Forbidden** |
| **SEC-06** | Ketahanan Integrasi (Graceful Degradation) | Koneksi ke database/API SAE Utama terputus saat proses sinkronisasi dijalankan. | Sistem mencatat error ke log, menampilkan pesan gagal yang informatif, **TIDAK crash (500)**, dan **TIDAK mencatat status sinkronisasi sukses palsu**. |
| **SEC-07** | Validasi Integritas Penempatan | Mencoba menempatkan siswa pada industri yang kuotanya sudah habis atau siswa berstatus belum siap. | **HTTP 422 Unprocessable Entity** (Ditolak dengan pesan validasi) |
| **SEC-08** | Finalisasi Nilai & Sertifikat | Siswa yang belum memiliki nilai lengkap mencoba mengunduh sertifikat PKL. | **HTTP 403 / 422** (Penerbitan sertifikat terkunci) |



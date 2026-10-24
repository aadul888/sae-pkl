# REQUISITES.md — Kebutuhan Fungsional dan Nonfungsional

Dokumen ini memetakan seluruh spesifikasi kebutuhan fungsional (FR) dan nonfungsional (NFR) untuk sistem **SAE PKL**.

---

## 1. Kebutuhan Fungsional (Functional Requirements)

### FR-01: Integrasi & Sinkronisasi Data Induk (SAE Utama)
- **FR-01.1:** Sistem harus dapat mengambil dan memperbarui referensi data Siswa dari SAE Utama (`peserta_didik_id`, NISN, NIK, NIPD, nama, jenis kelamin, rombel, kontak).
- **FR-01.2:** Sistem harus dapat mengambil referensi Guru/GTK dari SAE Utama (`ptk_id`, NUPTK, NIP, nama, kontak).
- **FR-01.3:** Sistem harus dapat mengambil referensi Jurusan/Kompetensi Keahlian dari SAE Utama (`jurusan_id`, kode, nama).
- **FR-01.4:** Sistem harus dapat mengidentifikasi penugasan tugas tambahan resmi (`WAKA_HUBIN`, `KAPROG` per `jurusan_id`) dari SAE Utama.
- **FR-01.5:** Sistem tidak boleh menyediakan form input manual penambahan siswa, guru, atau jurusan baru yang melewati sinkronisasi SAE Utama.

### FR-02: Manajemen Periode & Konfigurasi PKL
- **FR-02.1:** Admin / Waka Hubin dapat membuat periode PKL (tahun ajaran, semester, tanggal mulai, tanggal selesai).
- **FR-02.2:** Mendukung pembagian gelombang (wave) pelaksanaan PKL dalam satu periode.
- **FR-02.3:** Konfigurasi batas waktu pengisian jurnal harian, toleransi keterlambatan presensi, dan bobot penilaian.

### FR-03: Manajemen Mitra DU/DI & Pemetaan Kompetensi
- **FR-03.1:** Waka Hubin dapat mengelola direktori mitra industri (nama perusahaan, bidang usaha, alamat, kontak penanggung jawab, koordinat lokasi jika ada).
- **FR-03.2:** Pengaturan kuota penerimaan siswa per industri per jurusan per periode.
- **FR-03.3:** Pemetaan kesesuaian kompetensi keahlian/jurusan dengan kriteria industri.

### FR-04: Verifikasi Kesiapan Siswa & Usulan Penempatan
- **FR-04.1:** Kajur dapat memverifikasi status kesiapan siswa jurusannya untuk mengikuti PKL (syarat akademis, kedisiplinan, administrasi).
- **FR-04.2:** Kajur atau Siswa (tergantung konfigurasi sekolah) dapat mengajukan usulan penempatan mitra DU/DI.
- **FR-04.3:** Waka Hubin memvalidasi dan menetapkan penempatan resmi siswa pada mitra DU/DI.

### FR-05: Penugasan Pembimbing (Sekolah & Industri)
- **FR-05.1:** Kajur/Hubin menetapkan Guru Pembimbing PKL untuk sekelompok siswa bimbingan.
- **FR-05.2:** Hubin/Industri menetapkan akun Pembimbing Industri yang bertindak sebagai supervisor lapangan.

### FR-06: Presensi Siswa PKL
- **FR-06.1:** Siswa dapat melakukan presensi harian (status: Hadir, Sakit, Izin, Libur Kerja).
- **FR-06.2:** Presensi mencatat timestamp server, koordinat GPS (opsional/terkonfigurasi), serta bukti surat untuk izin/sakit.
- **FR-06.3:** Pembimbing Industri dan Guru Pembimbing dapat memverifikasi atau mengoreksi presensi siswa.
- **FR-06.4:** Data presensi PKL dirancang untuk dapat disinkronkan ke sistem pelaporan kehadiran terpusat SAE Utama secara idempoten dan berjejak audit (lingkup Fase 4).

### FR-07: Jurnal Harian & Umpan Balik
- **FR-07.1:** Siswa wajib mengisi jurnal aktivitas harian (tanggal, ringkasan pekerjaan, dokumentasi foto, kendala yang dihadapi).
- **FR-07.2:** Guru Pembimbing dan Pembimbing Industri dapat meninjau, menyetujui, dan memberikan catatan umpan balik (feedback).
- **FR-07.3:** Pembatasan waktu pengisian jurnal untuk mencegah pengisian susulan yang tidak terkontrol.

### FR-08: Monitoring & Kunjungan Pembimbing
- **FR-08.1:** Guru Pembimbing mencatat agenda dan laporan kunjungan monitoring (tanggal, industri, siswa yang ditemui, catatan kemajuan, foto monitoring).
- **FR-08.2:** Catatan kendala khusus siswa dan tindak lanjut intervensi.

### FR-09: Penilaian PKL Multidimensi
- **FR-09.1:** Komponen penilaian industri (aspek teknis, kedisiplinan, inisiatif, etika kerja, kerjasama).
- **FR-09.2:** Komponen penilaian sekolah (jurnal harian, laporan akhir PKL, presentasi/evaluasi monitoring).
- **FR-09.3:** Kalkulasi otomatis nilai akhir terbobot berdasarkan formula yang disetujui sekolah.

### FR-10: Sertifikasi, Kelulusan & Laporan
- **FR-10.1:** Verifikasi kelengkapan administrasi sebelum siswa dinyatakan tuntas PKL.
- **FR-10.2:** Penerbitan nomor sertifikat otomatis dan pencetakan sertifikat PKL ber-QR Code verifikasi.
- **FR-10.3:** Ekspor laporan format PDF / Excel: rekap presensi, rekap nilai, laporan per jurusan, laporan per DU/DI, dan laporan tingkat sekolah.

---

## 2. Kebutuhan Nonfungsional (Non-Functional Requirements)

### NFR-01: Keamanan (Security)
- **Autentikasi & RBAC:** Otorisasi berbasis peran dengan policy granular di sisi server. Pembatasan akses jurusan mutlak pada layer Query/Policy.
- **Sanitasi & Proteksi:** Perlindungan terhadap CSRF, XSS, SQL Injection, dan IDOR (Insecure Direct Object References).
- **Perlindungan Data Sensitif:** Password ter-hash menggunakan bcrypt/Argon2. Tidak ada kredensial yang terbuka di repositori atau file publik.
- **Audit Logging:** Pencatatan otomatis setiap aksi penting (penetapan penempatan, input nilai, sinkronisasi data, perubahan status).

### NFR-02: Kinerja & Efisiensi (Performance)
- **Response Time:** Endpoint umum harus merespons dalam waktu < 400ms di lingkungan server lokal.
- **Indexing Database:** Seluruh foreign keys dan kolom pencarian (`peserta_didik_id`, `ptk_id`, `jurusan_id`, `periode_id`, `tanggal`) wajib memiliki indeks.
- **Eager Loading:** Mencegah masalah query N+1 pada data relasi kompleks (penempatan, presensi, jurnal).

### NFR-03: Ketersediaan & Ketahanan Integrasi (Resilience)
- **Graceful Fallback:** Kegagalan koneksi ke SAE Utama tidak boleh menyebabkan crash sistem; sistem harus mencatat error ke log dan menampilkan notifikasi ramah pengguna.
- **Status Sinkronisasi Transparan:** Menampilkan timestamp sinkronisasi terakhir dan status keberhasilan yang akurat. Tidak ada status sukses palsu.

### NFR-04: Usabilitas & Desain Responsif (Usability)
- **Aksesibilitas Mobile:** Seluruh modul siswa dan pembimbing (presensi, jurnal harian, monitoring) harus nyaman dioperasikan di layar smartphone.
- **Antarmuka Bersih:** Menggunakan prinsip antarmuka modern, minim distraksi, dan navigasi yang intuitif.

### NFR-05: Kepatuhan Arsitektur & Perawatan (Maintainability)
- **Modularitas Kode:** Pemisahan tegas antara Controller, Form Request, Service/Action, Policy, dan Model.
- **Ukuran File Terkendali:** Maksimal 300-400 baris kode per file agar mudah dipahami dan diuji.

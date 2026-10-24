# MODULES.md — Rincian 20 Modul Sistem SAE PKL

Dokumen ini memetakan 20 modul inti operasional sistem **SAE PKL**, mencakup tujuan, aktor, batas akses, dependensi, dan kriteria penerimaan (*acceptance criteria*).

---

| No | Modul | Aktor Utama | Batas Akses & Otorisasi | Dependensi | Kriteria Penerimaan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **01** | **Dashboard Berbasis Peran** | Semua Peran | Data disaring ketat di server sesuai role & relasi. | Modul 02, 04, 08, 10, 11 | Dashboard menampilkan widget spesifik peran; data jurusan lain tidak boleh bocor ke Kajur. |
| **02** | **Integrasi & Sinkronisasi SAE** | Admin | Khusus Admin; operasi read-only ke SAE Utama. | `SaeIntegrationInterface` | Sinkronisasi mengambil data siswa, guru, jurusan, penugasan tugas tambahan tanpa error. |
| **03** | **Manajemen Periode PKL** | Admin, Waka Hubin | Admin & Hubin dapat membuat/mengedit; peran lain read-only. | - | Periode memiliki rentang tanggal valid; hanya 1 periode aktif per gelombang. |
| **04** | **Referensi Siswa, Guru, Jurusan** | Semua Peran (sesuai scope) | Read-only; tidak ada form create manual data induk. | Modul 02 | Menampilkan daftar siswa/guru/jurusan hasil sinkronisasi; Kajur hanya melihat jurusannya. |
| **05** | **Manajemen Mitra DU/DI** | Waka Hubin, Admin | Hubin full akses; Kajur & Guru read-only. | - | Form pendataan mitra industri lengkap dengan koordinat peta dan kontak resmi. |
| **06** | **Kuota & Pemetaan Industri** | Waka Hubin, Kajur | Hubin menetapkan; Kajur melihat kesesuaian jurusan. | Modul 03, 05 | Kuota terbagi per jurusan; validasi tidak boleh menempatkan siswa melebihi kuota. |
| **07** | **Verifikasi Kesiapan Siswa** | Kajur | Kajur hanya memverifikasi siswa di jurusannya sendiri. | Modul 04 | Status kesiapan siswa dapat diubah (Siap / Belum / Tidak Siap) disertai catatan. |
| **08** | **Penempatan & Penugasan PKL** | Waka Hubin, Kajur, Siswa | Kajur usulkan; Hubin validasi; Siswa lihat status. | Modul 03, 05, 06, 07 | Siswa yang belum siap tidak dapat ditempatkan; penempatan tervalidasi mengubah status jadi resmi. |
| **09** | **Penugasan Guru Pembimbing** | Waka Hubin, Kajur | Hubin/Kajur menetapkan; Guru melihat daftar siswa. | Modul 04, 08 | Satu siswa bimbingan memiliki 1 guru pembimbing aktif; rasio beban bimbingan terpantau. |
| **10** | **Presensi Siswa PKL** | Siswa, Industri, Guru | Siswa isi; Industri validasi; Guru pantau bimbingan. | Modul 08 | Catatan presensi menyimpan timestamp, status kehadiran, lampiran izin/sakit, dan koordinat GPS. Data siap disinkronkan idempoten ke pelaporan terpusat SAE Utama. |
| **11** | **Jurnal Harian Siswa** | Siswa, Guru, Industri | Siswa tulis; Pembimbing beri catatan umpan balik. | Modul 08, 10 | Siswa wajib mengisi deskripsi kegiatan & foto; pembimbing dapat memberi revisi/persetujuan. |
| **12** | **Monitoring & Kunjungan** | Guru Pembimbing, Hubin | Guru mencatat kunjungan; Hubin pantau lintas jurusan. | Modul 08, 09 | Form laporan kunjungan mencatat tanggal, catatan industri, kendala, foto, dan rencana tindak lanjut. |
| **13** | **Penilaian PKL (Sekolah & DU/DI)** | Industri, Guru, Kajur | Industri isi nilai lapangan; Guru isi nilai laporan; Kajur pantau. | Modul 08, 11, 12 | Formula pembobotan menghasilkan nilai akhir otomatis; kunci nilai setelah difinalisasi. |
| **14** | **Pengelolaan Dokumen PKL** | Semua Peran | Sesuai kepemilikan dokumen (surat tugas, mou, laporan). | Modul 08 | Upload file terverifikasi MIME; file tersimpan aman dan dapat diunduh pihak berwenang. |
| **15** | **Sertifikat PKL** | Waka Hubin, Admin, Siswa | Hubin terbitkan; Siswa unduh setelah tuntas nilai & presensi. | Modul 08, 10, 13 | Nomor sertifikat unik; terdapat QR Code verifikasi integritas sertifikat publik. |
| **16** | **Laporan & Ekspor Data** | Kepsek, Hubin, Kajur | Kepsek/Hubin seluruh jurusan; Kajur khusus jurusannya. | Modul 08, 10, 13 | Ekspor laporan PDF/Excel rekap presensi, nilai, dan penempatan siap cetak. |
| **17** | **Notifikasi & Pengingat** | Semua Peran | Notifikasi jurnal belum diverifikasi, presensi alpha, dsb. | Modul 10, 11 | Menampilkan badge/alert notifikasi di antarmuka sistem. |
| **18** | **Manajemen Pengguna & RBAC** | Admin SAE PKL | Admin mengelola akun lokal & hak akses; isolasi ketat. | Modul 02, 04 | Pembatasan role, aktivasi/deaktivasi akun, reset kredensial pembimbing industri. |
| **19** | **Audit Log** | Admin, Kepsek | Read-only; tidak dapat dihapus oleh pengguna biasa. | Semua Modul | Mencatat setiap operasi mutasi data penting (aktor, IP, timestamp, aksi, delta JSON). |
| **20** | **Pengaturan Aplikasi** | Admin | Konfigurasi sistem (nama aplikasi, logo, toleransi, api key). | - | Pengaturan tersimpan aman dan memengaruhi perilaku aplikasi secara dinamis. |

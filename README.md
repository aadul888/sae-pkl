# SAE PKL (Sistem Aplikasi Edukasi — Praktik Kerja Lapangan)

Aplikasi manajemen administrasi **Praktik Kerja Lapangan (PKL)** untuk tingkat Sekolah Menengah Kejuruan (SMK), dirancang praktis, cepat, dan terintegrasi langsung dengan ekosistem aplikasi **SAE**.

---

## 🚀 Panduan Pemasangan Cepat (Fresh Install di Laragon)

Aplikasi ini sudah dilengkapi dengan installer otomatis sehingga pengguna tidak perlu melakukan konfigurasi teknis yang rumit.

### Langkah 1: Tempatkan Aplikasi
Pastikan folder aplikasi diletakkan di direktori web server Laragon:
```text
C:\laragon\www\sae-pkl
```

### Langkah 2: Jalankan Installer Otomatis
Buka folder `sae-pkl`, lalu **klik dua kali (double-click)** pada file:
```text
install.bat
```
Installer akan secara otomatis:
- Menyiapkan berkas konfigurasi sistem.
- Menghubungkan dan membuat basis data `sae_pkl`.
- Menyiapkan tabel-tabel data aplikasi.
- Memasukkan pengaturan awal sistem.

### Langkah 3: Buka Aplikasi di Browser
Setelah installer selesai, buka peramban web (Google Chrome / Microsoft Edge / Mozilla Firefox) dan ketik alamat:
- **`http://sae-pkl.test`** (jika fitur auto-virtualhost Laragon aktif), atau
- **`http://localhost:8000`** (jika menjalankan server via perintah `artisan.bat serve`).

---

## 🔑 Masuk ke Aplikasi

Gunakan akun Administrator awal untuk masuk ke dalam sistem:
- **Nama Pengguna (Username):** `admin`
- **Kata Sandi (Password):** `admin123`

> ⚠️ **Saran Keamanan:** Setelah berhasil masuk, segera perbarui kata sandi Anda melalui menu profil di pojok kanan atas (*Ubah Password*) demi menjaga keamanan data sekolah. Anda juga dapat mengatur ulang kata sandi admin kapan saja melalui perintah:
> ```cmd
> artisan.bat sae:admin-setup
> ```

---

## ✨ Fitur Utama

- **Integrasi Otomatis SAE:** Mengambil referensi data siswa, guru, dan jurusan dari aplikasi SAE utama tanpa perlu input ulang manual.
- **Hak Akses Sesuai Peran:** Pembagian wewenang yang rapi untuk Kepala Sekolah, Waka Hubin, Kepala Jurusan (Kajur), Guru Pembimbing, Pembimbing Industri, dan Siswa.
- **Verifikasi Kesiapan Siswa:** Membantu Kepala Jurusan memastikan siswa memenuhi kriteria sebelum diterjunkan ke industri.
- **Jejak Audit Aktivitas:** Setiap aktivitas penting tercatat otomatis untuk menjamin akuntabilitas administrasi sekolah.

---

## 🛠️ Perintah Berguna

Jika ingin melakukan pemeliharaan melalui terminal/command prompt:
| Perintah | Fungsi |
| :--- | :--- |
| `install.bat` | Menjalankan pemasangan dan perbaikan otomatis basis data |
| `artisan.bat sae:sync-master` | Memperbarui salinan data siswa, guru, dan jurusan dari SAE utama |
| `artisan.bat sae:admin-setup` | Membuat atau mengatur ulang kata sandi administrator |
| `artisan.bat serve` | Menjalankan server aplikasi secara mandiri |

---

&copy; {{ date('Y') }} **SAE PKL** &bull; Bagian dari Ekosistem Sistem Aplikasi Edukasi (SAE).

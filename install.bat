@echo off
setlocal enabledelayedexpansion
title Installer SAE PKL (Sistem Aplikasi Edukasi)

echo ============================================================
echo           SAE PKL — PANDUAN INSTALASI CEPAT
echo   Sistem Informasi Praktik Kerja Lapangan SMK (Ekosistem SAE)
echo ============================================================
echo.

:: 1. Deteksi binary PHP 8.3 (Laragon atau System PATH)
set "PHP_BIN=php"
if exist "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" (
    set "PHP_BIN=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
)

echo [*] Menggunakan runtime: %PHP_BIN%
"%PHP_BIN%" -v | findstr /i "PHP 8." >nul
if errorlevel 1 (
    echo [!] PERINGATAN: Memerlukan PHP versi 8.3 atau lebih baru.
)

:: 2. Setup konfigurasi .env
echo [*] Memeriksa file konfigurasi (.env)...
if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo [v] File .env berhasil disiapkan dari template.
) else (
    echo [v] File .env sudah tersedia.
)

:: 3. Generate Application Key jika belum ada
echo [*] Memeriksa Application Key...
findstr /i "APP_KEY=base64:" ".env" >nul
if errorlevel 1 (
    "%PHP_BIN%" artisan key:generate --force
    echo [v] Application Key berhasil dibuat.
) else (
    echo [v] Application Key sudah terpasang.
)

:: 4. Buat Database sae_pkl di MySQL jika belum ada
echo [*] Memeriksa basis data MySQL (sae_pkl)...
"%PHP_BIN%" -r "try { $p = new PDO('mysql:host=127.0.0.1;port=3306', 'root', ''); $p->exec('CREATE DATABASE IF NOT EXISTS sae_pkl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'); echo '[v] Database sae_pkl siap digunakan.' . PHP_EOL; } catch (Exception $e) { echo '[!] Catatan koneksi DB: ' . $e->getMessage() . PHP_EOL; }"

:: 5. Jalankan Migrasi Database
echo [*] Menyiapkan tabel-tabel sistem...
"%PHP_BIN%" artisan migrate --force

:: 6. Jalankan Seeder Awal (Pengaturan & Akun Awal)
echo [*] Memasukkan konfigurasi awal sistem...
"%PHP_BIN%" artisan db:seed --force

echo.
echo ============================================================
echo                  INSTALASI BERHASIL!
echo ============================================================
echo.
echo Aplikasi SAE PKL sudah siap dijalankan:
echo  1. Jika menggunakan Laragon: Buka http://sae-pkl.test
echo  2. Jika menjalankan manual:
echo     Ketik perintah: artisan.bat serve
echo     Lalu buka browser: http://localhost:8000
echo.
echo Akun masuk bawaan (Lingkungan Percobaan):
echo  - Username : admin
echo  - Password : (silakan atur via: artisan.bat sae:admin-setup)
echo.
echo Tekan tombol apa saja untuk keluar dari installer...
pause >nul

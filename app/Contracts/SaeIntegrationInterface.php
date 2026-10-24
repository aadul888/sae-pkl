<?php

namespace App\Contracts;

interface SaeIntegrationInterface
{
    /**
     * Mengambil informasi resmi profil sekolah.
     */
    public function getSekolah(): ?array;

    /**
     * Mengambil daftar jurusan / program keahlian aktif.
     */
    public function getJurusanList(): array;

    /**
     * Mengambil daftar GTK (Guru dan Tenaga Kependidikan) aktif.
     */
    public function getGtkList(): array;

    /**
     * Mengambil daftar peserta didik aktif berdasarkan jurusan.
     */
    public function getPesertaDidikByJurusan(?string $jurusanId = null): array;

    /**
     * Mengambil daftar penugasan tugas tambahan aktif (Waka Hubin, Kajur/Kaprog).
     */
    public function getActiveDuties(): array;

    /**
     * Memvalidasi kredensial pengguna terhadap database SAE Utama (delegasi autentikasi).
     */
    public function verifyUserCredential(string $username, string $password): ?array;
}

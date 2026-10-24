<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_siswa', function (Blueprint $table) {
            $table->string('peserta_didik_id', 50)->primary()->comment('UUID Dapodik dari peserta_didik.peserta_didik_id');
            $table->string('nisn', 20)->index();
            $table->string('nipd', 25)->nullable()->index();
            $table->string('nik', 25)->nullable()->index();
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('nama_rombel', 50)->nullable();
            $table->string('rombongan_belajar_id', 50)->nullable()->index();
            $table->string('jurusan_id', 50)->index();
            $table->string('no_hp', 25)->nullable();
            $table->enum('status_kesiapan', ['belum_verifikasi', 'siap', 'tidak_siap'])->default('belum_verifikasi')->index();
            $table->text('catatan_kesiapan')->nullable();
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_siswa');
    }
};

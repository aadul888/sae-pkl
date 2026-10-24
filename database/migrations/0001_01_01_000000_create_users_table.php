<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('nama', 150);
            $table->string('email', 150)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', [
                'admin',
                'kepsek',
                'waka_hubin',
                'kajur',
                'guru_pembimbing',
                'pembimbing_industri',
                'siswa'
            ])->default('siswa')->index();
            $table->string('ptk_id', 50)->nullable()->index()->comment('Tautan logis ke GTK SAE Utama');
            $table->string('peserta_didik_id', 50)->nullable()->index()->comment('Tautan logis ke Siswa SAE Utama');
            $table->unsignedBigInteger('mitra_id')->nullable()->index()->comment('Tautan ke Mitra Industri (khusus Pembimbing Industri)');
            $table->string('jurusan_id', 50)->nullable()->index()->comment('Tautan kode jurusan (khusus isolasi wewenang Kajur)');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

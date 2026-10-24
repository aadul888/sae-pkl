<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_sekolah', function (Blueprint $table) {
            $table->string('sekolah_id', 50)->primary()->comment('UUID Dapodik dari sekolah.sekolah_id');
            $table->string('npsn', 20)->nullable()->index();
            $table->string('nama', 150);
            $table->text('alamat')->nullable();
            $table->string('kepala_sekolah_nama', 150)->nullable();
            $table->string('kepala_sekolah_nip', 30)->nullable();
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_sekolah');
    }
};

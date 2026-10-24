<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_jurusan', function (Blueprint $table) {
            $table->string('jurusan_id', 50)->primary()->comment('Kode / ID Jurusan Dapodik');
            $table->string('nama_jurusan', 150);
            $table->string('bidang_keahlian', 100)->nullable();
            $table->string('program_keahlian', 100)->nullable();
            $table->string('kajur_ptk_id', 50)->nullable()->index()->comment('UUID PTK yang menjabat Kajur');
            $table->string('kajur_nama', 150)->nullable();
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_jurusan');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_guru', function (Blueprint $table) {
            $table->string('ptk_id', 50)->primary()->comment('UUID Dapodik dari gtk.ptk_id');
            $table->string('nuptk', 30)->nullable()->index();
            $table->string('nip', 30)->nullable()->index();
            $table->string('nama', 150);
            $table->string('jenis_ptk', 100)->nullable();
            $table->string('no_hp', 25)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_guru');
    }
};

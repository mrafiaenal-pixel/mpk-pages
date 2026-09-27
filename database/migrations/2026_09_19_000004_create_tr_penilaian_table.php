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
        Schema::create('tr_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota_sekbid')->constrained('tb_anggota_sekbid')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_users')->constrained('users', 'id_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_aspek_penilaian')->constrained('tb_aspek_penilaian')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_penilaian');
    }
};

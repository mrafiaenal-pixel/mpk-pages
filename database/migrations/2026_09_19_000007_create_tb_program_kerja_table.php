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
        Schema::create('tb_program_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_progja');
            $table->text('deskripsi_progja');
            $table->enum('status_progja', ['rencana', 'berjalan', 'terlaksana', 'belum_terlaksana', 'sedang_berjalan', 'selesai', 'batal'])->default('rencana');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_program_kerja');
    }
};

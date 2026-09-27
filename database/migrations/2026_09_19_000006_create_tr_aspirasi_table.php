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
        Schema::create('tr_aspirasi', function (Blueprint $table) {
            $table->id('id_aspirasi');
            $table->string('nama_pengirim')->nullable();
            $table->string('judul_aspirasi');
            $table->text('deskripsi_aspirasi');
            $table->enum('status_aspirasi', ['menunggu', 'proses', 'selesai', 'ditolak', 'pending'])->default('menunggu');
            $table->foreignId('id_users')->nullable()->constrained('users', 'id_users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('kode_kategori');
            $table->foreign('kode_kategori')->references('kode_kategori')->on('tb_kategori_aspirasi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_aspirasi');
    }
};

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
        Schema::create('tb_profil_kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengurus');
            $table->string('jabatan_pengurus');
            $table->string('komisi_pengurus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_profil_kepengurusan');
    }
};

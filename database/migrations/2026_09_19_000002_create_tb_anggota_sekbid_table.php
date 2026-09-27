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
        Schema::create('tb_anggota_sekbid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sekbid')->constrained('tb_sekbid')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_anggota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_anggota_sekbid');
    }
};

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
        Schema::create('tb_calon_pemilu', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_calon');
            $table->unsignedTinyInteger('nomor_urut')->unique();
            $table->string('slogan')->nullable();
            $table->text('visi_misi');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_calon_pemilu');
    }
};

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
        Schema::create('tb_profile_sekolah', function (Blueprint $table) {
            $table->id('id_profile');
            $table->string('nama_sekolah');
            $table->string('email_sekolah');
            $table->string('nama_mpk');
            $table->text('descripsi_profile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_profile_sekolah');
    }
};

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
        Schema::create('tb_suara_pemilu', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('id_users')->constrained('users', 'id_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_calon')->constrained('tb_calon_pemilu')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            // Satu akun hanya boleh memberikan satu suara.
            $table->unique('id_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_suara_pemilu');
    }
};

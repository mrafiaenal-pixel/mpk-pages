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
        Schema::create('daily_lives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->string('kategori')->default('umum');
            $table->timestamps();

            $table->index(['id_users', 'tanggal']);
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lives');
    }
};

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
        Schema::create('tb_uang_kas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_users')->unsigned();
            $table->string('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->date('tanggal');
            $table->timestamps();

            $table->index(['id_users', 'tanggal']);
            $table->index('tipe');

            $table->foreign('id_users')
                ->references('id_users')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_uang_kas');
    }
};

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
        Schema::create('tb_user_sekbid', function (Blueprint $table): void {
            $table->foreignId('id_users')->constrained('users', 'id_users')->cascadeOnDelete();
            $table->foreignId('id_sekbid')->constrained('tb_sekbid', 'id')->cascadeOnDelete();
            $table->primary(['id_users', 'id_sekbid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_sekbid');
    }
};

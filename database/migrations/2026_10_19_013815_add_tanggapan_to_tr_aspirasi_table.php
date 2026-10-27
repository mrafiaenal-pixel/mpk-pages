<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_aspirasi', function (Blueprint $table): void {
            $table->text('tanggapan_mpk')->nullable()->after('deskripsi_aspirasi');
            $table->text('catatan_internal')->nullable()->after('tanggapan_mpk');
            $table->foreignId('id_users_penangan')
                ->nullable()
                ->after('catatan_internal')
                ->constrained('users', 'id_users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tr_aspirasi', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('id_users_penangan');
            $table->dropColumn(['tanggapan_mpk', 'catatan_internal']);
        });
    }
};

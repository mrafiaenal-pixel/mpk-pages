<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_aspek_penilaian', function (Blueprint $table): void {
            $table->decimal('total_poin', 8, 2)->nullable()->after('inisiatif_bantuan');
            $table->decimal('nilai', 5, 2)->nullable()->after('total_poin');
        });
    }

    public function down(): void
    {
        Schema::table('tb_aspek_penilaian', function (Blueprint $table): void {
            $table->dropColumn(['total_poin', 'nilai']);
        });
    }
};

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
        Schema::table('tb_profile_sekolah', function (Blueprint $table): void {
            $table->unsignedInteger('maksimal_calon')->default(5)->after('komisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_profile_sekolah', function (Blueprint $table): void {
            $table->dropColumn('maksimal_calon');
        });
    }
};

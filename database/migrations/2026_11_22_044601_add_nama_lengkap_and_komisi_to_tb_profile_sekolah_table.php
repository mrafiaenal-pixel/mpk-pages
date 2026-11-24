<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<array{kode: string, nama: string, deskripsi: string}> */
    private const KOMISI_DEFAULT = [
        ['kode' => 'A', 'nama' => 'Komisi A', 'deskripsi' => 'Tata Kelola Hukum'],
        ['kode' => 'B', 'nama' => 'Komisi B', 'deskripsi' => 'Jembatan antara Siswa dan Sekolah'],
        ['kode' => 'C', 'nama' => 'Komisi C', 'deskripsi' => 'Pengawasan & Evaluasi'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_profile_sekolah', function (Blueprint $table): void {
            $table->string('nama_lengkap')->default('Majelis Perwakilan Kelas')->after('nama_mpk');
            $table->json('komisi')->nullable()->after('descripsi_profile');
        });

        DB::table('tb_profile_sekolah')
            ->where('nama_mpk', 'MPR SMK WIKRAMA BOGOR')
            ->update(['nama_mpk' => 'MPK']);

        DB::table('tb_profile_sekolah')->update([
            'komisi' => json_encode(self::KOMISI_DEFAULT),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_profile_sekolah', function (Blueprint $table): void {
            $table->dropColumn(['nama_lengkap', 'komisi']);
        });
    }
};

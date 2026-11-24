<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ROLES_AKTIF = ['admin', 'guru', 'ketua', 'komc', 'koma', 'komb', 'anggota', 'bendahara'];

    private const ROLES_LAMA = ['admin', 'guru', 'ketua', 'komc', 'koma', 'komb', 'anggota', 'bendahara', 'osis', 'siswa'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->whereIn('role', ['mpk', 'osis', 'siswa'])->update(['role' => 'anggota']);

        $this->ubahEnum(self::ROLES_AKTIF);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->ubahEnum(self::ROLES_LAMA);
    }

    /**
     * MySQL menyimpan daftar peran sebagai tipe ENUM, sementara SQLite tidak.
     *
     * @param  list<string>  $roles
     */
    private function ubahEnum(array $roles): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $daftarRole = collect($roles)->map(fn (string $role): string => "'{$role}'")->implode(', ');

        DB::statement("ALTER TABLE users MODIFY role ENUM({$daftarRole}) NOT NULL DEFAULT 'anggota'");
    }
};

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
        Schema::create('tb_aspek_penilaian', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel user/anggota (Opsional tapi sangat disarankan)
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Keaktifan GDS
            $table->enum('keaktifan_gds', [
                'hadir',
                'telat 1x',
                'telat 2x',
                'telat 3x',
                'izin/lainnya',
                'tidak hadir',
            ]);

            // Piket RO
            $table->enum('piket_ro', [
                'hadir',
                'tidak ada jadwal',
                'tanpa keterangan',
                'izin/sakit',
                'tidak bagiannya',
            ]);

            // Kekompakan Sekbid (Disesuaikan dengan opsi poin: 120, 50, 25, 0)
            $table->enum('kekompakan', [
                'ramai aktif',
                'sepi ada komunikasi',
                'ramai saat progja',
                'grup mati',
            ]);

            // Komunikasi & Rapat (Digabung atau dipisah agar mencakup poin komunikasi + rapat)
            // Contoh dipisah menjadi dua kolom agar penilaian lebih akurat:
            $table->enum('komunikasi', [
                'baik responsif',
                'lambat merespon',
                'sulit koordinasi',
                'tidak berkomunikasi',
            ]);

            $table->enum('kehadiran_rapat', [
                'tepat waktu',
                'telat',
                'tanpa keterangan',
                'izin alasan jelas',
                'sakit',
            ]);

            // Kegiatan Progja
            $table->enum('kegiatan_progja', [
                'sesuai job',
                'kurang maksimal',
                'tidak ikut alasan',
                'tidak ada progja',
            ]);

            $table->boolean('inisiatif_bantuan')->default(false); // +15 poin jika true

            // Catatan dan Waktu
            $table->text('catatan_terhadap_anggota')->nullable();
            $table->dateTime('tanggal_penilaian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aspek_penilaian');
    }
};

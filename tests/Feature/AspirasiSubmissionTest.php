<?php

namespace Tests\Feature;

use App\Models\KategoriAspirasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AspirasiSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_aspirasi(): void
    {
        KategoriAspirasi::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana',
        ]);

        $response = $this->post('/aspirasi', [
            'nama_pengirim' => 'Rafi Pratama',
            'judul_aspirasi' => 'Pembersihan AC Kelas',
            'kode_kategori' => 'SARPRAS',
            'deskripsi_aspirasi' => 'AC di ruang XI PPLG 3 kurang dingin.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tr_aspirasi', [
            'judul_aspirasi' => 'Pembersihan AC Kelas',
            'nama_pengirim' => 'Rafi Pratama',
            'status_aspirasi' => 'menunggu',
        ]);
    }

    public function test_user_can_submit_aspirasi_anonymously(): void
    {
        KategoriAspirasi::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana',
        ]);

        $response = $this->post('/aspirasi', [
            'is_anonim' => '1',
            'judul_aspirasi' => 'Usulan Tempat Sampah',
            'kode_kategori' => 'SARPRAS',
            'deskripsi_aspirasi' => 'Perlu tambahan tempat sampah di lorong.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tr_aspirasi', [
            'judul_aspirasi' => 'Usulan Tempat Sampah',
            'nama_pengirim' => 'Anonim',
        ]);
    }
}

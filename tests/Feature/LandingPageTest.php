<?php

namespace Tests\Feature;

use App\Models\KategoriAspirasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_menampilkan_kategori_aspirasi_dan_tidak_menampilkan_status_rekayasa(): void
    {
        KategoriAspirasi::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana Sekolah',
        ]);

        $this->get(route('home'))
            ->assertSee('Topik yang Bisa Disampaikan')
            ->assertSee('Sarana & Prasarana Sekolah')
            ->assertDontSee('Periode Evaluasi Aktif')
            ->assertDontSee('Kompilasi Komisi B');
    }
}

<?php

namespace Tests\Feature;

use App\Models\AspekPenilaian;
use App\Models\Aspiration;
use App\Models\AspirationCategory;
use App\Models\Assesment;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use App\Services\PenilaiSkor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PenilaianFilterExportTest extends TestCase
{
    use RefreshDatabase;

    private Division $divisiDelapan;

    private Division $divisiSembilan;

    private User $ketuaMpk;

    private User $rafi;

    public function test_mpk_with_assignment_only_sees_own_division(): void
    {
        $penilaianLuar = $this->buatPenilaian($this->divisiSembilan->id, 'Rizky Pratama');
        $penilaianDalam = $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->rafi)->get(route('admin.penilaian'));

        $response->assertOk();
        $response->assertSee('Zahra Kamila');
        $response->assertDontSee('Rizky Pratama');

        $this->assertSame(
            [$penilaianDalam->id],
            $penilaianDalam->newQuery()->visibleTo($this->rafi)->pluck('id')->all()
        );
        $this->assertTrue($this->rafi->isDivisionScoped());
    }

    public function test_unassigned_mpk_sees_all_divisions(): void
    {
        $this->buatPenilaian($this->divisiSembilan->id, 'Rizky Pratama');
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->ketuaMpk)->get(route('admin.penilaian'));

        $response->assertOk();
        $response->assertSee('Rizky Pratama');
        $response->assertSee('Zahra Kamila');
        $this->assertFalse($this->ketuaMpk->isDivisionScoped());
    }

    public function test_mpk_cannot_submit_penilaian_for_other_division(): void
    {
        $anggotaLuar = DivisionMember::create([
            'id_sekbid' => $this->divisiSembilan->id,
            'nama_anggota' => 'Rizky Pratama',
        ]);

        $response = $this->actingAs($this->rafi)->post(route('admin.penilaian.store'), $this->payload($anggotaLuar));

        $response->assertSessionHasErrors('id_anggota_sekbid');
        $this->assertDatabaseCount('tr_penilaian', 0);
    }

    public function test_scoped_mpk_cannot_filter_into_another_division(): void
    {
        $this->buatPenilaian($this->divisiSembilan->id, 'Rizky Pratama');

        $response = $this->actingAs($this->rafi)
            ->get(route('admin.penilaian', ['divisi' => $this->divisiSembilan->id]));

        $response->assertOk();
        $response->assertDontSee('Rizky Pratama');
    }

    public function test_chairman_can_filter_by_division(): void
    {
        $this->buatPenilaian($this->divisiSembilan->id, 'Rizky Pratama');
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->ketuaMpk)
            ->get(route('admin.penilaian', ['divisi' => $this->divisiDelapan->id]));

        $response->assertOk();
        $response->assertSee('Zahra Kamila');
        $response->assertDontSee('Rizky Pratama');
    }

    public function test_search_filter_matches_member_name(): void
    {
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->ketuaMpk)
            ->get(route('admin.penilaian', ['q' => 'Zahra']));

        $response->assertOk();
        $response->assertSee('Zahra Kamila');
    }

    public function test_date_range_filter_is_applied(): void
    {
        $lama = $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila', now()->subMonths(3));
        $baru = $this->buatPenilaian($this->divisiDelapan->id, 'Wahyu Hidayat', now());

        $response = $this->actingAs($this->ketuaMpk)->get(route('admin.penilaian', [
            'dari' => now()->subDays(7)->toDateString(),
            'sampai' => now()->addDay()->toDateString(),
        ]));

        $response->assertOk();

        // Hanya penilaian dalam rentang tanggal yang tersisa di tabel.
        $response->assertViewHas('penilaians', function ($paginated) use ($lama, $baru): bool {
            $ids = $paginated->pluck('id')->all();

            return ! in_array($lama->id, $ids, true) && in_array($baru->id, $ids, true);
        });
    }

    public function test_export_returns_csv_with_bom_and_respects_filter(): void
    {
        $this->buatPenilaian($this->divisiSembilan->id, 'Rizky Pratama');
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->ketuaMpk)
            ->get(route('admin.penilaian.export', ['divisi' => $this->divisiDelapan->id]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertDownload();

        $isi = $response->streamedContent();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $isi);
        $this->assertStringContainsString('Zahra Kamila', $isi);
        $this->assertStringNotContainsString('Rizky Pratama', $isi);
    }

    public function test_rekap_page_renders_charts_from_real_data(): void
    {
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->rafi)->get(route('admin.rekap'));

        $response->assertOk();
        $response->assertSee('Rekap Visual Penilaian OSIS');
        $response->assertSee('chartSekbid');
        $response->assertSee('chartAspek');
        $response->assertSee('chartTren');
    }

    public function test_rekap_export_returns_csv(): void
    {
        $this->buatPenilaian($this->divisiDelapan->id, 'Zahra Kamila');

        $response = $this->actingAs($this->rafi)->get(route('admin.rekap.export'));

        $response->assertOk();
        $this->assertStringContainsString('Sekbid 8', $response->streamedContent());
    }

    public function test_scoring_rubric_maps_best_options_to_full_marks(): void
    {
        $hasil = PenilaiSkor::hitung([
            'keaktifan_gds' => 'hadir',
            'piket_ro' => 'hadir',
            'kekompakan' => 'ramai aktif',
            'komunikasi' => 'baik responsif',
            'kehadiran_rapat' => 'tepat waktu',
            'kegiatan_progja' => 'sesuai job',
            'inisiatif_bantuan' => true,
        ]);

        $this->assertEqualsWithDelta(PenilaiSkor::poinMaksimum(), $hasil['total_poin'], 0.001);
        $this->assertSame(100.0, $hasil['nilai']);
        $this->assertSame('Sangat Baik', $hasil['predikat']);
    }

    public function test_scoring_penalises_poor_performance(): void
    {
        $hasil = PenilaiSkor::hitung([
            'keaktifan_gds' => 'tidak hadir',
            'piket_ro' => 'tanpa keterangan',
            'kekompakan' => 'grup mati',
            'komunikasi' => 'tidak berkomunikasi',
            'kehadiran_rapat' => 'tanpa keterangan',
            'kegiatan_progja' => 'tidak ada progja',
            'inisiatif_bantuan' => false,
        ]);

        // 0 + 40 + 0 + 0 + 20 + 0 = 60 poin dari total maksimum 635.
        $this->assertSame(60.0, $hasil['total_poin']);
        $this->assertLessThan(20, $hasil['nilai']);
        $this->assertSame('Tidak Memadai', $hasil['predikat']);
    }

    public function test_scored_penilaian_persists_numeric_score(): void
    {
        $anggota = DivisionMember::create([
            'id_sekbid' => $this->divisiDelapan->id,
            'nama_anggota' => 'Zahra Kamila',
        ]);

        $this->actingAs($this->rafi)->post(route('admin.penilaian.store'), $this->payload($anggota))
            ->assertRedirect(route('admin.penilaian'));

        $aspek = AspekPenilaian::latest('id')->first();

        $this->assertNotNull($aspek->nilai);
        $this->assertSame(100.0, (float) $aspek->nilai);
        $this->assertSame('Sangat Baik', $aspek->predikat());
    }

    public function test_penugasan_mpk_to_division(): void
    {
        $mpk = User::create([
            'username' => 'mpk_baru',
            'email' => 'baru@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $response = $this->actingAs($this->ketuaMpk)
            ->patch(route('admin.sekbid.penugasan', $this->divisiDelapan->id), ['id_users' => $mpk->id_users]);

        $response->assertRedirect();
        $this->assertSame($this->divisiDelapan->id, $mpk->fresh()->id_sekbid);
        $this->assertTrue($mpk->fresh()->isDivisionScoped());
    }

    public function test_penugasan_moves_mpk_away_from_previous_division(): void
    {
        $this->assertSame($this->divisiDelapan->id, $this->rafi->id_sekbid);

        $this->actingAs($this->ketuaMpk)
            ->patch(route('admin.sekbid.penugasan', $this->divisiSembilan->id), ['id_users' => $this->rafi->id_users]);

        $this->assertSame($this->divisiSembilan->id, $this->rafi->fresh()->id_sekbid);
    }

    public function test_sekbid_page_shows_assignment_controls(): void
    {
        // Ketua (tanpa assignment) melihat kontrol penugasan.
        $this->actingAs($this->ketuaMpk)->get(route('admin.sekbid'))
            ->assertOk()
            ->assertSee('MPK Penanggung Jawab')
            ->assertSee($this->divisiDelapan->nama_sekbid);

        // MPK terikat hanya melihat divisinya sendiri dan tanpa kontrol.
        $this->actingAs($this->rafi)->get(route('admin.sekbid'))
            ->assertOk()
            ->assertSee($this->divisiDelapan->nama_sekbid)
            ->assertDontSee($this->divisiSembilan->nama_sekbid);
    }

    public function test_scoped_mpk_cannot_assign_other_mpk(): void
    {
        $mpkLain = User::create([
            'username' => 'mpk_lain',
            'email' => 'lain@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        // Rafi mencoba menugaskan MPK lain; harus ditolak karena bukan tanpa assignment.
        $this->actingAs($this->rafi)
            ->from(route('admin.sekbid'))
            ->patch(route('admin.sekbid.penugasan', $this->divisiDelapan->id), ['id_users' => $mpkLain->id_users])
            ->assertRedirect(route('admin.sekbid'))
            ->assertSessionHas('error');

        $this->assertNull($mpkLain->fresh()->id_sekbid);
    }

    public function test_aspiration_can_be_filtered_and_responded_to(): void
    {
        $aspirasi = Aspiration::create([
            'nama_pengirim' => 'Siswa XI',
            'judul_aspirasi' => 'Proyektor Rusak',
            'deskripsi_aspirasi' => 'Proyektor lab berkedip.',
            'status_aspirasi' => 'menunggu',
            'kode_kategori' => 'SARPRAS',
        ]);

        $this->actingAs($this->ketuaMpk)
            ->patch(route('admin.aspirasi.update', $aspirasi->id_aspirasi), [
                'status_aspirasi' => 'proses',
                'tanggapan_mpk' => 'Sedang kami jadwalkan.',
                'catatan_internal' => 'Menunggu bagian sarpras.',
            ])->assertRedirect();

        $this->assertDatabaseHas('tr_aspirasi', [
            'id_aspirasi' => $aspirasi->id_aspirasi,
            'status_aspirasi' => 'proses',
            'tanggapan_mpk' => 'Sedang kami jadwalkan.',
        ]);

        $this->actingAs($this->ketuaMpk)->get(route('admin.aspirasi', ['q' => 'Proyektor']))
            ->assertOk()
            ->assertSee('Proyektor Rusak');
    }

    public function test_aspiration_export_returns_csv(): void
    {
        Aspiration::create([
            'nama_pengirim' => 'Anonim',
            'judul_aspirasi' => 'Sampah Pilah',
            'deskripsi_aspirasi' => 'Mohon tempat sampah daur ulang.',
            'status_aspirasi' => 'menunggu',
            'kode_kategori' => 'SARPRAS',
        ]);

        $response = $this->actingAs($this->ketuaMpk)->get(route('admin.aspirasi.export'));

        $response->assertOk();
        $this->assertStringContainsString('Sampah Pilah', $response->streamedContent());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisiDelapan = Division::create(['nama_sekbid' => 'Sekbid 8: Sastra & Lingkungan']);
        $this->divisiSembilan = Division::create(['nama_sekbid' => 'Sekbid 9: TIK']);

        AspirationCategory::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana Sekolah',
        ]);

        $this->ketuaMpk = User::create([
            'username' => 'ketua',
            'email' => 'ketua@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $this->rafi = User::create([
            'username' => 'rafi',
            'email' => 'rafi@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
            'id_sekbid' => $this->divisiDelapan->id,
        ]);
    }

    /**
     * Buat satu data penilaian beserta aspeknya.
     */
    private function buatPenilaian(int $divisiId, string $namaAnggota, ?\DateTimeInterface $tanggal = null): Assesment
    {
        $anggota = DivisionMember::create([
            'id_sekbid' => $divisiId,
            'nama_anggota' => $namaAnggota,
        ]);

        $aspek = AspekPenilaian::create([
            'keaktifan_gds' => 'hadir',
            'piket_ro' => 'hadir',
            'kekompakan' => 'ramai aktif',
            'komunikasi' => 'baik responsif',
            'kehadiran_rapat' => 'tepat waktu',
            'kegiatan_progja' => 'sesuai job',
            'inisiatif_bantuan' => false,
            'tanggal_penilaian' => $tanggal ?? now(),
        ]);

        $aspek->refreshSkor();

        return Assesment::create([
            'id_anggota_sekbid' => $anggota->id,
            'id_users' => $this->ketuaMpk->id_users,
            'id_aspek_penilaian' => $aspek->id,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(DivisionMember $anggota): array
    {
        return [
            'id_anggota_sekbid' => $anggota->id,
            'keaktifan_gds' => 'hadir',
            'piket_ro' => 'hadir',
            'kekompakan' => 'ramai aktif',
            'komunikasi' => 'baik responsif',
            'kehadiran_rapat' => 'tepat waktu',
            'kegiatan_progja' => 'sesuai job',
            'inisiatif_bantuan' => '1',
            'tanggal_penilaian' => now()->format('Y-m-d H:i:s'),
        ];
    }
}

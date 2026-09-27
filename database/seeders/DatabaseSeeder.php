<?php

namespace Database\Seeders;

use App\Models\DivisionMember;
use App\Models\AssesmentAspect;
use App\Models\Aspiration;
use App\Models\KategoriAspirasi;
use App\Models\Assesment;
use App\Models\SchoolProfile;
use App\Models\ProfilKepengurusan;
use App\Models\WorkProgram;
use App\Models\Division;
use App\Models\User;
use App\Models\VissionMission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Users
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'muhamadrafiaenalyakin@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'mpk',
            ]
        );

        $ketuaMpk = User::firstOrCreate(
            ['username' => 'ketua_mpk'],
            [
                'email' => 'ketua.mpk@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'mpk',
            ]
        );

        // 2. Kategori Aspirasi
        $kategoris = [
            ['kode_kategori' => 'SARPRAS', 'nama_kategori' => 'Sarana & Prasarana Sekolah'],
            ['kode_kategori' => 'KEGIATAN', 'nama_kategori' => 'Kegiatan & Acara Siswa'],
            ['kode_kategori' => 'AKADEMIK', 'nama_kategori' => 'Kurikulum & Pembelajaran'],
            ['kode_kategori' => 'KEDISIPLINAN', 'nama_kategori' => 'Tata Tertib & Kedisiplinan'],
            ['kode_kategori' => 'EKSTRA', 'nama_kategori' => 'Ekstrakurikuler'],
            ['kode_kategori' => 'LAINNYA', 'nama_kategori' => 'Aspirasi Umum / Lainnya'],
        ];

        foreach ($kategoris as $k) {
            KategoriAspirasi::updateOrCreate(['kode_kategori' => $k['kode_kategori']], $k);
        }

        // 3. 10 Seksi Bidang (Sekbid) & Anggota
        $daftarSekbid = [
            ['nama' => 'Sekbid 1: Ketaqwaan terhadap Tuhan YME', 'anggota' => ['Ahmad Zaki', 'Nurul Hikmah', 'Fauzan Adit']],
            ['nama' => 'Sekbid 2: Budi Pekerti Luhur & Pembinaan Karakter', 'anggota' => ['Budi Santoso', 'Rina Anggraini', 'Dimas Pratama']],
            ['nama' => 'Sekbid 3: Kepribadian Unggul & Wawasan Kebangsaan', 'anggota' => ['Bagas Ramadhan', 'Annisa Fitri', 'Reza Kurnia']],
            ['nama' => 'Sekbid 4: Prestasi Akademik, Seni & Olahraga', 'anggota' => ['Dinda Rahma', 'Kevin Sanjaya', 'Putri Ayu']],
            ['nama' => 'Sekbid 5: Demokrasi, HAM & Pendidikan Politik', 'anggota' => ['Farhan Malik', 'Tiara Andini', 'Genta Buana']],
            ['nama' => 'Sekbid 6: Kreativitas, Keterampilan & Kewirausahaan', 'anggota' => ['Hendra Wijaya', 'Siti Maryam', 'Ilham Syah']],
            ['nama' => 'Sekbid 7: Kualitas Jasmani, Kesehatan & Gizi', 'anggota' => ['Aldi Taher', 'Maya Safitri', 'Farel Prayoga']],
            ['nama' => 'Sekbid 8: Sastra, Budaya & Pelestarian Lingkungan', 'anggota' => ['Zahra Kamila', 'Wahyu Hidayat', 'Nadia Putri']],
            ['nama' => 'Sekbid 9: Teknologi Informasi & Komunikasi (TIK)', 'anggota' => ['Rizky Pratama', 'Vina Amalia', 'Satria Dewa']],
            ['nama' => 'Sekbid 10: Komunikasi dalam Bahasa Asing & Literasi', 'anggota' => ['Grace Kelly', 'Jonathan Christi', 'Chelsea Olivia']],
        ];

        foreach ($daftarSekbid as $data) {
            $sekbid = Division::firstOrCreate(['nama_sekbid' => $data['nama']]);
            foreach ($data['anggota'] as $namaAnggota) {
                DivisionMember::firstOrCreate([
                    'id_sekbid' => $sekbid->id,
                    'nama_anggota' => $namaAnggota,
                ]);
            }
        }

        // 4. Program Kerja
        $progjas = [
            [
                'nama_progja' => 'Peringatan Hari Besar Islam (PHBI) & Doa Bersama',
                'deskripsi_progja' => 'Kegiatan keagamaan rutin untuk memperkuat nilai spiritual dan toleransi seluruh siswa.',
                'status_progja' => 'terlaksana',
            ],
            [
                'nama_progja' => 'Latihan Dasar Kepemimpinan Siswa (LDKS)',
                'deskripsi_progja' => 'Pengkaderan dan pembinaan kepemimpinan untuk seluruh pengurus organisasi siswa.',
                'status_progja' => 'sedang_berjalan',
            ],
            [
                'nama_progja' => 'Pekan Olahraga dan Seni (Porseni) Antarkelas',
                'deskripsi_progja' => 'Ajang kompetisi olahraga dan pentas kreasi seni untuk mempererat kekompakan antarkelas.',
                'status_progja' => 'terlaksana',
            ],
            [
                'nama_progja' => 'Bakti Sosial & Gerakan Sekolah Hijau (Green School)',
                'deskripsi_progja' => 'Penyaluran bantuan kepada warga sekitar dan aksi penanaman pohon di lingkungan sekolah.',
                'status_progja' => 'terlaksana',
            ],
            [
                'nama_progja' => 'Workshop Literasi Digital & Desain Grafis',
                'deskripsi_progja' => 'Pelatihan pemanfaatan teknologi digital dan konten edukatif bagi pengurus sekbid dan siswa.',
                'status_progja' => 'terlaksana',
            ],
            [
                'nama_progja' => 'English Day & Gelar Wicara Dwi Bahasa',
                'deskripsi_progja' => 'Pembiasaan komunikasi bahasa Inggris rutin setiap hari Rabu untuk meningkatkan kompetensi global.',
                'status_progja' => 'sedang_berjalan',
            ],
        ];

        foreach ($progjas as $pj) {
            WorkProgram::firstOrCreate(['nama_progja' => $pj['nama_progja']], $pj);
        }

        // 5. Aspek Penilaian & Penilaian Sample
        $anggotaPertama = Division::first();
        if ($anggotaPertama) {
            $aspek = AssesmentAspect::firstOrCreate([
                'keaktifan_gds' => 'hadir',
                'piket_ro' => 'hadir',
                'kekompakan' => 'ramai aktif',
                'komunikasi' => 'baik responsif',
                'kehadiran_rapat' => 'tepat waktu',
                'kegiatan_progja' => 'sesuai job',
                'catatan_terhadap_anggota' => 'Menunjukkan dedikasi tinggi dalam pelaksanaan piket dan koordinasi kegiatan.',
                'tanggal_penilaian' => now(),
            ]);

            Assesment::firstOrCreate([
                'id_anggota_sekbid' => $anggotaPertama->id,
                'id_users' => $ketuaMpk->id_users,
                'id_aspek_penilaian' => $aspek->id,
            ]);
        }

        // 6. Sample Aspirasi
        $sampleAspirasi = [
            [
                'nama_pengirim' => 'Raditya (XI PPLG 1)',
                'judul_aspirasi' => 'Perbaikan Proyektor dan Fasilitas Lab Komputer',
                'deskripsi_aspirasi' => 'Mohon bantuan untuk pengecekan proyektor di Lab RPL 2 yang sering berkedip saat jam pelajaran produktif.',
                'status_aspirasi' => 'selesai',
                'kode_kategori' => 'SARPRAS',
            ],
            [
                'nama_pengirim' => 'Anonim',
                'judul_aspirasi' => 'Penambahan Tempat Sampah Pilah di Koridor Belakang',
                'deskripsi_aspirasi' => 'Di koridor kelas XI dekat kantin sering menumpuk sampah plastik karena kurangnya tempat sampah daur ulang.',
                'status_aspirasi' => 'proses',
                'kode_kategori' => 'SARPRAS',
            ],
            [
                'nama_pengirim' => 'Siti Nurhaliza (X DKV 3)',
                'judul_aspirasi' => 'Permohonan Pengadaan Lomba E-Sport di Classmeeting',
                'deskripsi_aspirasi' => 'Diharapkan OSIS Sekbid 4 dapat memasukkan cabang Mobile Legends / Valorant pada agenda classmeeting semester ini.',
                'status_aspirasi' => 'menunggu',
                'kode_kategori' => 'KEGIATAN',
            ],
        ];

        foreach ($sampleAspirasi as $sa) {
            Aspiration::firstOrCreate(['judul_aspirasi' => $sa['judul_aspirasi']], $sa);
        }

        // 7. Visi Misi
        VissionMission::firstOrCreate([
            'visi' => 'Mewujudkan Majelis Perwakilan Kelas sebagai lembaga legislatif siswa yang aspiratif, transparan, proaktif, dan berintegritas demi terciptanya sinergi positif seluruh warga sekolah.',
            'misi' => "1. Menampung, mengawal, dan memperjuangkan aspirasi seluruh siswa secara transparan dan akuntabel.\n2. Melaksanakan pengawasan yang konstruktif dan terukur terhadap seluruh program kerja Seksi Bidang OSIS.\n3. Meningkatkan sinergi kolaboratif antara siswa, pengurus OSIS, pembina, dan pihak sekolah.\n4. Menegakkan kedisiplinan serta budaya musyawarah mufakat dalam setiap pengambilan keputusan.",
        ]);

        // 8. Profil Kepengurusan MPK
        $pengurus = [
            ['nama_pengurus' => 'M. Rafi Pratama', 'jabatan_pengurus' => 'Ketua Umum MPK', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Aulia Syahrani', 'jabatan_pengurus' => 'Wakil Ketua MPK', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Fathir Nugraha', 'jabatan_pengurus' => 'Sekretaris Umum', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Siti Maulida', 'jabatan_pengurus' => 'Bendahara Umum', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Dava Satria', 'jabatan_pengurus' => 'Ketua Komisi A', 'komisi_pengurus' => 'Komisi A (Aspirasi & Advokasi)'],
            ['nama_pengurus' => 'Nadila Rizka', 'jabatan_pengurus' => 'Ketua Komisi B', 'komisi_pengurus' => 'Komisi B (Pengawasan Sekbid)'],
            ['nama_pengurus' => 'Rangga Aliansyah', 'jabatan_pengurus' => 'Ketua Komisi C', 'komisi_pengurus' => 'Komisi C (Hukum & Disiplin)'],
            ['nama_pengurus' => 'Zahwa Tiara', 'jabatan_pengurus' => 'Ketua Komisi D', 'komisi_pengurus' => 'Komisi D (Humas & Publikasi)'],
        ];

        foreach ($pengurus as $p) {
            ProfilKepengurusan::firstOrCreate(['nama_pengurus' => $p['nama_pengurus']], $p);
        }

        // 9. Profil Sekolah
        SchoolProfile::firstOrCreate([
            'nama_sekolah' => 'SMK Wikrama Bogor',
            'email_sekolah' => 'prohumasi@smkwikrama.sch.id',
            'nama_mpk' => 'MPR SMK WIKRAMA BOGOR',
            'descripsi_profile' => 'Wadah pembinaan kepemimpinan dan keterwakilan siswa yang berkomitmen menjaga aspirasi dan integritas.',
        ]);
    }
}

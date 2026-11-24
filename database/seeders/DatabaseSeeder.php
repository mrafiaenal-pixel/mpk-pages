<?php

namespace Database\Seeders;

use App\Models\Aspiration;
use App\Models\Assesment;
use App\Models\AssesmentAspect;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\KategoriAspirasi;
use App\Models\ProfilKepengurusan;
use App\Models\SchoolProfile;
use App\Models\User;
use App\Models\VissionMission;
use App\Models\WorkProgram;
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
                'role' => 'admin',
            ]
        );

        $ketuaMpk = User::firstOrCreate(
            ['username' => 'rafi'],
            [
                'email' => 'rafi@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
            ]
        );

        // Add new roles: ketua, komc, anggota, bendahara, guru
        User::firstOrCreate(
            ['username' => 'ketua'],
            [
                'email' => 'ketua@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'ketua',
            ]
        );

        User::firstOrCreate(
            ['username' => 'komc'],
            [
                'email' => 'komc@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
            ]
        );

        User::firstOrCreate(
            ['username' => 'anggota'],
            [
                'email' => 'anggota@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'anggota',
            ]
        );

        User::firstOrCreate(
            ['username' => 'bendahara'],
            [
                'email' => 'bendahara@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
            ]
        );

        User::firstOrCreate(
            ['username' => 'guru'],
            [
                'email' => 'guru@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // Role Kom C (akses seluruh sekbid, sama seperti admin)
        User::firstOrCreate(
            ['username' => 'komc'],
            [
                'email' => 'komc@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
            ]
        );

        // Role Ketua (akses sama seperti kom c)
        User::firstOrCreate(
            ['username' => 'ketua'],
            [
                'email' => 'ketua@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'ketua',
            ]
        );

        // Role Kom C (seluruh sekbid)
        User::firstOrCreate(
            ['username' => 'rafi'],
            [
                'email' => 'rafi@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
                'id_sekbid' => null,
            ]
        );

        // Role Samuel (seluruh sekbid)
        User::firstOrCreate(
            ['username' => 'samuel'],
            [
                'email' => 'samuel@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
                'id_sekbid' => null,
            ]
        );

        // Role Kiran (seluruh sekbid)
        User::firstOrCreate(
            ['username' => 'kiran'],
            [
                'email' => 'kiran@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komc',
                'id_sekbid' => null,
            ]
        );

        // Role Kom B (input penilaian + baca aspirasi)
        User::firstOrCreate(
            ['username' => 'komb'],
            [
                'email' => 'komb@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komb',
                'id_sekbid' => null,
            ]
        );

        // Role Iksan (Kom B)
        User::firstOrCreate(
            ['username' => 'iksan'],
            [
                'email' => 'iksan@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komb',
            ]
        );

        // Role Denis (Kom B)
        User::firstOrCreate(
            ['username' => 'denis'],
            [
                'email' => 'denis@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komb',
            ]
        );

        // Role Redina: Komisi B sekaligus bendahara (sekbid 6 dan 1)
        User::firstOrCreate(
            ['username' => 'redina'],
            [
                'email' => 'redina@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'komb',
                'role_tambahan' => 'bendahara',
            ]
        );

        // Role Ezri (sekbid 5 dan 1, juga sebagai ketua)
        User::firstOrCreate(
            ['username' => 'ezri'],
            [
                'email' => 'ezri@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'ketua',
            ]
        );

        // Role Kom A (input penilaian + tambah aturan organisasi)
        User::firstOrCreate(
            ['username' => 'koma'],
            [
                'email' => 'koma@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'koma',
            ]
        );

        // Role Sena (Kom A)
        User::firstOrCreate(
            ['username' => 'sena'],
            [
                'email' => 'sena@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'koma',
            ]
        );

        // Role Ezra (Kom A)
        User::firstOrCreate(
            ['username' => 'ezra'],
            [
                'email' => 'ezra@smkwikrama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'koma',
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

        // 3. 10 Seksi Bidang (Sekbid) & Anggota (data baru)
        $daftarSekbid = [
            ['nama' => 'Sekbid 1: Ketaqwaan terhadap Tuhan YME', 'anggota' => ['Annisa Lestari', 'Princess Felice Chandra', 'Arya Bima', 'Purnomo Yoga', 'Khalista Naraya Suci']],
            ['nama' => 'Sekbid 2: Budi Pekerti Luhur & Pembinaan Karakter', 'anggota' => ['Halwatussani Khoerunissa', 'Balqish Adara', 'Chinesya Anggia', 'Ridwan Faiz']],
            ['nama' => 'Sekbid 3: Kepribadian Unggul & Wawasan Kebangsaan', 'anggota' => ['Khairi Nizar Setiawan', 'Faiz Alin Nuha', 'Carvidya Nerlita', 'Aurelia Kirana Larasati']],
            ['nama' => 'Sekbid 4: Prestasi Akademik, Seni & Olahraga', 'anggota' => ['Naufal Abdilah', 'Syifa Dwi Anggraini', 'Inara Ayudyaputri Aqilla', 'Shafa Azzahra Nurprayogo']],
            ['nama' => 'Sekbid 5: Demokrasi, HAM & Pendidikan Politik', 'anggota' => ['Lenka Soulnya Putri', 'Risma Oktaviana Dewi', 'Muhammad Gerrard Habibie', 'Muhamad Virgiawan']],
            ['nama' => 'Sekbid 6: Kreativitas, Keterampilan & Kewirausahaan', 'anggota' => ['Rafka Athaya Khiry', 'Qirania Alika Cherysta Putri', 'Zerlina Pavla De Raynov', 'Glenn Felix Dharmawan']],
            ['nama' => 'Sekbid 7: Kualitas Jasmani, Kesehatan & Gizi', 'anggota' => ['Widi Rizki Pratama Kahar', 'Adinda Khairunnisa Aprilliya', 'Dyandra Kinanti Hapsari', 'Cantika Shifa Dwi Murti']],
            ['nama' => 'Sekbid 8: Sastra, Budaya & Pelestarian Lingkungan', 'anggota' => ['Faradilla Chairanty R', 'Sassy Kirana Sarjono', 'Shofia Kamilah Wahyudi', 'Nazwa Ramadanisa']],
            ['nama' => 'Sekbid 9: Teknologi Informasi & Komunikasi (TIK)', 'anggota' => ['Adinda Felovya', 'Oriza Aurelia Orlin', 'Muhammad Safa Naufal Fashih', 'Siti Ajeng Marufah', 'Nada Zalfa Hersya Zahirah', 'Najla Riana Azka', 'Maulidha Louise Almira', 'Anisa Dea Alfiani']],
            ['nama' => 'Sekbid 10: Komunikasi dalam Bahasa Asing & Literasi', 'anggota' => ['Ariel Ford Hastipardji', 'Keysha Aprilia Mulya', 'Alisha Az-Zahra Suardi']],
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

        // 3b. Samakan peran dan penugasan sekbid setiap akun uji.
        $peranPerUsername = [
            'admin' => 'admin',
            'ketua' => 'ketua',
            'komc' => 'komc',
            'rafi' => 'komc',
            'samuel' => 'komc',
            'kiran' => 'komc',
            'komb' => 'komb',
            'iksan' => 'komb',
            'denis' => 'komb',
            'koma' => 'koma',
            'sena' => 'koma',
            'ezra' => 'koma',
            'redina' => 'komb',
            'bendahara' => 'bendahara',
            'ezri' => 'ketua',
            'anggota' => 'anggota',
            'guru' => 'guru',
        ];

        $peranTambahanPerUsername = [
            'redina' => 'bendahara',
        ];

        foreach ($peranPerUsername as $username => $role) {
            User::where('username', $username)->update([
                'role' => $role,
                'role_tambahan' => $peranTambahanPerUsername[$username] ?? null,
                'password' => Hash::make('password'),
            ]);
        }

        $idSekbid = fn (int $nomor): ?int => Division::where('nama_sekbid', 'like', "Sekbid {$nomor}:%")->value('id');

        // Penilai: sekbid 1 untuk semua, 2 ezra, 3 iksan, 4 sena, 5 ezri,
        // 6 redina, 7-9 tanpa penugasan khusus, 10 denis.
        $penugasanSekbid = [
            'iksan' => [3, 1],
            'denis' => [10, 1],
            'redina' => [6, 1],
            'ezri' => [5, 1],
            'sena' => [4, 1],
            'ezra' => [2, 1],
        ];

        $tanpaBatas = ['admin', 'ketua', 'komc', 'rafi', 'samuel', 'kiran', 'guru', 'komb', 'koma', 'anggota', 'bendahara'];

        foreach ($tanpaBatas as $username) {
            $user = User::where('username', $username)->first();

            if ($user !== null) {
                $user->sekbids()->detach();
                $user->forceFill(['id_sekbid' => null])->save();
            }
        }

        foreach ($penugasanSekbid as $username => $nomorSekbid) {
            $user = User::where('username', $username)->first();

            if ($user === null) {
                continue;
            }

            $ids = array_values(array_filter(array_map($idSekbid, $nomorSekbid)));

            $user->forceFill(['id_sekbid' => null])->save();
            $user->sekbids()->sync($ids);
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
        $anggotaPertama = DivisionMember::first();
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

            $aspek->refreshSkor();
        }

        // 5b. Backfill skor untuk data penilaian lama yang belum punya nilai numerik.
        AssesmentAspect::whereNull('nilai')->each(
            fn (AssesmentAspect $aspekLama) => $aspekLama->refreshSkor()
        );

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
        $visiMisi = VissionMission::firstOrNew([]);
        $visiMisi->fill([
            'visi' => 'Mewujudkan organisasi siswa yang aspiratif, transparan, dan berintegritas demi kemajuan sekolah.',
            'misi' => "Menampung, mengawal, dan memperjuangkan seluruh aspirasi siswa secara transparan dan solutif.\nMelaksanakan pengawasan objektif terhadap 10 Seksi Bidang OSIS secara terstruktur dan terukur.\nMenjadi jembatan komunikasi yang harmonis antara siswa, pembina, dan jajaran manajemen sekolah.\nMenegakkan tata tertib dan memupuk budaya kepemimpinan berakhlak mulia.",
        ]);
        $visiMisi->save();

        // 8. Profil Kepengurusan (tiga komisi: A, B, C)
        $pengurus = [
            ['nama_pengurus' => 'M. Rafi Pratama', 'jabatan_pengurus' => 'Ketua Umum', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Aulia Syahrani', 'jabatan_pengurus' => 'Wakil Ketua', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Fathir Nugraha', 'jabatan_pengurus' => 'Sekretaris Umum', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Siti Maulida', 'jabatan_pengurus' => 'Bendahara Umum', 'komisi_pengurus' => 'Pimpinan Inti'],
            ['nama_pengurus' => 'Dava Satria', 'jabatan_pengurus' => 'Ketua Komisi A', 'komisi_pengurus' => 'Komisi A'],
            ['nama_pengurus' => 'Nadila Rizka', 'jabatan_pengurus' => 'Ketua Komisi B', 'komisi_pengurus' => 'Komisi B'],
            ['nama_pengurus' => 'Rangga Aliansyah', 'jabatan_pengurus' => 'Ketua Komisi C', 'komisi_pengurus' => 'Komisi C'],
        ];

        $keptIds = [];
        foreach ($pengurus as $p) {
            $keptIds[] = ProfilKepengurusan::updateOrCreate(
                ['nama_pengurus' => $p['nama_pengurus']],
                $p
            )->getKey();
        }
        ProfilKepengurusan::whereNotIn('id', $keptIds)->delete();

        // 9. Profil Sekolah / identitas organisasi
        $profil = SchoolProfile::firstOrNew([]);
        $profil->fill([
            'nama_sekolah' => 'SMK Wikrama Bogor',
            'email_sekolah' => 'prohumasi@smkwikrama.sch.id',
            'nama_mpk' => 'MPK',
            'nama_lengkap' => 'Majelis Perwakilan Kelas',
            'descripsi_profile' => 'Wadah aspirasi siswa dan pengawasan kinerja seksi bidang yang berkomitmen menjaga transparansi dan integritas.',
            'komisi' => [
                ['kode' => 'A', 'nama' => 'Komisi A', 'deskripsi' => 'Tata Kelola Hukum'],
                ['kode' => 'B', 'nama' => 'Komisi B', 'deskripsi' => 'Jembatan antara Siswa dan Sekolah'],
                ['kode' => 'C', 'nama' => 'Komisi C', 'deskripsi' => 'Pengawasan & Evaluasi'],
            ],
        ]);
        $profil->save();
    }
}

@extends('layout.app')

@section('title', $site->singkatan.' - Portal Resmi '.$site->namaLengkap)

@section('bodyClass', 'landing-page')

@section('content')

    <div id="mk-progress" aria-hidden="true"></div>

    <!-- ==================== HERO ==================== -->
    <section id="beranda" class="mk-section mk-hero">
        <div class="mk-grid-bg" aria-hidden="true"></div>
        <div class="mk-orb mk-orb--red" aria-hidden="true"></div>
        <div class="mk-orb mk-orb--blue" aria-hidden="true"></div>

        <div class="mk-container relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-6 items-center">
                <div class="lg:col-span-7">
                    <div class="mk-kicker mk-reveal">
                        <span class="mk-kicker__dot"></span>
                        Portal Aspirasi & Pemantauan {{ $site->singkatan }}
                    </div>

                    <h1 class="mk-title mk-reveal" data-delay="1">
                        <span class="mk-title__line">Suara Siswa,</span>
                        <span class="mk-title__line"><span class="mk-outline">Arah Perubahan</span></span>
                        <span class="mk-title__line">Sekolah<span class="mk-dot">.</span></span>
                    </h1>

                    <p class="mk-lead mk-reveal" data-delay="2">
                        Wadah resmi penampungan aspirasi seluruh siswa, transparansi pengawasan kinerja
                        10 Seksi Bidang OSIS, dan evaluasi program kerja secara terstruktur demi kemajuan sekolah.
                    </p>

                    <div class="mk-actions mk-reveal" data-delay="3">
                        <a href="#aspirasi" class="mk-btn mk-btn--primary">
                            <span>Kirim Aspirasi Sekarang</span>
                            <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        </a>
                        <a href="#progja" class="mk-btn mk-btn--ghost">
                            <span>Lihat Program Kerja</span>
                            <i class="fa-solid fa-list-check text-[11px]"></i>
                        </a>
                    </div>

                    <div class="mk-stats mk-reveal" data-delay="4">
                        <div class="mk-stat">
                            <span class="mk-stat__num" data-count="10">0</span>
                            <span class="mk-stat__label">Seksi Bidang OSIS</span>
                        </div>
                        <div class="mk-stat">
                            <span class="mk-stat__num" data-count="100" data-suffix="+">0</span>
                            <span class="mk-stat__label">Aspirasi Terkelola</span>
                        </div>
                        <div class="mk-stat">
                            <span class="mk-stat__num" data-count="95" data-suffix="%">0</span>
                            <span class="mk-stat__label">Disiplin & Terlaksana</span>
                        </div>
                    </div>
                </div>

                <!-- Floating status panel -->
                <div class="lg:col-span-5 relative mk-reveal" data-delay="3">
                    <div class="mk-console mk-float">
                        <div class="p-5 sm:p-6 space-y-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#85827e]">Kanal
                                        Aspirasi</p>
                                    <p class="text-lg font-black text-[#f6f4f2] mt-1">Topik yang Bisa Disampaikan</p>
                                </div>
                                <span class="mk-pill"><span class="mk-pill__dot"></span>
                                    {{ str_pad((string) $kategoris->count(), 2, '0', STR_PAD_LEFT) }} Kategori</span>
                            </div>

                            <div class="space-y-3">
                                @forelse($kategoris->take(4) as $kategori)
                                    <div class="mk-console__row">
                                        <span
                                            class="mk-console__index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="min-w-0 flex-1">
                                            <span
                                                class="block truncate text-[12px] font-semibold text-[#d6d4d1]">{{ $kategori->nama_kategori }}</span>
                                            <span
                                                class="block truncate text-[10px] text-[#85827e]">{{ $kategori->kode_kategori }}</span>
                                        </span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-[#85827e]"></i>
                                    </div>
                                @empty
                                    <div class="mk-console__row">
                                        <span class="mk-console__index">--</span>
                                        <span class="min-w-0 flex-1 text-[12px] text-[#85827e]">Kategori aspirasi belum
                                            tersedia.</span>
                                    </div>
                                @endforelse
                            </div>

                            <div
                                class="pt-4 border-t border-white/8 flex items-center justify-between gap-3 text-[10px] font-bold text-[#85827e]">
                                <span class="flex items-center gap-1.5"><i
                                        class="fa-solid fa-comment-dots text-[#5b8cff]"></i> Kanal resmi aspirasi
                                    {{ $site->singkatan }}</span>
                                <a href="#aspirasi" class="mk-link">Kirim Aspirasi <i
                                        class="fa-solid fa-chevron-right text-[10px]"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="mk-badge mk-badge--one" aria-hidden="true">ASPIRASI</div>
                    <div class="mk-badge mk-badge--two" aria-hidden="true">TRANSPARANSI</div>
                </div>
            </div>

            <!-- Theme toggle -->
            <button type="button" id="mk-theme-toggle" class="mk-theme-toggle" aria-label="Ganti tema tampilan"
                aria-pressed="false">
                <i class="fa-solid fa-sun mk-theme-icon"></i>
                <span class="mk-theme-label">Light mode</span>
            </button>
        </div>
    </section>

    <!-- ==================== MARQUEE ==================== -->
    <div class="mk-marquee" aria-hidden="true">
        <div class="mk-marquee__track">
            @for ($i = 0; $i < 2; $i++)
                <span>ASPIRASI <b>✳</b> PENGAWASAN <b>✳</b> TRANSPARANSI <b>✳</b> PERUBAHAN <b>✳</b> </span>
            @endfor
        </div>
    </div>

    <!-- ==================== SEKBID & PROGJA ==================== -->
    <section id="sekbid" class="mk-section mk-section--soft">
        <div class="mk-container">
            <span id="progja" class="block"></span>

            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="mk-eyebrow justify-center mk-reveal"><span>01</span> / Seksi Bidang OSIS</div>
                <h2 class="mk-heading mk-reveal" data-delay="1">
                    Mengenal Seksi Bidang<br><span class="mk-outline">& Program Kerja</span>
                </h2>
                <p class="mk-text mx-auto mt-4 mk-reveal" data-delay="2">
                    Pengawasan, koordinasi, dan pembinaan berkala terhadap 10 Seksi Bidang OSIS oleh Komisi
                    Pengawasan {{ $site->singkatan }} demi pencapaian program kerja yang optimal.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                @foreach ([['01', 'fa-hands-praying', 'Sekbid 1', 'Ketaqwaan terhadap Tuhan YME', 'Membina toleransi dan pelaksanaan ibadah bersama, perayaan hari besar keagamaan, serta kajian rohani.'], ['02', 'fa-heart-circle-check', 'Sekbid 2', 'Budi Pekerti Luhur & Karakter', 'Pengawalan Gerakan Disiplin Sekolah (GDS), pembiasaan 5S (Senyum, Salam, Sapa, Sopan, Santun).'], ['03', 'fa-flag', 'Sekbid 3', 'Wawasan Kebangsaan & Bela Negara', 'Pelaksanaan upacara bendera, pembinaan kepemimpinan (LDKS), dan penanaman jiwa patriotisme.'], ['04', 'fa-trophy', 'Sekbid 4', 'Prestasi Akademik, Seni & Olahraga', 'Fasilitasi kompetisi akademik, pentas seni sekolah, pekan olahraga, dan ajang kreativitas siswa.']] as $sekbidCard)
                    <article class="mk-card mk-reveal" data-delay="{{ $loop->iteration % 4 }}">
                        <div class="mk-card__top">
                            <span class="mk-index">{{ $sekbidCard[0] }}</span>
                            <i class="fa-solid {{ $sekbidCard[1] }}"></i>
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#5b8cff]">{{ $sekbidCard[2] }}</span>
                        <h3 class="mk-card__title mt-1 mb-2">{{ $sekbidCard[3] }}</h3>
                        <p class="mk-card__text">{{ $sekbidCard[4] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                <article class="mk-banner mk-banner--red mk-reveal">
                    <div class="mk-banner__icon"><i class="fa-solid fa-clipboard-check"></i></div>
                    <h3 class="mk-banner__title">Pengawasan Terstruktur & Berkelanjutan</h3>
                    <p class="mk-banner__text">
                        {{ $site->singkatan }} secara rutin melakukan monitoring terhadap kehadiran piket Ruang OSIS (RO), ketertiban
                        petugas GDS, dan kesesuaian eksekusi proposal program kerja dengan realisasi di lapangan.
                    </p>
                    <div class="mk-banner__chips">
                        <span><i class="fa-solid fa-circle-check"></i> Absensi Digital</span>
                        <span><i class="fa-solid fa-circle-check"></i> Rekapitulasi Mingguan</span>
                    </div>
                </article>
                <article class="mk-banner mk-banner--blue mk-reveal" data-delay="1">
                    <div class="mk-banner__icon"><i class="fa-solid fa-award"></i></div>
                    <h3 class="mk-banner__title">5 Aspek Standar Mutu Penilaian</h3>
                    <p class="mk-banner__text">
                        Setiap anggota dan seksi bidang dinilai secara objektif berdasarkan lima pilar: Keaktifan GDS,
                        Kedisiplinan Piket RO, Kekompakan Tim, Efektivitas Komunikasi, dan Keberhasilan Progja.
                    </p>
                    <div class="mk-banner__chips">
                        <span><i class="fa-solid fa-circle-check"></i> Berbasis Indikator</span>
                        <span><i class="fa-solid fa-circle-check"></i> Umpan Balik Terbuka</span>
                    </div>
                </article>
            </div>

            <div class="mk-metrics mk-reveal" data-delay="2">
                <div class="mk-metric">
                    <span class="mk-metric__value">10</span>
                    <span class="mk-metric__label">Total Seksi Bidang</span>
                </div>
                <div class="mk-metric">
                    <span class="mk-metric__value text-[#5b8cff]">92%</span>
                    <span class="mk-metric__label">Progja Terlaksana</span>
                </div>
                <div class="mk-metric">
                    <span class="mk-metric__value text-[#5b8cff]">150+</span>
                    <span class="mk-metric__label">Aspirasi Tersalurkan</span>
                </div>
                <div class="mk-metric">
                    <span class="mk-metric__value text-[#5b8cff]">A</span>
                    <span class="mk-metric__label">Predikat Evaluasi Umum</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== LAPORAN & PENILAIAN ==================== -->
    <section id="penilaian" class="mk-section mk-section--soft" x-data="{ activeFilter: 'semua' }">
        <div class="mk-container">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
                <div>
                    <div class="mk-eyebrow mk-reveal"><span>02</span> / Transparansi Kerja</div>
                    <h2 class="mk-heading mk-reveal" data-delay="1">Laporan & Publikasi<br><span
                            class="mk-outline">Penilaian Sekbid</span></h2>
                    <p class="mk-text mt-4 mk-reveal" data-delay="2">
                        Publikasi hasil pemantauan program kerja dan evaluasi kualitas pelaksanaan kegiatan oleh {{ $site->singkatan }}.
                    </p>
                </div>

                <div class="mk-tabs mk-reveal" data-delay="2">
                    <button type="button" @click="activeFilter = 'semua'"
                        :class="activeFilter === 'semua' ? 'is-active' : ''" class="mk-tab">Semua</button>
                    <button type="button" @click="activeFilter = 'terlaksana'"
                        :class="activeFilter === 'terlaksana' ? 'is-active' : ''" class="mk-tab">Terlaksana</button>
                    <button type="button" @click="activeFilter = 'sedang_berjalan'"
                        :class="activeFilter === 'sedang_berjalan' ? 'is-active' : ''" class="mk-tab">Sedang
                        Berjalan</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <article x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'" x-cloak
                    class="mk-card mk-progja mk-reveal">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 1</span>
                        <span class="mk-status mk-status--done"><i class="fa-solid fa-circle-check text-[9px]"></i>
                            Terlaksana</span>
                    </div>
                    <h3 class="mk-card__title mt-6">Peringatan Hari Besar Islam (PHBI)</h3>
                    <p class="mk-card__text">Peringatan Maulid Nabi dan kajian akbar yang dihadiri oleh seluruh siswa,
                        guru, dan komite sekolah.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> September 2026</span>
                        <span class="text-[#5b8cff]">Nilai: Sangat Baik</span>
                    </div>
                </article>

                <article x-show="activeFilter === 'semua' || activeFilter === 'sedang_berjalan'" x-cloak
                    class="mk-card mk-progja mk-reveal" data-delay="1">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 3</span>
                        <span class="mk-status mk-status--run"><i class="fa-solid fa-spinner text-[9px]"></i>
                            Berjalan</span>
                    </div>
                    <h3 class="mk-card__title mt-6">Latihan Dasar Kepemimpinan (LDK)</h3>
                    <p class="mk-card__text">Penggemblengan mental dan wawasan manajemen organisasi bagi calon pengurus
                        OSIS dan {{ $site->singkatan }} periode baru.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Sedang Berlangsung</span>
                        <span class="text-[#5b8cff]">Nilai: Baik</span>
                    </div>
                </article>

                <article x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'" x-cloak
                    class="mk-card mk-progja mk-reveal" data-delay="2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 4</span>
                        <span class="mk-status mk-status--done"><i class="fa-solid fa-circle-check text-[9px]"></i>
                            Terlaksana</span>
                    </div>
                    <h3 class="mk-card__title mt-6">Pekan Olahraga & Seni (Porseni)</h3>
                    <p class="mk-card__text">Turnamen futsal, basket, tari kreasi, dan vokal antarkelas yang berjalan
                        lancar dan suportif.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Agustus 2026</span>
                        <span class="text-[#5b8cff]">Nilai: Sangat Baik</span>
                    </div>
                </article>

                <article x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'" x-cloak
                    class="mk-card mk-progja mk-reveal">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 8</span>
                        <span class="mk-status mk-status--done"><i class="fa-solid fa-circle-check text-[9px]"></i>
                            Terlaksana</span>
                    </div>
                    <h3 class="mk-card__title mt-6">Bakti Sosial & Gerakan Sekolah Hijau</h3>
                    <p class="mk-card__text">Pembagian 200 paket sembako kepada masyarakat prasejahtera dan pemilahan bank
                        sampah sekolah.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Juli 2026</span>
                        <span class="text-[#5b8cff]">Nilai: Sangat Baik</span>
                    </div>
                </article>

                <article x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'" x-cloak
                    class="mk-card mk-progja mk-reveal" data-delay="1">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 9</span>
                        <span class="mk-status mk-status--done"><i class="fa-solid fa-circle-check text-[9px]"></i>
                            Terlaksana</span>
                    </div>
                    <h3 class="mk-card__title mt-6">Workshop Literasi Digital & Konten</h3>
                    <p class="mk-card__text">Pelatihan videografi, copywriting, dan etika bermedia sosial untuk seluruh
                        perwakilan kelas.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Juni 2026</span>
                        <span class="text-[#5b8cff]">Nilai: Baik</span>
                    </div>
                </article>

                <article x-show="activeFilter === 'semua' || activeFilter === 'sedang_berjalan'" x-cloak
                    class="mk-card mk-progja mk-reveal" data-delay="2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="mk-status mk-status--todo">Sekbid 10</span>
                        <span class="mk-status mk-status--run"><i class="fa-solid fa-spinner text-[9px]"></i>
                            Berjalan</span>
                    </div>
                    <h3 class="mk-card__title mt-6">English Club & Gelar Wicara</h3>
                    <p class="mk-card__text">Program penguatan speaking dan debat bahasa Inggris mingguan di perpustakaan
                        sekolah.</p>
                    <div
                        class="mt-auto pt-5 flex items-center justify-between border-t border-white/8 text-[10px] font-bold text-[#706d6a]">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Rutin Setiap Rabu</span>
                        <span class="text-[#5b8cff]">Nilai: Baik</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ==================== ASPIRASI ==================== -->
    <section id="aspirasi" class="mk-section" x-data="{ isAnonim: false }">
        <div class="mk-container">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
                <div>
                    <div class="mk-eyebrow mk-reveal"><span>03</span> / Formulir Aspirasi</div>
                    <h2 class="mk-heading mk-reveal" data-delay="1">Sampaikan <span class="mk-outline">Aspirasi</span>
                    </h2>
                </div>
                <p class="mk-text max-w-md mk-reveal" data-delay="2">
                    Suara Anda menentukan kemajuan sekolah kita bersama.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                <!-- Form -->
                <div class="lg:col-span-7 mk-form-card mk-reveal">
                    <div class="flex items-center justify-between gap-4 border-b border-white/8 pb-5 mb-6">
                        <div>
                            <h3 class="text-xl font-black text-[#f6f4f2]">Formulir Aspirasi</h3>
                            <p class="text-[11px] text-[#85827e] mt-1">Terbuka untuk seluruh siswa, tanpa perlu login.</p>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-[#5b8cff]/10 border border-[#5b8cff]/25 text-[#5b8cff] flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                        </div>
                    </div>

                    <form action="{{ route('aspirasi.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label for="nama_pengirim" class="mk-label">Nama / Kelas</label>
                                <input type="text" id="nama_pengirim" name="nama_pengirim" :disabled="isAnonim"
                                    :placeholder="isAnonim ? 'Identitas disembunyikan' : 'Contoh: Ahmad (XI PPLG 2)'"
                                    class="mk-input disabled:opacity-40">
                                @error('nama_pengirim')
                                    <p class="mk-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="kode_kategori" class="mk-label">Kategori <span
                                        class="text-[#5b8cff]">*</span></label>
                                <select id="kode_kategori" name="kode_kategori" required class="mk-input">
                                    <option value="" disabled selected>Pilih kategori...</option>
                                    @forelse($kategoris as $kategori)
                                        <option value="{{ $kategori->kode_kategori }}">{{ $kategori->nama_kategori }}
                                            ({{ $kategori->kode_kategori }})
                                        </option>
                                    @empty
                                        <option value="SARPRAS">Sarana & Prasarana</option>
                                        <option value="KEGIATAN">Kegiatan Siswa</option>
                                        <option value="AKADEMIK">Akademik</option>
                                        <option value="LAINNYA">Lainnya</option>
                                    @endforelse
                                </select>
                                @error('kode_kategori')
                                    <p class="mk-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <label class="mk-check">
                            <input type="checkbox" name="is_anonim" value="1" x-model="isAnonim">
                            <span>Kirim sebagai anonim</span>
                        </label>

                        <div class="space-y-2">
                            <label for="judul_aspirasi" class="mk-label">Judul Aspirasi <span
                                    class="text-[#5b8cff]">*</span></label>
                            <input type="text" id="judul_aspirasi" name="judul_aspirasi" required
                                placeholder="Contoh: Pengadaan stopkontak tambahan di ruang kelas"
                                value="{{ old('judul_aspirasi') }}" class="mk-input">
                            @error('judul_aspirasi')
                                <p class="mk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="deskripsi_aspirasi" class="mk-label">Rincian Aspirasi <span
                                    class="text-[#5b8cff]">*</span></label>
                            <textarea id="deskripsi_aspirasi" name="deskripsi_aspirasi" rows="5" required
                                placeholder="Jelaskan kendala, lokasi, atau usulan solusi Anda dengan jelas dan santun..."
                                class="mk-input resize-none">{{ old('deskripsi_aspirasi') }}</textarea>
                            @error('deskripsi_aspirasi')
                                <p class="mk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="mk-btn mk-btn--primary w-full justify-center">
                            <span>Kirim Aspirasi Sekarang</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </button>
                        <p class="text-center text-[10px] text-[#5b5855]">
                            <i class="fa-solid fa-lock mr-1"></i> Identitas Anda aman dan diproses oleh Komisi Aspirasi
                            {{ $site->singkatan }}.
                        </p>
                    </form>
                </div>

                <!-- Alur + feed -->
                <div class="lg:col-span-5 space-y-5">
                    <div id="alur-aspirasi" class="mk-panel mk-reveal" data-delay="1">
                        <h3 class="text-base font-black text-[#f6f4f2]">Mekanisme Tindak Lanjut</h3>
                        <p class="text-[11px] text-[#85827e] mt-1 mb-6">Bagaimana aspirasi Anda diproses hingga membuahkan
                            solusi?</p>
                        <ol class="mk-steps">
                            <li><span>1</span>
                                <div><b>Aspirasi Diterima & Verifikasi</b><small>{{ $site->singkatan }} mengkaji urgensi dan relevansi aspirasi
                                        yang masuk.</small></div>
                            </li>
                            <li><span>2</span>
                                <div><b>Musyawarah & Advokasi</b><small>Komisi A menyampaikan dalam rapat komisi bersama
                                        OSIS atau Pembina.</small></div>
                            </li>
                            <li><span>3</span>
                                <div><b>Rekomendasi & Eksekusi</b><small>Pihak sekolah/OSIS menindaklanjuti perbaikan di
                                        lapangan.</small></div>
                            </li>
                            <li><span class="mk-steps__done"><i class="fa-solid fa-check text-[10px]"></i></span>
                                <div><b>Publikasi Solusi</b><small>Status diperbarui menjadi 'Selesai' pada papan
                                        transparansi.</small></div>
                            </li>
                        </ol>
                    </div>

                    <div class="mk-panel mk-reveal" data-delay="2">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-base font-black text-[#f6f4f2]">Aspirasi Terkini</h3>
                            <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#5b8cff]">Status
                                Terbuka</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentAspirasi as $item)
                                <div class="mk-feed">
                                    <div class="flex items-center justify-between gap-3">
                                        <span
                                            class="text-[10px] font-bold text-[#85827e]">{{ $item->nama_pengirim }}</span>
                                        @if ($item->status_aspirasi === 'selesai')
                                            <span class="mk-status mk-status--done">Selesai</span>
                                        @elseif($item->status_aspirasi === 'proses')
                                            <span class="mk-status mk-status--run">Diproses</span>
                                        @else
                                            <span class="mk-status mk-status--todo">Menunggu</span>
                                        @endif
                                    </div>
                                    <p class="text-[12px] font-bold text-[#e8e6e3] mt-2 line-clamp-1">
                                        {{ $item->judul_aspirasi }}</p>
                                    <p class="text-[11px] text-[#85827e] mt-1 line-clamp-2 leading-relaxed">
                                        {{ $item->deskripsi_aspirasi }}</p>
                                </div>
                            @empty
                                <div class="mk-empty">Belum ada aspirasi. Jadilah yang pertama bersuara.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== STRUKTUR & VISI MISI ==================== -->
    <section id="visimisi" class="mk-section mk-section--soft">
        <div class="mk-container">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="mk-eyebrow justify-center mk-reveal"><span>04</span> / Organisasi & Visi</div>
                <h2 class="mk-heading mk-reveal" data-delay="1">Struktur Kepengurusan<br><span class="mk-outline">&
                        Komisi {{ $site->singkatan }}</span></h2>
                <p class="mk-text mx-auto mt-4 mk-reveal" data-delay="2">
                    Masa bakti kepengurusan {{ $site->namaLengkap }} dengan integritas tinggi dan dedikasi penuh.
                </p>
            </div>

            <div class="mk-panel mk-reveal">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-5 space-y-4 lg:border-r lg:border-white/8 lg:pr-8">
                        <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#5b8cff]">Visi Utama</span>
                        <blockquote class="mk-quote">
                            “{{ $site->visi }}”
                        </blockquote>
                        <p class="mk-card__text">
                            {{ $site->deskripsi }}
                        </p>
                    </div>
                    <div class="lg:col-span-7 space-y-3 lg:pl-4">
                        <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#85827e]">Misi
                            Organisasi</span>
                        @forelse ($site->misi as $misi)
                            <div class="mk-misi"><i
                                    class="fa-solid fa-check text-[#5b8cff] text-[10px]"></i><span>{{ $misi }}</span>
                            </div>
                        @empty
                            <p class="mk-card__text">Misi organisasi belum diisi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            @php
                $leaderStyles = [
                    ['fa-user-tie', 'mk-leader--red'],
                    ['fa-user-check', 'mk-leader--orange'],
                    ['fa-file-signature', 'mk-leader--blue'],
                    ['fa-wallet', 'mk-leader--mint'],
                ];
            @endphp
            <div id="struktur" class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($site->pengurus as $leader)
                    @php $leaderStyle = $leaderStyles[$loop->index % count($leaderStyles)]; @endphp
                    <div class="mk-leader mk-reveal">
                        <div class="mk-leader__avatar {{ $leaderStyle[1] }}"><i
                                class="fa-solid {{ $leaderStyle[0] }}"></i>
                        </div>
                        <h4 class="mk-leader__name">{{ $leader->nama_pengurus }}</h4>
                        <p class="mk-leader__role">{{ $leader->jabatan_pengurus }}</p>
                        <p class="mk-leader__meta">{{ $leader->komisi_pengurus }}</p>
                    </div>
                @empty
                    <div class="col-span-full mk-empty">Struktur pengurus belum diisi.</div>
                @endforelse
            </div>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($site->komisi as $komisi)
                    <div class="mk-tile mk-reveal">
                        <span class="mk-tile__num text-[#5b8cff]">{{ $komisi['kode'] }}</span>
                        <div>
                            <h3 class="mk-tile__title">{{ $komisi['nama'] }}</h3>
                            <p class="mk-tile__meta">{{ $komisi['deskripsi'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== DOKUMENTASI KEGIATAN ==================== -->
    <section id="dokumentasi" class="mk-section">
        <div class="mk-container">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="mk-eyebrow justify-center mk-reveal"><span>05</span> / Dokumentasi</div>
                <h2 class="mk-heading mk-reveal" data-delay="1">Dokumentasi<br><span class="mk-outline">& Kegiatan Sekolah</span></h2>
                <p class="mk-text mx-auto mt-4 mk-reveal" data-delay="2">
                    Momen dan kegiatan organisasi yang terdokumentasi untuk seluruh warga sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($fotoDokumentasi as $foto)
                    <a href="{{ Storage::url($foto->foto) }}" target="_blank"
                       class="mk-card mk-reveal overflow-hidden block" title="Lihat foto penuh">
                        <div class="aspect-video overflow-hidden bg-black/20">
                            <img src="{{ Storage::url($foto->foto) }}" alt="{{ $foto->judul }}"
                                 loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-4">
                            <h3 class="mk-card__title">{{ $foto->judul }}</h3>
                            <p class="mk-card__text">{{ $foto->keterangan ?: 'Dokumentasi kegiatan.' }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full mk-empty">Foto dokumentasi belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <script>
        (function() {
            try {
                if (localStorage.getItem('mk-theme') === 'light') {
                    document.documentElement.classList.add('mk-light');
                }
            } catch (error) {
                // Theme preference is optional when storage is unavailable.
            }
        })();
    </script>
    <style>
        .landing-page {
            --mk-bg: #050608;
            --mk-soft: #0a0c10;
            --mk-surface: #111318;
            --mk-line: rgba(255, 255, 255, 0.09);
            --mk-fg: #f6f4f2;
            --mk-muted: #9b9895;
            --mk-blue: #5b8cff;
            background: var(--mk-bg);
            color: var(--mk-fg);
        }

        .landing-page main {
            background: var(--mk-bg);
        }

        /* Progress bar */
        #mk-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            width: 0;
            z-index: 60;
            background: var(--mk-blue);
            box-shadow: 0 0 18px rgba(91, 140, 255, 0.8);
            transition: width 0.1s linear;
        }

        /* Navbar override */
        .landing-page header {
            background: rgba(5, 6, 8, 0.82) !important;
            border-color: var(--mk-line) !important;
            box-shadow: none !important;
            backdrop-filter: blur(18px);
        }

        .landing-page header .bg-gradient-to-tr {
            background: linear-gradient(135deg, #5b8cff, #2447a8) !important;
            box-shadow: 0 8px 24px rgba(91, 140, 255, 0.22) !important;
        }

        .landing-page header .text-slate-900 {
            color: var(--mk-fg) !important;
        }

        .landing-page header .text-slate-700,
        .landing-page header .text-slate-500 {
            color: #b7b4b1 !important;
        }

        .landing-page header nav a {
            color: #c2bfbc !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .landing-page header nav a:hover,
        .landing-page header nav a:focus {
            color: var(--mk-blue) !important;
            background: rgba(91, 140, 255, 0.08) !important;
        }

        .landing-page header .border-slate-200 {
            border-color: var(--mk-line) !important;
        }

        .landing-page header .border-slate-200\/80 {
            border-color: var(--mk-line) !important;
        }

        .landing-page header .bg-blue-50 {
            background: rgba(91, 140, 255, 0.1) !important;
            border-color: rgba(91, 140, 255, 0.25) !important;
        }

        .landing-page header .text-blue-600 {
            color: #7aa5ff !important;
        }

        .landing-page header .bg-blue-600 {
            background: var(--mk-blue) !important;
            box-shadow: 0 8px 24px rgba(91, 140, 255, 0.22) !important;
        }

        .landing-page header .bg-blue-600:hover {
            background: #7aa5ff !important;
        }

        .landing-page header .hover\:bg-slate-50:hover,
        .landing-page header .hover\:bg-slate-100:hover {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        .landing-page header .hover\:text-blue-600:hover {
            color: #7aa5ff !important;
        }

        .landing-page header .bg-slate-950\/95 {
            background: rgba(5, 6, 8, 0.95) !important;
        }

        /* Footer override */
        .landing-page footer {
            background: #050608 !important;
            border-color: var(--mk-line) !important;
        }

        .landing-page footer .bg-blue-600,
        .landing-page footer .hover\:bg-blue-600:hover {
            background: var(--mk-blue) !important;
        }

        .landing-page footer .text-blue-400,
        .landing-page footer .text-blue-500 {
            color: #7aa5ff !important;
        }

        .landing-page footer .bg-slate-800\/80 {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        /* Layout */
        .mk-container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        @media (min-width: 640px) {
            .mk-container {
                padding: 0 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .mk-container {
                padding: 0 2rem;
            }
        }

        .mk-section {
            position: relative;
            min-height: 100svh;
            padding: 8rem 0;
            border-bottom: 1px solid var(--mk-line);
            overflow: hidden;
        }

        .mk-section--soft {
            background: var(--mk-soft);
        }

        .mk-hero {
            min-height: calc(100svh - 5rem);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-top: 5rem;
            padding-bottom: 4rem;
        }

        /* Hero background details */
        .mk-grid-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: radial-gradient(ellipse at 30% 40%, black, transparent 72%);
            -webkit-mask-image: radial-gradient(ellipse at 30% 40%, black, transparent 72%);
        }

        .mk-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(90px);
            pointer-events: none;
            opacity: 0.32;
        }

        .mk-orb--red {
            width: 420px;
            height: 420px;
            background: var(--mk-blue);
            top: -160px;
            right: -80px;
            animation: mkFloat 12s ease-in-out infinite alternate;
        }

        .mk-orb--blue {
            width: 360px;
            height: 360px;
            background: var(--mk-blue);
            bottom: 4%;
            left: -140px;
            opacity: 0.18;
            animation: mkFloat 15s ease-in-out infinite alternate-reverse;
        }

        /* Typography */
        .mk-kicker {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.6rem;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #a7a4a1;
            margin-bottom: 2rem;
        }

        .mk-kicker__dot {
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            background: var(--mk-blue);
            box-shadow: 0 0 14px var(--mk-blue);
            animation: mkPulse 1.8s ease-in-out infinite;
        }

        .mk-kicker__sep {
            color: #4a4745;
        }

        .mk-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: clamp(3.1rem, 10.5vw, 10rem);
            line-height: 0.9;
            letter-spacing: -0.075em;
            text-transform: uppercase;
            margin: 0;
        }

        .mk-title__line {
            display: block;
        }

        .mk-outline {
            color: transparent;
            -webkit-text-stroke: 1.5px rgba(246, 244, 242, 0.78);
        }

        .mk-dot {
            color: var(--mk-blue);
        }

        .mk-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: clamp(2.4rem, 5.6vw, 5.2rem);
            line-height: 0.98;
            letter-spacing: -0.06em;
            text-transform: uppercase;
            margin: 0;
        }

        .mk-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #85827e;
            margin-bottom: 1.5rem;
        }

        .mk-eyebrow span {
            color: var(--mk-blue);
        }

        .mk-lead,
        .mk-text {
            color: #a7a4a1;
            font-size: 0.95rem;
            line-height: 1.8;
            max-width: 46rem;
        }

        .mk-lead {
            margin-top: 2rem;
            font-size: 1rem;
        }

        .mk-text {
            font-size: 0.9rem;
        }

        /* Buttons */
        .mk-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 2.25rem;
        }

        .mk-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.95rem 1.4rem;
            border-radius: 0.75rem;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: transform .25s ease, background .25s ease, border-color .25s ease, box-shadow .25s ease;
            cursor: pointer;
        }

        .mk-btn:hover {
            transform: translateY(-3px);
        }

        .mk-btn--primary {
            color: #fff;
            background: var(--mk-blue);
            box-shadow: 0 14px 34px rgba(91, 140, 255, 0.28);
        }

        .mk-btn--primary:hover {
            background: #7aa5ff;
            box-shadow: 0 18px 40px rgba(91, 140, 255, 0.38);
        }

        .mk-btn--ghost {
            color: var(--mk-fg);
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.03);
        }

        .mk-btn--ghost:hover {
            border-color: rgba(91, 140, 255, 0.5);
            background: rgba(91, 140, 255, 0.06);
        }

        /* Stats */
        .mk-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem 1rem;
            margin-top: 3.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--mk-line);
            max-width: 640px;
        }

        @media (min-width: 640px) {
            .mk-stats {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .mk-stat {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .mk-stat__num {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            line-height: 1;
        }

        .mk-stat__label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #706d6a;
        }

        /* Console */
        .mk-console {
            position: relative;
            background: linear-gradient(160deg, rgba(28, 31, 37, 0.96), rgba(12, 14, 18, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.11);
            border-radius: 1.25rem;
            box-shadow: 0 40px 90px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .mk-console::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(circle at 80% 0%, rgba(91, 140, 255, 0.12), transparent 55%);
            pointer-events: none;
        }

        .mk-float {
            animation: mkFloat 9s ease-in-out infinite alternate;
        }

        .mk-console__row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.8rem;
            border-radius: 0.7rem;
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.045);
        }

        .mk-console__index {
            font-family: ui-monospace, monospace;
            font-size: 10px;
            font-weight: 700;
            color: var(--mk-blue);
        }

        .mk-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.6rem;
            border-radius: 9999px;
            border: 1px solid rgba(91, 140, 255, 0.25);
            background: rgba(91, 140, 255, 0.07);
            color: var(--mk-blue);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.12em;
        }

        .mk-pill__dot {
            width: 5px;
            height: 5px;
            border-radius: 9999px;
            background: var(--mk-blue);
            animation: mkPulse 1.6s infinite;
        }

        .mk-track {
            height: 5px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.07);
            overflow: hidden;
        }

        .mk-track__fill {
            height: 100%;
            border-radius: inherit;
            transition: width 1.2s cubic-bezier(.22, 1, .36, 1);
        }

        .mk-badge {
            position: absolute;
            padding: 0.5rem 0.75rem;
            border-radius: 0.6rem;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.18em;
            border: 1px solid var(--mk-line);
            background: rgba(10, 12, 16, 0.9);
            backdrop-filter: blur(8px);
            animation: mkFloat 7s ease-in-out infinite alternate-reverse;
        }

        .mk-badge--one {
            top: -22px;
            right: 18%;
            color: var(--mk-blue);
        }

        .mk-badge--two {
            bottom: -20px;
            left: 8%;
            color: var(--mk-blue);
            animation-delay: -2s;
        }

        /* Theme toggle */
        .mk-theme-toggle {
            position: fixed;
            top: 5.6rem;
            right: 1.25rem;
            z-index: 50;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid var(--mk-line);
            background: rgba(10, 12, 16, 0.88);
            color: var(--mk-fg);
            backdrop-filter: blur(10px);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
            transition: transform .25s ease, border-color .25s ease, background .25s ease;
            cursor: pointer;
        }

        .mk-theme-toggle:hover {
            transform: translateY(-2px);
            border-color: var(--mk-blue);
        }

        @media (min-width: 640px) {
            .mk-theme-toggle {
                right: 2rem;
            }
        }

        /* Marquee */
        .mk-marquee {
            overflow: hidden;
            border-bottom: 1px solid var(--mk-line);
            background: var(--mk-blue);
            padding: 0.85rem 0;
        }

        .mk-marquee__track {
            display: flex;
            width: max-content;
            animation: mkMarquee 28s linear infinite;
        }

        .mk-marquee__track span {
            font-size: clamp(0.8rem, 2vw, 1.1rem);
            font-weight: 800;
            letter-spacing: 0.22em;
            color: #fff;
            white-space: nowrap;
        }

        .mk-marquee__track b {
            color: rgba(0, 0, 0, 0.35);
            margin: 0 0.6rem;
        }

        /* Cards */
        .mk-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 260px;
            padding: 1.6rem;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.012));
            border: 1px solid var(--mk-line);
            border-radius: 1.1rem;
            transition: transform .35s ease, border-color .35s ease, background .35s ease;
        }

        .mk-card:hover {
            transform: translateY(-6px);
            border-color: rgba(91, 140, 255, 0.45);
            background: rgba(91, 140, 255, 0.035);
        }

        .mk-card__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }

        .mk-card__top i {
            color: var(--mk-blue);
            font-size: 1.1rem;
        }

        .mk-index {
            font-family: ui-monospace, monospace;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.14em;
            color: var(--mk-blue);
        }

        .mk-card__title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--mk-fg);
            margin: 0 0 0.6rem;
        }

        .mk-card__text {
            font-size: 0.78rem;
            line-height: 1.75;
            color: #8d8a87;
            margin: 0;
        }

        .mk-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mk-blue);
        }

        .mk-link:hover {
            color: #7aa5ff;
        }

        /* Sekbid tiles */
        .mk-tile {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2.5rem;
            min-height: 164px;
            padding: 1.1rem;
            border-radius: 1rem;
            border: 1px solid var(--mk-line);
            background: linear-gradient(150deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.008));
            transition: transform .3s ease, border-color .3s ease, background .3s ease;
        }

        .mk-tile:hover {
            transform: translateY(-5px);
            border-color: rgba(91, 140, 255, 0.4);
            background: rgba(91, 140, 255, 0.05);
        }

        .mk-tile__num {
            font-family: ui-monospace, monospace;
            font-size: 0.8rem;
            font-weight: 800;
            color: #706d6a;
        }

        .mk-tile__title {
            font-size: 0.82rem;
            font-weight: 800;
            line-height: 1.4;
            color: var(--mk-fg);
            margin: 0 0 0.35rem;
        }

        .mk-tile__meta {
            font-size: 10px;
            color: #706d6a;
            margin: 0;
        }

        .mk-empty {
            grid-column: 1 / -1;
            padding: 2.5rem;
            border: 1px dashed var(--mk-line);
            border-radius: 1rem;
            text-align: center;
            font-size: 0.8rem;
            color: #706d6a;
        }

        /* Status badges */
        .mk-status {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.6rem;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border: 1px solid;
            white-space: nowrap;
        }

        .mk-status--done {
            color: var(--mk-blue);
            border-color: rgba(91, 140, 255, 0.3);
            background: rgba(91, 140, 255, 0.07);
        }

        .mk-status--run {
            color: var(--mk-blue);
            border-color: rgba(91, 140, 255, 0.3);
            background: rgba(91, 140, 255, 0.07);
        }

        .mk-status--todo {
            color: #a7a4a1;
            border-color: rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
        }

        .mk-progja {
            min-height: 240px;
        }

        /* Filter tabs */
        .mk-tabs {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem;
            border-radius: 0.9rem;
            border: 1px solid var(--mk-line);
            background: rgba(255, 255, 255, 0.025);
            width: fit-content;
        }

        .mk-tab {
            padding: 0.6rem 1rem;
            border-radius: 0.65rem;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #a7a4a1;
            transition: background .25s ease, color .25s ease;
            white-space: nowrap;
        }

        .mk-tab:hover {
            color: var(--mk-fg);
            background: rgba(255, 255, 255, 0.05);
        }

        .mk-tab.is-active {
            color: #fff;
            background: var(--mk-blue);
            box-shadow: 0 8px 20px rgba(91, 140, 255, 0.25);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Feature banners */
        .mk-banner {
            position: relative;
            overflow: hidden;
            padding: 2rem;
            border-radius: 1.2rem;
            border: 1px solid var(--mk-line);
            background: linear-gradient(135deg, rgba(91, 140, 255, 0.18), rgba(91, 140, 255, 0.03));
        }

        .mk-banner--blue {
            background: linear-gradient(135deg, rgba(91, 140, 255, 0.18), rgba(91, 140, 255, 0.04));
        }

        .mk-banner::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            bottom: -100px;
            border-radius: 9999px;
            background: rgba(91, 140, 255, 0.08);
            pointer-events: none;
        }

        .mk-banner--blue::after {
            background: rgba(91, 140, 255, 0.08);
        }

        .mk-banner__icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.06);
            color: #7aa5ff;
            font-size: 1.15rem;
            margin-bottom: 1.5rem;
        }

        .mk-banner--blue .mk-banner__icon {
            color: #8fb0ff;
        }

        .mk-banner__title {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.35;
            margin: 0 0 0.8rem;
            color: var(--mk-fg);
        }

        .mk-banner__text {
            font-size: 0.8rem;
            line-height: 1.8;
            color: #a7a4a1;
            margin: 0 0 1.5rem;
        }

        .mk-banner__chips {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 10px;
            font-weight: 800;
            color: #d6d4d1;
        }

        .mk-banner__chips i {
            color: var(--mk-blue);
            margin-right: 0.35rem;
        }

        /* Metric bar */
        .mk-metrics {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            border: 1px solid var(--mk-line);
            border-radius: 1.2rem;
            background: rgba(255, 255, 255, 0.02);
            overflow: hidden;
        }

        @media (min-width: 1024px) {
            .mk-metrics {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .mk-metric {
            padding: 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid var(--mk-line);
        }

        .mk-metric:nth-child(odd) {
            border-right: 1px solid var(--mk-line);
        }

        @media (min-width: 1024px) {
            .mk-metric {
                border-bottom: 0;
                border-right: 1px solid var(--mk-line);
            }

            .mk-metric:last-child {
                border-right: 0;
            }
        }

        .mk-metric__value {
            display: block;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            line-height: 1;
        }

        .mk-metric__label {
            display: block;
            margin-top: 0.6rem;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #85827e;
        }

        /* Leaders */
        .mk-leader {
            padding: 1.6rem;
            text-align: center;
            border-radius: 1.1rem;
            border: 1px solid var(--mk-line);
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.012));
            transition: transform .3s ease, border-color .3s ease;
        }

        .mk-leader:hover {
            transform: translateY(-5px);
            border-color: rgba(91, 140, 255, 0.4);
        }

        .mk-leader__avatar {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.2rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
        }

        .mk-leader--red {
            background: linear-gradient(135deg, #5b8cff, #2447a8);
            box-shadow: 0 12px 30px rgba(91, 140, 255, 0.22);
        }

        .mk-leader--orange {
            background: linear-gradient(135deg, #5b8cff, #2447a8);
            box-shadow: 0 12px 30px rgba(91, 140, 255, 0.18);
        }

        .mk-leader--blue {
            background: linear-gradient(135deg, #5b8cff, #2447a8);
            box-shadow: 0 12px 30px rgba(91, 140, 255, 0.18);
        }

        .mk-leader--mint {
            background: linear-gradient(135deg, #5b8cff, #2447a8);
            box-shadow: 0 12px 30px rgba(91, 140, 255, 0.16);
        }

        .mk-leader__name {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--mk-fg);
            margin: 0;
        }

        .mk-leader__role {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #7aa5ff;
            margin: 0.4rem 0 0;
        }

        .mk-leader__meta {
            font-size: 10px;
            color: #85827e;
            margin: 0.5rem 0 0;
        }

        /* Panel */
        .mk-panel {
            padding: 2.5rem;
            border-radius: 1.25rem;
            border: 1px solid var(--mk-line);
            background: linear-gradient(150deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.012));
        }

        @media (max-width: 640px) {
            .mk-panel {
                padding: 1.5rem;
            }
        }

        .mk-quote {
            margin: 0;
            padding-left: 1.25rem;
            border-left: 3px solid var(--mk-blue);
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.6;
            color: var(--mk-fg);
        }

        .mk-misi {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            font-size: 0.78rem;
            line-height: 1.6;
            color: #a7a4a1;
        }

        /* Form */
        .mk-form-card {
            padding: 2rem;
            border-radius: 1.25rem;
            border: 1px solid var(--mk-line);
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.012));
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35);
        }

        @media (max-width: 640px) {
            .mk-form-card {
                padding: 1.4rem;
            }
        }

        .mk-label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #a7a4a1;
        }

        .mk-input {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 0.75rem;
            background: #0a0c10;
            border: 1px solid var(--mk-line);
            color: var(--mk-fg);
            font-size: 0.8rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .mk-input::placeholder {
            color: #4f4c49;
        }

        .mk-input:focus {
            border-color: var(--mk-blue);
            box-shadow: 0 0 0 3px rgba(91, 140, 255, 0.14);
        }

        .mk-input option {
            background: #111318;
            color: var(--mk-fg);
        }

        .mk-check {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.78rem;
            color: #a7a4a1;
            cursor: pointer;
        }

        .mk-check input {
            width: 15px;
            height: 15px;
            accent-color: var(--mk-blue);
            border-radius: 4px;
        }

        .mk-error {
            font-size: 10px;
            font-weight: 700;
            color: #7aa5ff;
        }

        /* Steps + feed */
        .mk-steps {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 1.1rem;
        }

        .mk-steps li {
            display: flex;
            gap: 0.9rem;
        }

        .mk-steps li>span {
            width: 26px;
            height: 26px;
            flex: 0 0 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: rgba(91, 140, 255, 0.12);
            border: 1px solid rgba(91, 140, 255, 0.3);
            color: var(--mk-blue);
            font-size: 10px;
            font-weight: 800;
        }

        .mk-steps__done {
            background: rgba(91, 140, 255, 0.12) !important;
            border-color: rgba(91, 140, 255, 0.3) !important;
            color: var(--mk-blue) !important;
        }

        .mk-steps b {
            display: block;
            font-size: 0.78rem;
            color: var(--mk-fg);
        }

        .mk-steps small {
            display: block;
            font-size: 0.68rem;
            color: #706d6a;
            margin-top: 0.2rem;
        }

        .mk-feed {
            padding: 0.9rem;
            border-radius: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(255, 255, 255, 0.025);
        }

        /* Reveal animation */
        .mk-reveal {
            opacity: 0;
            transform: translateY(38px);
            transition: opacity .85s cubic-bezier(.22, 1, .36, 1), transform .85s cubic-bezier(.22, 1, .36, 1);
        }

        .mk-reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        .mk-reveal[data-delay="1"] {
            transition-delay: .1s;
        }

        .mk-reveal[data-delay="2"] {
            transition-delay: .2s;
        }

        .mk-reveal[data-delay="3"] {
            transition-delay: .3s;
        }

        .mk-reveal[data-delay="4"] {
            transition-delay: .4s;
        }

        .mk-reveal[data-delay="5"] {
            transition-delay: .5s;
        }

        @keyframes mkFloat {
            from {
                transform: translate3d(0, 0, 0) rotate(-1.5deg);
            }

            to {
                transform: translate3d(0, -20px, 0) rotate(2deg);
            }
        }

        @keyframes mkPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .45;
                transform: scale(.75);
            }
        }

        @keyframes mkMarquee {
            to {
                transform: translateX(-50%);
            }
        }

        /* ==================== LIGHT MODE ==================== */
        :is(html.mk-light .landing-page, .landing-page.mk-light) {
            --mk-bg: #f6f4f2;
            --mk-soft: #eeece9;
            --mk-surface: #ffffff;
            --mk-line: rgba(15, 17, 22, 0.1);
            --mk-fg: #16181d;
            --mk-muted: #5f5c58;
            --mk-dim: #7d7a76;
            --mk-outline-stroke: rgba(22, 24, 29, 0.72);
            background: var(--mk-bg);
            color: var(--mk-fg);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-outline {
            -webkit-text-stroke-color: var(--mk-outline-stroke);
        }

        /* Navbar in light mode */
        :is(html.mk-light .landing-page, .landing-page.mk-light) header {
            background: rgba(246, 244, 242, 0.92) !important;
            border-color: var(--mk-line) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .text-slate-900 {
            color: #16181d !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .text-slate-700,
        :is(html.mk-light .landing-page, .landing-page.mk-light) header .text-slate-500 {
            color: #4c4a47 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header nav a {
            color: #4c4a47 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header nav a:hover,
        :is(html.mk-light .landing-page, .landing-page.mk-light) header nav a:focus {
            color: #385fbf !important;
            background: rgba(56, 95, 191, 0.08) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .border-slate-200,
        :is(html.mk-light .landing-page, .landing-page.mk-light) header .border-slate-200\/80 {
            border-color: var(--mk-line) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .bg-blue-50 {
            background: rgba(56, 95, 191, 0.08) !important;
            border-color: rgba(56, 95, 191, 0.2) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .text-blue-600,
        :is(html.mk-light .landing-page, .landing-page.mk-light) header .hover\:text-blue-600:hover {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) header .hover\:bg-slate-50:hover,
        :is(html.mk-light .landing-page, .landing-page.mk-light) header .hover\:bg-slate-100:hover {
            background: rgba(0, 0, 0, 0.04) !important;
        }

        /* Footer in light mode */
        :is(html.mk-light .landing-page, .landing-page.mk-light) footer {
            background: #e8e5e1 !important;
            color: #5f5c58;
            border-color: var(--mk-line) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) footer h4,
        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .text-white {
            color: #16181d !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .border-slate-800 {
            border-color: var(--mk-line) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .bg-slate-800\/80 {
            background: rgba(0, 0, 0, 0.05) !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .text-blue-400,
        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .text-blue-500 {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) footer .hover\:text-slate-400:hover {
            color: #16181d !important;
        }

        /* Shared surfaces and text */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-grid-bg {
            background-image:
                linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-orb {
            opacity: 0.12;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-kicker,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-lead,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-text {
            color: #5f5c58;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-kicker__sep {
            color: #b6b2ae;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-btn--ghost {
            color: #16181d;
            border-color: rgba(0, 0, 0, 0.16);
            background: rgba(0, 0, 0, 0.02);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-btn--ghost:hover {
            border-color: rgba(56, 95, 191, 0.5);
            background: rgba(56, 95, 191, 0.06);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-stats {
            border-top-color: var(--mk-line);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-stat__label {
            color: #7d7a76;
        }

        /* Console */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-console {
            background: linear-gradient(160deg, #ffffff, #f4f2ef);
            border-color: var(--mk-line);
            box-shadow: 0 30px 70px rgba(15, 17, 22, 0.12);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-console::after {
            background: radial-gradient(circle at 80% 0%, rgba(56, 95, 191, 0.08), transparent 55%);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-console__row {
            background: rgba(0, 0, 0, 0.02);
            border-color: rgba(0, 0, 0, 0.06);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-console__index {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-pill {
            color: #385fbf;
            border-color: rgba(56, 95, 191, 0.25);
            background: rgba(56, 95, 191, 0.07);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-pill__dot {
            background: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-track {
            background: rgba(0, 0, 0, 0.08);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-badge {
            border-color: var(--mk-line);
            background: rgba(255, 255, 255, 0.92);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-badge--one {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-badge--two {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-theme-toggle {
            background: rgba(255, 255, 255, 0.92);
            color: #16181d;
            box-shadow: 0 12px 30px rgba(15, 17, 22, 0.12);
        }

        /* Cards, tiles, panels */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-card,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tile,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-panel,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-form-card {
            background: linear-gradient(160deg, #ffffff, #f7f5f2);
            border-color: var(--mk-line);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-card:hover,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tile:hover {
            border-color: rgba(56, 95, 191, 0.4);
            background: rgba(56, 95, 191, 0.04);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-form-card {
            box-shadow: 0 30px 70px rgba(15, 17, 22, 0.1);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-card__title,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tile__title {
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-card__text,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-misi {
            color: #5f5c58;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-link {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tile__num,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tile__meta,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-empty {
            color: #7d7a76;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-empty {
            border-color: var(--mk-line);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-quote {
            color: #16181d;
            border-left-color: #385fbf;
        }

        /* Status badges */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-status--done {
            color: #385fbf;
            border-color: rgba(56, 95, 191, 0.3);
            background: rgba(56, 95, 191, 0.08);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-status--run {
            color: #385fbf;
            border-color: rgba(56, 95, 191, 0.3);
            background: rgba(91, 140, 255, 0.1);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-status--todo {
            color: #5f5c58;
            border-color: var(--mk-line);
            background: rgba(0, 0, 0, 0.03);
        }

        /* Form */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-label,
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-check {
            color: #5f5c58;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-input {
            background: #f4f2ef;
            border-color: var(--mk-line);
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-input:focus {
            border-color: #385fbf;
            box-shadow: 0 0 0 3px rgba(56, 95, 191, 0.12);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-input::placeholder {
            color: #a09c98;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-input option {
            background: #ffffff;
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-check input {
            accent-color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-error {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-feed {
            border-color: var(--mk-line);
            background: rgba(0, 0, 0, 0.02);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-steps b {
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-steps small {
            color: #7d7a76;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-steps li>span {
            background: rgba(56, 95, 191, 0.1);
            border-color: rgba(56, 95, 191, 0.3);
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-steps__done {
            background: rgba(56, 95, 191, 0.1) !important;
            border-color: rgba(56, 95, 191, 0.3) !important;
            color: #385fbf !important;
        }

        /* Tabs, banners, metrics and leaders in light mode */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tabs {
            border-color: var(--mk-line);
            background: rgba(0, 0, 0, 0.03);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tab {
            color: #5f5c58;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tab:hover {
            color: #16181d;
            background: rgba(0, 0, 0, 0.05);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-tab.is-active {
            color: #fff;
            background: #385fbf;
            box-shadow: 0 8px 20px rgba(56, 95, 191, 0.22);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner {
            border-color: var(--mk-line);
            background: linear-gradient(135deg, rgba(56, 95, 191, 0.07), rgba(56, 95, 191, 0.015));
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner--blue {
            background: linear-gradient(135deg, rgba(91, 140, 255, 0.08), rgba(91, 140, 255, 0.03));
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner::after {
            background: rgba(56, 95, 191, 0.05);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner--blue::after {
            background: rgba(91, 140, 255, 0.05);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner__icon {
            border-color: var(--mk-line);
            background: rgba(255, 255, 255, 0.6);
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner--blue .mk-banner__icon {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner__title {
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner__text {
            color: #5f5c58;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-banner__chips {
            color: #24262b;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-metrics {
            border-color: var(--mk-line);
            background: rgba(255, 255, 255, 0.5);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-metric {
            border-color: var(--mk-line);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-metric__label {
            color: #7d7a76;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-leader {
            border-color: var(--mk-line);
            background: linear-gradient(160deg, #ffffff, #f7f5f2);
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-leader__name {
            color: #16181d;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-leader__role {
            color: #385fbf;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .mk-leader__meta {
            color: #7d7a76;
        }

        /* Arbitrary utility colors used in the markup */
        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#f6f4f2\] {
            color: #16181d !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#e8e6e3\] {
            color: #1d1f24 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#d6d4d1\] {
            color: #24262b !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#a7a4a1\] {
            color: #5f5c58 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#85827e\] {
            color: #6f6c68 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#706d6a\] {
            color: #7d7a76 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#5b5855\] {
            color: #8c8985 !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#5b8cff\] {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#5b8cff\] {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#5b8cff\] {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-\[\#7aa5ff\] {
            color: #385fbf !important;
        }

        :is(html.mk-light .landing-page, .landing-page.mk-light) .text-white\/25 {
            color: rgba(0, 0, 0, 0.25) !important;
        }

        @media (prefers-reduced-motion: reduce) {
            .mk-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .mk-float,
            .mk-orb,
            .mk-badge,
            .mk-marquee__track,
            .mk-kicker__dot,
            .mk-pill__dot {
                animation: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const themeToggle = document.getElementById('mk-theme-toggle');
            const storedTheme = (function() {
                try {
                    return localStorage.getItem('mk-theme');
                } catch (error) {
                    return null;
                }
            })();
            let isLight = storedTheme === 'light' || document.documentElement.classList.contains('mk-light');

            const applyTheme = () => {
                document.documentElement.classList.toggle('mk-light', isLight);
                document.body.classList.toggle('mk-light', isLight);
                if (themeToggle) {
                    themeToggle.setAttribute('aria-pressed', String(isLight));
                    themeToggle.querySelector('.mk-theme-icon').className = 'fa-solid ' + (isLight ? 'fa-moon' :
                        'fa-sun') + ' mk-theme-icon';
                    themeToggle.querySelector('.mk-theme-label').textContent = isLight ? 'Dark mode' : 'Light mode';
                }
            };
            applyTheme();

            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    isLight = !isLight;
                    try {
                        localStorage.setItem('mk-theme', isLight ? 'light' : 'dark');
                    } catch (error) {
                        /* ignore */
                    }
                    applyTheme();
                });
            }

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const reveals = document.querySelectorAll('.mk-reveal');

            if (reduceMotion || !('IntersectionObserver' in window)) {
                reveals.forEach((el) => el.classList.add('is-visible'));
            } else {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -6% 0px'
                });
                reveals.forEach((el) => revealObserver.observe(el));
            }

            const progress = document.getElementById('mk-progress');
            const updateProgress = () => {
                const scrollable = document.documentElement.scrollHeight - window.innerHeight;
                progress.style.width = (scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0) + '%';
            };
            window.addEventListener('scroll', updateProgress, {
                passive: true
            });
            window.addEventListener('resize', updateProgress);
            updateProgress();

            document.querySelectorAll('[data-count]').forEach((el) => {
                const target = Number(el.dataset.count || 0);
                const suffix = el.dataset.suffix || '';
                if (reduceMotion || !('IntersectionObserver' in window)) {
                    el.textContent = target + suffix;
                    return;
                }
                let started = false;
                const counterObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting || started) {
                            return;
                        }
                        started = true;
                        const start = performance.now();
                        const duration = 1300;
                        const tick = (now) => {
                            const progressValue = Math.min((now - start) / duration, 1);
                            const eased = 1 - Math.pow(1 - progressValue, 3);
                            el.textContent = Math.round(target * eased) + suffix;
                            if (progressValue < 1) {
                                requestAnimationFrame(tick);
                            }
                        };
                        requestAnimationFrame(tick);
                        counterObserver.unobserve(el);
                    });
                }, {
                    threshold: 0.5
                });
                counterObserver.observe(el);
            });

            const bars = document.querySelectorAll('[data-progress]');
            if ('IntersectionObserver' in window) {
                const barObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.style.width = entry.target.dataset.progress + '%';
                            barObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.4
                });
                bars.forEach((bar) => barObserver.observe(bar));
            } else {
                bars.forEach((bar) => {
                    bar.style.width = bar.dataset.progress + '%';
                });
            }
        })();
    </script>
@endpush

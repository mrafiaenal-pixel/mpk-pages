@extends('layout.app')

@section('title', 'MPK - Portal Resmi Majelis Perwakilan Kelas')

@section('content')

    <!-- ==================== HERO SECTION ==================== -->
    <section id="beranda" class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-blue-50/70 via-white to-slate-50/40">
        <!-- Background Decorative Elements -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-24 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left: Headline & Actions -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <!-- Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/80 border border-blue-200 text-blue-700 text-xs font-semibold uppercase tracking-wider shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Portal Aspirasi & Pemantauan MPK
                    </div>

                    <!-- Main Title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                        Suara Siswa,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700">Arah Perubahan</span><br>
                        Sekolah
                    </h1>

                    <!-- Description -->
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-normal">
                        Wadah resmi penampungan aspirasi seluruh siswa, transparansi pengawasan kinerja 10 Seksi Bidang OSIS, dan evaluasi program kerja secara terstruktur demi kemajuan sekolah.
                    </p>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="#aspirasi" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all text-base">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                            <span>Kirim Aspirasi Sekarang</span>
                        </a>
                        <a href="#progja" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-xs hover:border-slate-300 transition-all text-base">
                            <i class="fa-solid fa-list-check text-slate-400"></i>
                            <span>Lihat Program Kerja</span>
                        </a>
                    </div>

                    <!-- Highlight Counters -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/70 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">10</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">Seksi Bidang OSIS</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 tracking-tight">100+</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">Aspirasi Terkelola</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">95%</p>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">Disiplin & Terlaksana</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual Mockup / Floating Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Glow behind card -->
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl blur-xl opacity-20"></div>

                        <!-- Card Container -->
                        <div class="relative bg-white/90 backdrop-blur-xl border border-slate-200/80 rounded-2xl p-6 shadow-2xl space-y-5">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                        <i class="fa-solid fa-chart-pie"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Pemantauan Kinerja Sekbid</h3>
                                        <p class="text-xs text-slate-400">Periode Evaluasi Aktif</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Aktif
                                </span>
                            </div>

                            <!-- List of Evaluated Sekbids -->
                            <div class="space-y-3">
                                <!-- Item 1 -->
                                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-between hover:bg-blue-50/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                            01
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Sekbid 1: Ketaqwaan</p>
                                            <p class="text-[11px] text-slate-500">Piket RO & PHBI</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 font-bold text-xs border border-emerald-200">
                                        Sangat Baik
                                    </span>
                                </div>

                                <!-- Item 2 -->
                                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-between hover:bg-blue-50/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                                            02
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Sekbid 2: Budi Pekerti</p>
                                            <p class="text-[11px] text-slate-500">Keaktifan GDS Pagi</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 font-bold text-xs border border-blue-200">
                                        Sangat Baik
                                    </span>
                                </div>

                                <!-- Item 3 -->
                                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-between hover:bg-blue-50/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                            03
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Sekbid 3: Wawasan Kebangsaan</p>
                                            <p class="text-[11px] text-slate-500">Petugas Upacara & Bela Negara</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 font-bold text-xs border border-indigo-200">
                                        Baik
                                    </span>
                                </div>

                                <!-- Item 4 -->
                                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex items-center justify-between hover:bg-blue-50/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">
                                            04
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Sekbid 4: Prestasi & Seni</p>
                                            <p class="text-[11px] text-slate-500">Persiapan Classmeeting</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-600 font-bold text-xs border border-purple-200">
                                        Sangat Baik
                                    </span>
                                </div>
                            </div>

                            <!-- Bottom Mini Summary -->
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-blue-600"></i>
                                    Kompilasi Komisi B (Pengawasan)
                                </span>
                                <a href="#penilaian" class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                    Detail <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================== SECTION: SEKBID & PROGJA ==================== -->
    <section id="sekbid" class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                    Seksi Bidang OSIS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Mengenal Seksi Bidang & Program Kerja
                </h2>
                <p class="text-slate-600 text-base">
                    Pengawasan, koordinasi, dan pembinaan berkala terhadap 10 Seksi Bidang OSIS oleh Komisi Pengawasan MPK demi pencapaian program kerja yang optimal.
                </p>
            </div>

            <!-- Top Grid: 4 Highlight Sekbid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Sekbid 1 -->
                <div class="group p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hands-praying"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Sekbid 1</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1 mb-2">Ketaqwaan terhadap Tuhan YME</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Membina toleransi dan pelaksanaan ibadah bersama, perayaan hari besar keagamaan, serta kajian rohani.
                    </p>
                </div>

                <!-- Sekbid 2 -->
                <div class="group p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-heart-circle-check"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Sekbid 2</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1 mb-2">Budi Pekerti Luhur & Karakter</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pengawalan Gerakan Disiplin Sekolah (GDS), pembiasaan 5S (Senyum, Salam, Sapa, Sopan, Santun).
                    </p>
                </div>

                <!-- Sekbid 3 -->
                <div class="group p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flag"></i>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Sekbid 3</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1 mb-2">Wawasan Kebangsaan & Bela Negara</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pelaksanaan upacara bendera, pembinaan kepemimpinan (LDKS), dan penanaman jiwa patriotisme.
                    </p>
                </div>

                <!-- Sekbid 4 -->
                <div class="group p-6 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Sekbid 4</span>
                    <h3 class="text-base font-bold text-slate-900 mt-1 mb-2">Prestasi Akademik, Seni & Olahraga</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Fasilitasi kompetisi akademik, pentas seni sekolah, pekan olahraga, dan ajang kreativitas siswa.
                    </p>
                </div>
            </div>

            <!-- Middle Feature Banners -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white relative overflow-hidden shadow-xl shadow-blue-900/10">
                    <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl text-blue-200 mb-6 border border-white/20">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 tracking-tight">Pengawasan Terstruktur & Berkelanjutan</h3>
                    <p class="text-sm text-blue-100/90 leading-relaxed mb-6 font-normal">
                        MPK secara rutin melakukan monitoring terhadap kehadiran piket Ruang OSIS (RO), ketertiban petugas GDS, dan kesesuaian eksekusi proposal program kerja dengan realisasi di lapangan.
                    </p>
                    <div class="flex items-center gap-6 text-xs font-semibold text-blue-200">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-blue-300"></i> Absensi Digital</span>
                        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-blue-300"></i> Rekapitulasi Mingguan</span>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-gradient-to-br from-amber-600 via-amber-700 to-orange-800 text-white relative overflow-hidden shadow-xl shadow-amber-900/10">
                    <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl text-amber-200 mb-6 border border-white/20">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 tracking-tight">5 Aspek Standar Mutu Penilaian</h3>
                    <p class="text-sm text-amber-100/90 leading-relaxed mb-6 font-normal">
                        Setiap anggota dan seksi bidang dinilai secara objektif berdasarkan lima pilar: Keaktifan GDS, Kedisiplinan Piket RO, Kekompakan Tim, Efektivitas Komunikasi, dan Keberhasilan Progja.
                    </p>
                    <div class="flex items-center gap-6 text-xs font-semibold text-amber-200">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Berbasis Indikator</span>
                        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Umpan Balik Terbuka</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Metric Bar -->
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center divide-y lg:divide-y-0 lg:divide-x divide-slate-200">
                    <div class="p-2">
                        <p class="text-3xl font-black text-slate-900 tracking-tight">10</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Total Seksi Bidang</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-black text-blue-600 tracking-tight">92%</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Progja Terlaksana</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-black text-indigo-600 tracking-tight">150+</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Aspirasi Tersalurkan</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-black text-emerald-600 tracking-tight">A</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Predikat Evaluasi Umum</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================== SECTION: LAPORAN & PENILAIAN ==================== -->
    <section id="penilaian" class="py-20 bg-slate-50/60 border-t border-slate-200/70" x-data="{ activeFilter: 'semua' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Filter Tabs -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div class="space-y-2 max-w-xl">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-100/60 px-3 py-1 rounded-full border border-blue-200">
                        Transparansi Kerja
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Laporan & Publikasi Penilaian Sekbid
                    </h2>
                    <p class="text-slate-600 text-sm">
                        Publikasi hasil pemantauan program kerja dan evaluasi kualitas pelaksanaan kegiatan oleh MPK.
                    </p>
                </div>

                <!-- Tabs -->
                <div class="flex items-center gap-1.5 p-1.5 rounded-xl bg-white border border-slate-200 shadow-xs self-start md:self-auto">
                    <button @click="activeFilter = 'semua'" 
                            :class="activeFilter === 'semua' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-lg text-xs transition-all">
                        Semua
                    </button>
                    <button @click="activeFilter = 'terlaksana'" 
                            :class="activeFilter === 'terlaksana' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-lg text-xs transition-all">
                        Terlaksana
                    </button>
                    <button @click="activeFilter = 'sedang_berjalan'" 
                            :class="activeFilter === 'sedang_berjalan' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-lg text-xs transition-all">
                        Sedang Berjalan
                    </button>
                </div>
            </div>

            <!-- Cards Grid: 6 Program Kerja Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                                Sekbid 1
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Terlaksana
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Peringatan Hari Besar Islam (PHBI)</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Peringatan Maulid Nabi dan kajian akbar yang dihadiri oleh seluruh siswa, guru, dan komite sekolah.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> September 2026</span>
                        <span class="font-bold text-emerald-600">Nilai: Sangat Baik</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'sedang_berjalan'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                                Sekbid 3
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fa-solid fa-spinner text-[10px] animate-spin"></i> Berjalan
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Latihan Dasar Kepemimpinan (LDK)</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Penggemblengan mental dan wawasan manajemen organisasi bagi calon pengurus OSIS dan MPK periode baru.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> Sedang Berlangsung</span>
                        <span class="font-bold text-blue-600">Nilai: Baik</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100">
                                Sekbid 4
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Terlaksana
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Pekan Olahraga & Seni (Porseni)</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Turnamen futsal, basket, tari kreasi, dan vokal antarkelas yang berjalan lancar dan suportif.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> Agustus 2026</span>
                        <span class="font-bold text-emerald-600">Nilai: Sangat Baik</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100">
                                Sekbid 8
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Terlaksana
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Bakti Sosial & Gerakan Sekolah Hijau</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Pembagian 200 paket sembako kepada masyarakat prasejahtera dan pemilahan bank sampah sekolah.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> Juli 2026</span>
                        <span class="font-bold text-emerald-600">Nilai: Sangat Baik</span>
                    </div>
                </div>

                <!-- Card 5 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'terlaksana'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-md border border-cyan-100">
                                Sekbid 9
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Terlaksana
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Workshop Literasi Digital & Konten</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Pelatihan videografi, copywriting, dan etika bermedia sosial untuk seluruh perwakilan kelas.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> Juni 2026</span>
                        <span class="font-bold text-blue-600">Nilai: Baik</span>
                    </div>
                </div>

                <!-- Card 6 -->
                <div x-show="activeFilter === 'semua' || activeFilter === 'sedang_berjalan'"
                     class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">
                                Sekbid 10
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fa-solid fa-spinner text-[10px] animate-spin"></i> Berjalan
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">English Club & Gelar Wicara</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Program penguatan speaking dan debat bahasa Inggris mingguan di perpustakaan sekolah.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar text-[11px] mr-1"></i> Rutin Setiap Rabu</span>
                        <span class="font-bold text-blue-600">Nilai: Baik</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================== SECTION: FORM ASPIRASI ==================== -->
    <section id="aspirasi" class="py-20 bg-white border-t border-slate-200/70" x-data="{ isAnonim: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Left: Form Aspirasi Siswa -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl shadow-slate-200/50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Sampaikan Aspirasi</h2>
                            <p class="text-xs sm:text-sm text-slate-500">Suara Anda menentukan kemajuan sekolah kita bersama.</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('aspirasi.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Nama Pengirim & Anonim Toggle -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="nama_pengirim" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Nama Pengirim / Kelas
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="is_anonim" value="1" x-model="isAnonim" class="w-4 h-4 text-blue-600 rounded-sm border-slate-300 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-600">Kirim sebagai Anonim</span>
                                </label>
                            </div>
                            <input type="text" id="nama_pengirim" name="nama_pengirim" 
                                   :disabled="isAnonim"
                                   :placeholder="isAnonim ? 'Identitas Anda disembunyikan (Anonim)' : 'Contoh: Ahmad Dhani (XI PPLG 2)'"
                                   :class="isAnonim ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-slate-50 text-slate-900'"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden">
                        </div>

                        <!-- Judul Aspirasi -->
                        <div class="space-y-1.5">
                            <label for="judul_aspirasi" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Judul Aspirasi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="judul_aspirasi" name="judul_aspirasi" required
                                   placeholder="Contoh: Pengadaan Stopkontak Tambahan di Ruang Kelas"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden">
                        </div>

                        <!-- Kategori Aspirasi -->
                        <div class="space-y-1.5">
                            <label for="kode_kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Kategori Aspirasi <span class="text-rose-500">*</span>
                            </label>
                            <select id="kode_kategori" name="kode_kategori" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden">
                                <option value="" disabled selected>Pilih Kategori Aspirasi...</option>
                                @forelse($kategoris as $kategori)
                                    <option value="{{ $kategori->kode_kategori }}">{{ $kategori->nama_kategori }} ({{ $kategori->kode_kategori }})</option>
                                @empty
                                    <option value="SARPRAS">Sarana & Prasarana Sekolah</option>
                                    <option value="KEGIATAN">Kegiatan & Acara Siswa</option>
                                    <option value="AKADEMIK">Kurikulum & Pembelajaran</option>
                                    <option value="KEDISIPLINAN">Tata Tertib & Kedisiplinan</option>
                                    <option value="LAINNYA">Aspirasi Umum / Lainnya</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Deskripsi Aspirasi -->
                        <div class="space-y-1.5">
                            <label for="deskripsi_aspirasi" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Rincian / Penjelasan Aspirasi <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="deskripsi_aspirasi" name="deskripsi_aspirasi" rows="4" required
                                      placeholder="Jelaskan kendala, lokasi, saran, atau usulan solusi Anda secara santun dan jelas..."
                                      class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden resize-none"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full py-4 px-6 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/25 hover:shadow-blue-600/35 transition-all text-sm flex items-center justify-center gap-2 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Kirim Aspirasi Sekarang</span>
                            </button>
                            <p class="text-center text-[11px] text-slate-400 mt-2.5">
                                <i class="fa-solid fa-lock text-[10px] mr-1"></i> Data Anda dienkripsi dan diproses secara aman oleh Komisi Aspirasi MPK.
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Right: Alur & Feed Aspirasi -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Card 1: Alur Penanganan -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-8 shadow-xl">
                        <h3 class="text-lg font-bold mb-1">Mekanisme Tindak Lanjut</h3>
                        <p class="text-xs text-slate-400 mb-6">Bagaimana aspirasi Anda diproses hingga membuahkan solusi?</p>

                        <div class="space-y-4">
                            <!-- Step 1 -->
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    1
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">Aspirasi Diterima & Verifikasi</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">MPK mengkaji urgensi dan relevansi aspirasi yang masuk.</p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    2
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">Musyawarah & Advokasi</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Komisi A menyampaikan dalam rapat komisi bersama OSIS atau Pembina.</p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    3
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">Rekomendasi & Eksekusi</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Pihak sekolah/OSIS menindaklanjuti perbaikan di lapangan.</p>
                                </div>
                            </div>
                            <!-- Step 4 -->
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">Publikasi Solusi</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Status diperbarui menjadi 'Selesai' pada papan transparansi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Aspirasi Terkini -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">Aspirasi Terkini</h3>
                            <span class="text-[11px] font-semibold text-blue-600">Status Terbuka</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($recentAspirasi as $item)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-bold text-slate-500">{{ $item->nama_pengirim }}</span>
                                        @if($item->status_aspirasi === 'selesai')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Selesai
                                            </span>
                                        @elseif($item->status_aspirasi === 'proses')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                Diproses
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Menunggu
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item->judul_aspirasi }}</p>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $item->deskripsi_aspirasi }}</p>
                                </div>
                            @empty
                                <div class="p-4 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                                    Belum ada aspirasi terdaftar.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================== SECTION: STRUKTUR & VISI MISI ==================== -->
    <section id="visimisi" class="py-20 bg-slate-50/70 border-t border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-100/60 px-3 py-1 rounded-full border border-blue-200">
                    Organisasi & Visi
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Struktur Kepengurusan & Komisi MPK
                </h2>
                <p class="text-slate-600 text-sm">
                    Masa bakti kepengurusan Majelis Perwakilan Kelas dengan integritas tinggi dan dedikasi penuh.
                </p>
            </div>

            <!-- Visi & Misi Box -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm mb-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-5 space-y-4 lg:border-r lg:border-slate-100 lg:pr-8">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Visi Utama</span>
                        <h3 class="text-2xl font-extrabold text-slate-900 leading-snug">
                            "Mewujudkan MPK sebagai lembaga aspiratif, transparan, dan berintegritas demi kemajuan sekolah."
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Mewakili setiap aspirasi siswa secara independen untuk menciptakan iklim belajar yang inklusif dan kondusif.
                        </p>
                    </div>

                    <div class="lg:col-span-7 space-y-3 lg:pl-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Misi Organisasi</span>
                        <div class="space-y-2.5 text-xs text-slate-600">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5 shrink-0"></i>
                                <span>Menampung, mengawal, dan memperjuangkan seluruh aspirasi siswa secara transparan dan solutif.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5 shrink-0"></i>
                                <span>Melaksanakan pengawasan objektif terhadap 10 Seksi Bidang OSIS secara terstruktur dan terukur.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5 shrink-0"></i>
                                <span>Menjadi jembatan komunikasi yang harmonis antara siswa, pembina, dan jajaran manajemen sekolah.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-check text-blue-600 mt-0.5 shrink-0"></i>
                                <span>Menegakkan tata tertib dan memupuk budaya kepemimpinan berakhlak mulia.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Core Leaders Avatars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Ketua -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 text-center space-y-4 hover:shadow-md transition-all">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">M. Rafi Pratama</h4>
                        <p class="text-xs text-blue-600 font-semibold">Ketua Umum MPK</p>
                        <p class="text-[11px] text-slate-400 mt-1">Koordinator Umum & Kebijakan</p>
                    </div>
                </div>

                <!-- Wakil Ketua -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 text-center space-y-4 hover:shadow-md transition-all">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg shadow-indigo-500/20">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Aulia Syahrani</h4>
                        <p class="text-xs text-indigo-600 font-semibold">Wakil Ketua MPK</p>
                        <p class="text-[11px] text-slate-400 mt-1">Internal Organisasi & Disiplin</p>
                    </div>
                </div>

                <!-- Sekretaris -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 text-center space-y-4 hover:shadow-md transition-all">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-cyan-600 to-blue-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg shadow-cyan-500/20">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Fathir Nugraha</h4>
                        <p class="text-xs text-cyan-600 font-semibold">Sekretaris Umum</p>
                        <p class="text-[11px] text-slate-400 mt-1">Administrasi & Notulensi Sidang</p>
                    </div>
                </div>

                <!-- Bendahara -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 text-center space-y-4 hover:shadow-md transition-all">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg shadow-amber-500/20">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Siti Maulida</h4>
                        <p class="text-xs text-amber-600 font-semibold">Bendahara Umum</p>
                        <p class="text-[11px] text-slate-400 mt-1">Manajemen Anggaran & Kas</p>
                    </div>
                </div>
            </div>

            <!-- 4 Komisi MPK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="p-4 rounded-xl bg-white border border-slate-200/90 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        A
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Komisi A</h5>
                        <p class="text-[11px] text-slate-500">Aspirasi, Advokasi & Humas</p>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/90 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        B
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Komisi B</h5>
                        <p class="text-[11px] text-slate-500">Pengawasan Sekbid 1 - 5</p>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/90 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        C
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Komisi C</h5>
                        <p class="text-[11px] text-slate-500">Pengawasan Sekbid 6 - 10</p>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-slate-200/90 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        D
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Komisi D</h5>
                        <p class="text-[11px] text-slate-500">Hukum, Disiplin & Tata Tertib</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

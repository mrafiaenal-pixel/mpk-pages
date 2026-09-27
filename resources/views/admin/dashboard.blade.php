@extends('layout.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Welcome Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ikhtisar Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau kinerja seksi bidang OSIS dan respon aspirasi siswa secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.penilaian') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Input Penilaian Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 STAT CARDS (TOP ROW) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Sekbid</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalSekbid }}</p>
                <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> 10 Seksi Bidang Aktif
                </p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi Masuk</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-inbox"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalAspirasi }}</p>
                <p class="text-xs text-blue-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-arrow-up text-[10px]"></i> {{ $aspirasiMenunggu }} Menunggu Ditinjau
                </p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Indeks Kinerja</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">A <span class="text-sm font-medium text-slate-400">/ 94%</span></p>
                <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-star text-[10px]"></i> Predikat Sangat Baik
                </p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Progja Terlaksana</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $persentaseProgja }}%</p>
                <p class="text-xs text-amber-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-clock-rotate-left text-[10px]"></i> Dari {{ $totalProgja }} Program Kerja
                </p>
            </div>
        </div>
    </div>

    <!-- CHARTS SECTION (MIDDLE ROW) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Area Chart: Tren Aktivitas & Aspirasi (8 cols) -->
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Tren Aspirasi & Evaluasi Mingguan</h3>
                    <p class="text-xs text-slate-400">Grafik pergerakan aspirasi masuk dan evaluasi progja</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600">
                    Bulan Berjalan
                </span>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="lineChartAktivitas"></canvas>
            </div>
        </div>

        <!-- Donut Chart & Aspek Breakdown (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Distribusi Status Aspirasi</h3>
                <p class="text-xs text-slate-400 mb-4">Perbandingan status tindak lanjut</p>
                <div class="h-44 relative flex items-center justify-center">
                    <canvas id="donutChartAspirasi"></canvas>
                </div>
            </div>

            <!-- Progress Bars for 5 Aspek Kinerja -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-700">Rata-Rata 5 Aspek Penilaian:</p>

                <!-- Aspek 1: GDS -->
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] font-semibold">
                        <span class="text-slate-600">Keaktifan GDS Pagi</span>
                        <span class="text-blue-600">96%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: 96%"></div>
                    </div>
                </div>

                <!-- Aspek 2: Piket RO -->
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] font-semibold">
                        <span class="text-slate-600">Kedisiplinan Piket RO</span>
                        <span class="text-emerald-600">92%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: 92%"></div>
                    </div>
                </div>

                <!-- Aspek 3: Kekompakan -->
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] font-semibold">
                        <span class="text-slate-600">Kekompakan Tim Sekbid</span>
                        <span class="text-indigo-600">90%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 90%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM ROW: RECENT PENILAIAN TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Rekap Penilaian Kinerja Terbaru</h3>
                <p class="text-xs text-slate-500">Evaluasi berkala terhadap perwakilan dan anggota Seksi Bidang OSIS.</p>
            </div>
            <a href="{{ route('admin.penilaian') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 self-start sm:self-auto">
                Lihat Semua Penilaian <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Anggota Sekbid</th>
                        <th class="py-3.5 px-6">Seksi Bidang</th>
                        <th class="py-3.5 px-6">Keaktifan GDS</th>
                        <th class="py-3.5 px-6">Piket RO</th>
                        <th class="py-3.5 px-6">Kekompakan</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6 text-right">Penilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentPenilaian as $penilaian)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($penilaian->anggotaSekbid->nama_anggota ?? 'A', 0, 1)) }}
                                    </div>
                                    <span>{{ $penilaian->anggotaSekbid->nama_anggota ?? 'Anggota Sekbid' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $penilaian->anggotaSekbid->division->nama_sekbid ?? 'Sekbid OSIS' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-emerald-600">{{ $penilaian->aspect->keaktifan_gds ?? 'Baik' }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $penilaian->aspect->piket_ro ?? 'Baik' }}
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $penilaian->aspect->kekompakan ?? 'Sangat Baik' }}
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $penilaian->aspect ? \Carbon\Carbon::parse($penilaian->aspect->tanggal_penilaian)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right font-medium text-slate-600">
                                {{ $penilaian->user->username ?? 'MPK' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada data penilaian tersimpan. Silakan input penilaian kinerja pertama Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line/Area Chart Aktivitas
        const ctxLine = document.getElementById('lineChartAktivitas').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'],
                datasets: [
                    {
                        label: 'Aspirasi Masuk',
                        data: [12, 19, 15, 25, 22],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Evaluasi Progja',
                        data: [8, 14, 18, 16, 20],
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Donut Chart Status Aspirasi
        const ctxDonut = document.getElementById('donutChartAspirasi').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Diproses', 'Selesai'],
                datasets: [{
                    data: [{{ $aspirasiMenunggu ?: 3 }}, {{ $aspirasiProses ?: 5 }}, {{ $aspirasiSelesai ?: 12 }}],
                    backgroundColor: ['#f59e0b', '#2563eb', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush

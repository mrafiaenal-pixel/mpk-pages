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
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ number_format($rataNilai, 2) }}<span class="text-sm font-medium text-slate-400">/100</span>
                </p>
                <p class="text-xs font-semibold flex items-center gap-1 mt-1 {{ \App\Services\PenilaiSkor::warnaPredikat($predikat) }} px-2 py-0.5 rounded-full inline-flex w-fit">
                    <i class="fa-solid fa-star text-[10px]"></i> {{ $predikat }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1.5">Dari {{ $totalPenilaian }} data penilaian</p>
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
                    <h3 class="text-sm font-bold text-slate-900">Tren Penilaian Kinerja</h3>
                    <p class="text-xs text-slate-400">Rata-rata nilai dan jumlah penilaian 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600">
                    6 Bulan Terakhir
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

            <!-- Rata-rata aspek dari data penilaian nyata -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-700">Rata-rata Aspek Penilaian:</p>

                @php $warnaAspek = ['bg-blue-600 text-blue-600', 'bg-emerald-600 text-emerald-600', 'bg-indigo-600 text-indigo-600']; @endphp

                @foreach($rekapAspek->take(3) as $index => $aspek)
                    <div class="space-y-1">
                        <div class="flex justify-between text-[11px] font-semibold">
                            <span class="text-slate-600">{{ $aspek['label'] }}</span>
                            <span class="{{ $warnaAspek[$index % 3] }}">{{ number_format($aspek['rata'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $warnaAspek[$index % 3] }} h-1.5 rounded-full" style="width: {{ min(100, $aspek['rata']) }}%"></div>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('admin.rekap') }}" class="block text-center text-[11px] font-bold text-blue-600 hover:text-blue-700 pt-1">
                    Lihat Rekap Visual Lengkap <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
            </div>

            <!-- Distribusi Predikat -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-700">Distribusi Predikat Kinerja</p>
                <div class="h-44 relative flex items-center justify-center">
                    <canvas id="donutChartPredikat"></canvas>
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
                        <th class="py-3.5 px-6">Nilai</th>
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
                                        {{ strtoupper(substr($penilaian->divisionMember?->nama_anggota ?? 'A', 0, 1)) }}
                                    </div>
                                    <span>{{ $penilaian->divisionMember?->nama_anggota ?? 'Anggota Sekbid' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $penilaian->divisionMember?->division?->nama_sekbid ?? 'Sekbid OSIS' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-emerald-600">{{ $penilaian->aspect?->keaktifan_gds ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-700">{{ $penilaian->aspect?->piket_ro ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $penilaian->aspect?->kekompakan ?? '-' }}</td>
                            <td class="py-4 px-6 font-black text-slate-900">
                                {{ $penilaian->aspect?->nilai !== null ? number_format($penilaian->aspect->nilai, 2) : '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $penilaian->aspect?->tanggal_penilaian?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-right font-medium text-slate-600">
                                {{ $penilaian->user?->username ?? 'MPK' }}
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
        // Tren nyata: rata-rata nilai & jumlah penilaian per bulan (6 bulan terakhir)
        const trenLabels = {!! json_encode($trenBulanan->pluck('periode')->values()) !!};
        const trenRata = {!! json_encode($trenBulanan->pluck('rata_nilai')->map(fn($n) => $n ?? 0)->values()) !!};
        const trenJumlah = {!! json_encode($trenBulanan->pluck('jumlah')->values()) !!};

        const ctxLine = document.getElementById('lineChartAktivitas').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [
                    {
                        label: 'Rata-rata Nilai',
                        data: trenRata,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Penilaian',
                        data: trenJumlah,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        yAxisID: 'y1'
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
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, title: { display: true, text: 'Nilai', font: { size: 10 } } },
                    y1: { position: 'right', beginAtZero: true, grid: { display: false }, title: { display: true, text: 'Jumlah', font: { size: 10 } } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Donut Chart Status Aspirasi (angka asli, tanpa data palsu)
        const ctxDonut = document.getElementById('donutChartAspirasi').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Diproses', 'Selesai'],
                datasets: [{
                    data: [{{ $aspirasiMenunggu }}, {{ $aspirasiProses }}, {{ $aspirasiSelesai }}].map((v) => Number(v) || 0),
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

        // Donut Chart Distribusi Predikat
        const distLabels = {!! json_encode($distribusiPredikat->map(fn($d) => $d['label'])->values()) !!};
        const distData = {!! json_encode($distribusiPredikat->map(fn($d) => $d['jumlah'])->values()) !!};
        const warnaPredikat = { 'Sangat Baik': '#10b981', 'Baik': '#2563eb', 'Cukup': '#f59e0b', 'Perlu Pembinaan': '#c2410c', 'Tidak Memadai': '#dc2626' };
        const ctxPredikat = document.getElementById('donutChartPredikat').getContext('2d');
        new Chart(ctxPredikat, {
            type: 'doughnut',
            data: {
                labels: distLabels,
                datasets: [{
                    data: distData.map((v) => Number(v) || 0),
                    backgroundColor: distLabels.map((l) => warnaPredikat[l] ?? '#94a3b8'),
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

@extends('layout.admin')

@section('title', 'Rekap Visual Penilaian')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekap Visual Penilaian OSIS</h1>
            <p class="text-xs text-slate-500 mt-1">Rata-rata nilai seluruh input penilaian, dikelompokkan per sekbid, per aspek, dan per bulan.</p>
        </div>
        <a href="{{ route('admin.rekap.export', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
            <i class="fa-solid fa-file-excel text-emerald-600"></i>
            <span>Export Excel</span>
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.rekap') }}"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 grid grid-cols-1 sm:grid-cols-4 gap-3">
        <div class="sm:col-span-2">
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Divisi / Sekbid</label>
            <select name="divisi" onchange="this.form.submit()"
                    @disabled(auth()->user()->isDivisionScoped())
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white disabled:opacity-60">
                <option value="">Semua Divisi</option>
                @foreach($divisiList as $divisi)
                    <option value="{{ $divisi->id }}" @selected(($filterAktif['divisi'] ?? null) == $divisi->id)>{{ $divisi->nama_sekbid }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $filterAktif['dari'] ?? '' }}"
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $filterAktif['sampai'] ?? '' }}"
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>
        <div class="sm:col-span-4 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs">
                <i class="fa-solid fa-filter mr-1"></i>Terapkan
            </button>
            @if(count($filterAktif) > 0)
                <a href="{{ route('admin.rekap') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Reset</a>
            @endif
            <span class="text-[11px] text-slate-400 font-medium">Total {{ $totalPenilaian }} data penilaian dalam rentang ini.</span>
        </div>
    </form>

    @if($totalPenilaian === 0)
        <div class="p-10 text-center bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <i class="fa-solid fa-chart-column text-3xl text-slate-300"></i>
            <p class="text-sm font-bold text-slate-700 mt-3">Tidak ada data penilaian untuk ditampilkan</p>
            <p class="text-xs text-slate-400 mt-1">Coba ubah filter divisi atau rentang tanggal.</p>
        </div>
    @else
        <!-- Kartu Ringkasan -->
        @php $rataKeseluruhan = round($statistikSekbid->avg('rata_nilai'), 2); @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Umum</p>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ number_format($rataKeseluruhan, 2) }}</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border mt-2 {{ \App\Services\PenilaiSkor::warnaPredikat(\App\Services\PenilaiSkor::predikat($rataKeseluruhan)) }}">
                    {{ \App\Services\PenilaiSkor::predikat($rataKeseluruhan) }}
                </span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sekbid Terbaik</p>
                <p class="text-sm font-black text-slate-900 mt-2 leading-tight">{{ $statistikSekbid->first()['nama'] ?? '-' }}</p>
                <p class="text-xs text-emerald-600 font-bold mt-1">{{ number_format($statistikSekbid->first()['rata_nilai'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sekbid Terendah</p>
                <p class="text-sm font-black text-slate-900 mt-2 leading-tight">{{ $statistikSekbid->last()['nama'] ?? '-' }}</p>
                <p class="text-xs text-rose-600 font-bold mt-1">{{ number_format($statistikSekbid->last()['rata_nilai'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Aspek Terlemah</p>
                <p class="text-sm font-black text-slate-900 mt-2 leading-tight">{{ $statistikAspek->sortBy('rata')->first()['label'] ?? '-' }}</p>
                <p class="text-xs text-orange-600 font-bold mt-1">{{ number_format($statistikAspek->sortBy('rata')->first()['rata'] ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Grafik -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-sm font-extrabold text-slate-900">Rata-rata Nilai per Seksi Bidang</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 mb-4">Skala 0–100</p>
                <div class="h-80"><canvas id="chartSekbid"></canvas></div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-sm font-extrabold text-slate-900">Profil Rata-rata per Aspek</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 mb-4">Normalisasi ke skala 0–100 agar sebanding</p>
                <div class="h-80"><canvas id="chartAspek"></canvas></div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 xl:col-span-2">
                <h3 class="text-sm font-extrabold text-slate-900">Tren Rata-rata Nilai Bulanan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 mb-4">Perkembangan kinerja OSIS dari waktu ke waktu</p>
                <div class="h-72"><canvas id="chartTren"></canvas></div>
            </div>
        </div>

        <!-- Tabel Peringkat Sekbid -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Peringkat Kinerja per Seksi Bidang</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Diurutkan dari rata-rata nilai tertinggi</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Peringkat</th>
                            <th class="py-3.5 px-6">Seksi Bidang</th>
                            <th class="py-3.5 px-6">Anggota Dinilai</th>
                            <th class="py-3.5 px-6">Jumlah Penilaian</th>
                            <th class="py-3.5 px-6">Rata-rata</th>
                            <th class="py-3.5 px-6">Tertinggi</th>
                            <th class="py-3.5 px-6">Terendah</th>
                            <th class="py-3.5 px-6">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($statistikSekbid as $index => $row)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[11px]
                                        {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-slate-200 text-slate-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-50 text-slate-400')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $row['nama'] }}</td>
                                <td class="py-4 px-6 text-slate-600">{{ $row['jumlah_anggota'] }}</td>
                                <td class="py-4 px-6 text-slate-600">{{ $row['jumlah_penilaian'] }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(100, $row['rata_nilai']) }}%"></div>
                                        </div>
                                        <span class="font-black text-slate-900">{{ number_format($row['rata_nilai'], 2) }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-emerald-600 font-bold">{{ number_format($row['tertinggi'], 2) }}</td>
                                <td class="py-4 px-6 text-rose-600 font-bold">{{ number_format($row['terendah'], 2) }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ \App\Services\PenilaiSkor::warnaPredikat($row['predikat']) }}">
                                        {{ $row['predikat'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Anggota Teratas -->
        @if($anggotaTeratas->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900">5 Anggota dengan Rata-rata Tertinggi</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Individu dengan rata-rata penilaian tertinggi</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 p-5">
                    @foreach($anggotaTeratas as $index => $row)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-black text-sm mb-3">
                                {{ strtoupper(substr($row['nama'], 0, 1)) }}
                            </div>
                            <p class="text-xs font-bold text-slate-900 leading-tight">{{ $row['nama'] }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-2">{{ $row['divisi'] }}</p>
                            <p class="text-lg font-black text-blue-600 mt-2">{{ number_format($row['rata_nilai'], 2) }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ \App\Services\PenilaiSkor::warnaPredikat($row['predikat']) }}">
                                {{ $row['predikat'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
    @if($totalPenilaian > 0)
    const palette = ['#2563eb','#7c3aed','#0891b2','#059669','#d97706','#dc2626','#db2777','#4f46e5','#0d9488','#65a30d','#ea580c','#9333ea'];

    // Rata-rata nilai per sekbid
    new Chart(document.getElementById('chartSekbid'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($statistikSekbid->pluck('nama')->map(fn($n) => \Illuminate\Support\Str::before($n, ':'))->values()) !!},
            datasets: [{
                label: 'Rata-rata Nilai',
                data: {!! json_encode($statistikSekbid->pluck('rata_nilai')->values()) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.75)',
                borderRadius: 6,
                maxBarThickness: 48,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, max: 100, ticks: { font: { size: 10 } } },
                y: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // Profil per aspek (radar)
    new Chart(document.getElementById('chartAspek'), {
        type: 'radar',
        data: {
            labels: {!! json_encode($statistikAspek->pluck('label')->values()) !!},
            datasets: [{
                label: 'Rata-rata Aspek',
                data: {!! json_encode($statistikAspek->pluck('rata')->values()) !!},
                backgroundColor: 'rgba(124, 58, 237, 0.2)',
                borderColor: '#7c3aed',
                pointBackgroundColor: '#7c3aed',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 25, font: { size: 9 }, backdropColor: 'transparent' },
                    pointLabels: { font: { size: 11, weight: '600' } }
                }
            }
        }
    });

    // Tren bulanan
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenBulanan->pluck('periode')->values()) !!},
            datasets: [{
                label: 'Rata-rata Nilai Bulanan',
                data: {!! json_encode($trenBulanan->pluck('rata_nilai')->values()) !!},
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#059669',
                borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });
    @endif
</script>
@endpush
@endsection
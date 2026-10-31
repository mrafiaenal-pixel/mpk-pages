@extends('layout.admin')

@section('title', 'Laporan Kegiatan OSIS / MPK')

@section('content')
    <div class="space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Kegiatan OSIS / MPK</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar seluruh program kerja OSIS / MPK beserta status pelaksanaannya.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.kegiatan.export.csv') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-file-csv text-xs text-emerald-600"></i>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('admin.kegiatan.export.pdf') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition-all">
                    <i class="fa-solid fa-file-pdf text-xs"></i>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>

        <!-- Flash & Errors -->
        @if (session('success'))
            <div
                class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3 text-xs font-semibold">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- STAT CARDS -->
        @php
            $totalKegiatan = $kegiatans->count();
            $totalRencana = $kegiatans->where('status_progja', 'rencana')->count();
            $totalBerjalan = $kegiatans->whereIn('status_progja', ['berjalan', 'sedang_berjalan'])->count();
            $totalTerlaksana = $kegiatans->whereIn('status_progja', ['terlaksana', 'selesai'])->count();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kegiatan</span>
                    <div
                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalKegiatan }}</p>
                    <p class="text-xs text-slate-500 font-semibold flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-database text-[10px]"></i> Seluruh program kerja
                    </p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rencana</span>
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalRencana }}</p>
                    <p class="text-xs text-indigo-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-hourglass-start text-[10px]"></i> Belum dijalankan
                    </p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Berjalan</span>
                    <div
                        class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalBerjalan }}</p>
                    <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-play text-[10px]"></i> Sedang dilaksanakan
                    </p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Terlaksana</span>
                    <div
                        class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalTerlaksana }}</p>
                    <p class="text-xs text-teal-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-flag-checkered text-[10px]"></i> Selesai
                    </p>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Filter Kegiatan</h3>
                <p class="text-xs text-slate-400">Saring data berdasarkan status dan nama kegiatan.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="statusFilter"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select id="statusFilter"
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="rencana">Rencana</option>
                        <option value="berjalan">Berjalan</option>
                        <option value="terlaksana">Terlaksana</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div>
                    <label for="searchFilter"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cari Nama
                        Kegiatan</label>
                    <input type="text" id="searchFilter" placeholder="Ketik nama kegiatan..."
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <button type="button" id="btnReset"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold text-xs transition-all">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- TABEL KEGIATAN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Daftar Program Kerja</h3>
                    <p class="text-xs text-slate-500">Seluruh kegiatan OSIS / MPK yang tercatat di sistem.</p>
                </div>
                <span id="counterKegiatan"
                    class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600">
                    {{ $totalKegiatan }} kegiatan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6 w-16">ID</th>
                            <th class="py-3.5 px-6">Nama Kegiatan</th>
                            <th class="py-3.5 px-6">Deskripsi</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody id="kegiatanTable" class="divide-y divide-slate-100">
                        @forelse($kegiatans as $row)
                            @php
                                $status = $row->status_progja ?? 'batal';
                                $badge = match ($status) {
                                    'rencana' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'berjalan',
                                    'sedang_berjalan'
                                        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'terlaksana', 'selesai' => 'bg-teal-50 text-teal-700 border-teal-200',
                                    default => 'bg-rose-50 text-rose-700 border-rose-200',
                                };
                                $label = ucfirst(str_replace('_', ' ', $status));
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors kegiatan-row"
                                data-status="{{ $status }}" data-nama="{{ strtolower($row->nama_progja) }}">
                                <td class="py-4 px-6 font-bold text-slate-500">#{{ $row->id }}</td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($row->nama_progja, 0, 1)) }}
                                        </div>
                                        <span>{{ $row->nama_progja }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $row->deskripsi_progja ? \Illuminate\Support\Str::limit($row->deskripsi_progja, 90) : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right text-slate-500">
                                    {{ $row->created_at ? $row->created_at->translatedFormat('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada data kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="noResultRow" class="hidden py-8 text-center text-slate-400 text-xs">
                Tidak ada kegiatan yang cocok dengan filter.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const statusFilter = document.getElementById('statusFilter');
            const searchFilter = document.getElementById('searchFilter');
            const btnReset = document.getElementById('btnReset');
            const rows = Array.from(document.querySelectorAll('.kegiatan-row'));
            const noResult = document.getElementById('noResultRow');
            const counter = document.getElementById('counterKegiatan');

            function applyFilter() {
                const status = statusFilter.value.toLowerCase();
                const q = searchFilter.value.toLowerCase().trim();
                let visible = 0;

                rows.forEach(row => {
                    const rowStatus = (row.dataset.status || '').toLowerCase();
                    const rowNama = (row.dataset.nama || '').toLowerCase();

                    let statusMatch = !status;
                    if (status === 'berjalan')
                        statusMatch = rowStatus === 'berjalan' || rowStatus === 'sedang_berjalan';
                    else if (status === 'terlaksana')
                        statusMatch = rowStatus === 'terlaksana' || rowStatus === 'selesai';
                    else if (status === 'batal')
                        statusMatch = !['rencana', 'berjalan', 'sedang_berjalan', 'terlaksana', 'selesai']
                        .includes(rowStatus);
                    else if (status)
                        statusMatch = rowStatus === status;

                    const searchMatch = !q || rowNama.includes(q);

                    if (statusMatch && searchMatch) {
                        row.classList.remove('hidden');
                        visible++;
                    } else {
                        row.classList.add('hidden');
                    }
                });

                noResult.classList.toggle('hidden', visible > 0 || rows.length === 0);
                counter.textContent = visible + ' kegiatan';
            }

            statusFilter.addEventListener('change', applyFilter);
            searchFilter.addEventListener('input', applyFilter);

            btnReset.addEventListener('click', () => {
                statusFilter.value = '';
                searchFilter.value = '';
                applyFilter();
            });
        })();
    </script>
@endpush

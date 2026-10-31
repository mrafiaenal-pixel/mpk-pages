@extends('layout.admin')

@section('title', 'Management Uang Kas')

@section('content')
    <div class="space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Management Uang Kas</h1>
                <p class="text-xs text-slate-500 mt-1">Catatan uang kas yang masuk dan keluar secara real-time.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bendahara.export') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all">
                    <i class="fa-solid fa-file-export text-xs"></i>
                    <span>Export Data</span>
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

        <!-- FILTER & RINGKASAN -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Filter Panel -->
            <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Filter Transaksi</h3>
                    <p class="text-xs text-slate-400">Saring data berdasarkan jenis dan rentang tanggal.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label for="tipeFilter"
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis</label>
                        <select id="tipeFilter"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label for="dariFilter"
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dari
                            Tanggal</label>
                        <input type="date" id="dariFilter"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <div>
                        <label for="sampaiFilter"
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sampai
                            Tanggal</label>
                        <input type="date" id="sampaiFilter"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="btnReset"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold text-xs transition-all">
                            <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Ringkasan Kas</h3>
                    <p class="text-xs text-slate-400">Berdasarkan filter aktif.</p>
                </div>
                <div class="space-y-2.5 pt-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Masuk
                        </span>
                        <span id="ringkasanMasuk" class="text-sm font-black text-emerald-600">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Keluar
                        </span>
                        <span id="ringkasanKeluar" class="text-sm font-black text-rose-600">-</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-700">Sisa Saldo</span>
                        <span id="ringkasanSisa" class="text-base font-black text-slate-900">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Riwayat Transaksi Uang Kas</h3>
                    <p class="text-xs text-slate-500">Daftar seluruh pemasukan dan pengeluaran yang tercatat.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Tanggal</th>
                            <th class="py-3.5 px-6">User</th>
                            <th class="py-3.5 px-6">Jenis</th>
                            <th class="py-3.5 px-6">Keterangan</th>
                            <th class="py-3.5 px-6 text-right">Nominal</th>
                            <th class="py-3.5 px-6 text-right">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody id="uangKasTable" class="divide-y divide-slate-100">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FORM TRANSAKSI BARU -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Catat Transaksi Baru</h3>
                    <p class="text-xs text-slate-400">Tambahkan pemasukan atau pengeluaran kas.</p>
                </div>

                <form id="formTransaksi" method="POST" action="{{ route('admin.bendahara.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis
                            Transaksi</label>
                        <select name="tipe"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('tipe') border-rose-400 @enderror">
                            <option value="masuk" @selected(old('tipe') === 'masuk')>Masuk</option>
                            <option value="keluar" @selected(old('tipe') === 'keluar')>Keluar</option>
                        </select>
                        @error('tipe')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal
                            (Rp)</label>
                        <input type="number" name="nominal" min="0" placeholder="0" value="{{ old('nominal') }}"
                            required
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('nominal') border-rose-400 @enderror">
                        @error('nominal')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Misal: Pendapatan jualan, Bayar listrik"
                            value="{{ old('keterangan') }}" required
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('keterangan') border-rose-400 @enderror">
                        @error('keterangan')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('tanggal') border-rose-400 @enderror">
                        @error('tanggal')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Transaksi</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const URL_INDEX = @json(route('admin.bendahara.index'));
            const URL_SUMMARY = @json(route('admin.bendahara.summary'));

            function rupiah(n) {
                const num = Number(n) || 0;
                return 'Rp ' + num.toLocaleString('id-ID');
            }

            function getFilters() {
                return new URLSearchParams({
                    tipe: document.getElementById('tipeFilter').value || '',
                    dari: document.getElementById('dariFilter').value || '',
                    sampai: document.getElementById('sampaiFilter').value || '',
                });
            }

            function updateRingkasan() {
                const params = getFilters();
                fetch(URL_SUMMARY + '?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('ringkasanMasuk').textContent = data.masuk ? rupiah(data.masuk) :
                            '-';
                        document.getElementById('ringkasanKeluar').textContent = data.keluar ? rupiah(data.keluar) :
                            '-';
                        document.getElementById('ringkasanSisa').textContent = (Number(data.sisa) >= 0) ?
                            rupiah(data.sisa) :
                            '-' + rupiah(Math.abs(data.sisa));
                    })
                    .catch(err => console.error('Gagal memuat ringkasan:', err));
            }

            function loadUangKas() {
                const params = getFilters();
                params.set('format', 'json');

                fetch(URL_INDEX + '?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const tbody = document.getElementById('uangKasTable');
                        tbody.innerHTML = '';
                        const rows = data.uangKas || data.data || [];

                        if (rows.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada transaksi.</td></tr>';
                            return;
                        }

                        rows.forEach(item => {
                            const isMasuk = item.tipe === 'masuk';
                            const badgeClass = isMasuk ?
                                'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                'bg-rose-50 text-rose-700 border border-rose-200';
                            const badgeIcon = isMasuk ? 'fa-arrow-down' : 'fa-arrow-up';
                            const username = (item.user && item.user.username) ? item.user.username :
                                'Anonim';

                            const tr = document.createElement('tr');
                            tr.className = 'hover:bg-slate-50/60 transition-colors';
                            tr.innerHTML = `
                        <td class="py-4 px-6 text-slate-600">${item.tanggal ?? '-'}</td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    ${username.charAt(0).toUpperCase()}
                                </div>
                                <span>${username}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold ${badgeClass}">
                                <i class="fa-solid ${badgeIcon} text-[9px]"></i>
                                ${isMasuk ? 'Masuk' : 'Keluar'}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-600">${item.keterangan ?? '-'}</td>
                        <td class="py-4 px-6 text-right font-black ${isMasuk ? 'text-emerald-600' : 'text-rose-600'}">
                            ${(isMasuk ? '+' : '-') + ' ' + rupiah(item.nominal)}
                        </td>
                        <td class="py-4 px-6 text-right text-slate-400 text-[11px]">${item.created_at ?? '-'}</td>
                    `;
                            tbody.appendChild(tr);
                        });
                    })
                    .catch(err => {
                        console.error('Gagal memuat uang kas:', err);
                        document.getElementById('uangKasTable').innerHTML =
                            '<tr><td colspan="6" class="py-8 text-center text-rose-500">Gagal memuat data.</td></tr>';
                    });
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadUangKas();
                updateRingkasan();

                ['tipeFilter', 'dariFilter', 'sampaiFilter'].forEach(id => {
                    document.getElementById(id).addEventListener('change', () => {
                        loadUangKas();
                        updateRingkasan();
                    });
                });

                document.getElementById('btnReset').addEventListener('click', () => {
                    document.getElementById('tipeFilter').value = '';
                    document.getElementById('dariFilter').value = '';
                    document.getElementById('sampaiFilter').value = '';
                    loadUangKas();
                    updateRingkasan();
                });
            });
        })();
    </script>
@endpush

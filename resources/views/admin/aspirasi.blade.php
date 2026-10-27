@extends('layout.admin')

@section('title', 'Manajemen Aspirasi')

@section('content')
<div class="space-y-6" x-data="{ modalId: null }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Aspirasi Siswa</h1>
            <p class="text-xs text-slate-500 mt-1">Triage, tulis tanggapan, dan pantau tindak lanjut aspirasi siswa.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.aspirasi.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Aspirasi</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $statistik['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Menunggu</p>
            <p class="text-2xl font-black text-amber-600 mt-1">{{ $statistik['menunggu'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Diproses</p>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $statistik['proses'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Belum Diberi Tanggapan</p>
            <p class="text-2xl font-black text-rose-600 mt-1">{{ $statistik['belum_ditangani'] }}</p>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.aspirasi') }}"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="lg:col-span-2">
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul, isi, atau nama pengirim..."
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
                <option value="">Semua Status</option>
                @foreach(['menunggu' => 'Menunggu', 'proses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Kategori</label>
            <select name="kategori" onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori->kode_kategori }}" @selected(request('kategori') === $kategori->kode_kategori)>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}"
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>

        <div class="sm:col-span-2 lg:col-span-5 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs">
                <i class="fa-solid fa-filter mr-1"></i>Terapkan
            </button>
            @if(request()->hasAny(['q','status','kategori','dari']))
                <a href="{{ route('admin.aspirasi') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Aspirasi -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="tabelAspirasi" class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-6">Pengirim</th>
                        <th class="py-3.5 px-6">Aspirasi</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Tanggapan</th>
                        <th class="py-3.5 px-6">Masuk</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aspirasis as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-400">#{{ $item->id_aspirasi }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $item->nama_pengirim }}</td>
                            <td class="py-4 px-6 max-w-xs">
                                <p class="font-bold text-slate-800">{{ $item->judul_aspirasi }}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $item->deskripsi_aspirasi }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $item->category->nama_kategori ?? $item->kode_kategori }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $warnaStatus = match ($item->status_aspirasi) {
                                        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $warnaStatus }}">
                                    {{ ucfirst($item->status_aspirasi) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 max-w-[16rem]">
                                @if(filled($item->tanggapan_mpk))
                                    <p class="text-slate-700 line-clamp-2">{{ $item->tanggapan_mpk }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        oleh {{ $item->penangan?->username ?? 'MPK' }} · {{ $item->updated_at?->translatedFormat('d M Y') }}
                                    </p>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Belum ditanggapi
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $item->created_at?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button @click="modalId = {{ $item->id_aspirasi }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold hover:bg-blue-100 transition-colors cursor-pointer">
                                    <i class="fa-solid fa-reply"></i> Tindak Lanjuti
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Tindak Lanjut -->
                        <div x-show="modalId === {{ $item->id_aspirasi }}" x-cloak
                             class="fixed inset-0 z-50 overflow-y-auto">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="modalId = null"></div>
                            <div class="flex min-h-full items-center justify-center p-4">
                                <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-base font-extrabold text-slate-900">{{ $item->judul_aspirasi }}</h3>
                                            <p class="text-[11px] text-slate-400 mt-1">
                                                #{{ $item->id_aspirasi }} · {{ $item->nama_pengirim }} · {{ $item->category->nama_kategori ?? $item->kode_kategori }}
                                            </p>
                                        </div>
                                        <button @click="modalId = null" class="p-2 text-slate-400 hover:text-slate-600 shrink-0">
                                            <i class="fa-solid fa-xmark text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-600 leading-relaxed">
                                        {{ $item->deskripsi_aspirasi }}
                                    </div>

                                    <form action="{{ route('admin.aspirasi.update', $item->id_aspirasi) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PATCH')

                                        <div class="space-y-1">
                                            <label class="block text-[11px] font-bold text-slate-700">Status Tindak Lanjut</label>
                                            <select name="status_aspirasi" required
                                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                                @foreach(['menunggu' => 'Menunggu', 'proses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $value => $label)
                                                    <option value="{{ $value }}" @selected($item->status_aspirasi === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block text-[11px] font-bold text-slate-700">Tanggapan MPK <span class="text-slate-400 font-normal">(diteruskan ke pengirim)</span></label>
                                            <textarea name="tanggapan_mpk" rows="4"
                                                      placeholder="Contoh: Aspirasi sudah kami tindak lanjuti dengan jadwal Maintenance minggu depan..."
                                                      class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden resize-none">{{ $item->tanggapan_mpk }}</textarea>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block text-[11px] font-bold text-slate-700">Catatan Internal <span class="text-slate-400 font-normal">(tidak tampil ke siswa)</span></label>
                                            <textarea name="catatan_internal" rows="2"
                                                      placeholder="Catatan internal untuk anggota MPK..."
                                                      class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden resize-none">{{ $item->catatan_internal }}</textarea>
                                        </div>

                                        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                                            <button type="button" @click="modalId = null"
                                                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                                            <button type="submit"
                                                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20">
                                                Simpan Tindak Lanjut
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Tidak ada data aspirasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($aspirasis->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $aspirasis->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('#tabelAspirasi').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            autoWidth: false,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id-ID.json' },
        });
    });
</script>
@endpush

@endsection
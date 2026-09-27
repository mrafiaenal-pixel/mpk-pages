@extends('layout.admin')

@section('title', 'Manajemen Aspirasi Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Aspirasi Masuk</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola dan perbarui status tindak lanjut aspirasi siswa.</p>
        </div>

        <!-- Filter Status Buttons -->
        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-white border border-slate-200 shadow-xs self-start sm:self-auto text-xs">
            <a href="{{ route('admin.aspirasi') }}" 
               class="px-3 py-1.5 rounded-lg font-bold {{ !request('status') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                Semua
            </a>
            <a href="{{ route('admin.aspirasi', ['status' => 'menunggu']) }}" 
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'menunggu' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.aspirasi', ['status' => 'proses']) }}" 
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'proses' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                Diproses
            </a>
            <a href="{{ route('admin.aspirasi', ['status' => 'selesai']) }}" 
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:text-slate-900' }}">
                Selesai
            </a>
        </div>
    </div>

    <!-- Table of Aspirations -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-6">Pengirim</th>
                        <th class="py-3.5 px-6">Judul Aspirasi</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Status Saat Ini</th>
                        <th class="py-3.5 px-6">Tanggal Masuk</th>
                        <th class="py-3.5 px-6 text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aspirasis as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-400">
                                #{{ $item->id_aspirasi }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $item->nama_pengirim }}
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <p class="font-bold text-slate-800">{{ $item->judul_aspirasi }}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->deskripsi_aspirasi }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $item->category->nama_kategori ?? $item->kode_kategori }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status_aspirasi === 'selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                                    </span>
                                @elseif($item->status_aspirasi === 'proses')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-spinner text-[10px] animate-spin"></i> Diproses
                                    </span>
                                @elseif($item->status_aspirasi === 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.aspirasi.status', $item->id_aspirasi) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status_aspirasi" onchange="this.form.submit()" 
                                            class="px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-hidden cursor-pointer hover:bg-slate-100">
                                        <option value="menunggu" {{ $item->status_aspirasi === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="proses" {{ $item->status_aspirasi === 'proses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $item->status_aspirasi === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $item->status_aspirasi === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
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
@endsection

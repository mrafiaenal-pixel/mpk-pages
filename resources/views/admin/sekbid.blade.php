@extends('layout.admin')

@section('title', 'Daftar Seksi Bidang OSIS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Seksi Bidang & Anggota OSIS</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh seksi bidang dan perwakilan anggota di bawah pengawasan MPK.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
            <i class="fa-solid fa-layer-group"></i> 10 Sekbid Terdaftar
        </span>
    </div>

    <!-- Table of Sekbid & Anggota -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6 w-16">No</th>
                        <th class="py-3.5 px-6">Nama Seksi Bidang</th>
                        <th class="py-3.5 px-6">Daftar Anggota</th>
                        <th class="py-3.5 px-6 text-center">Jumlah Anggota</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sekbids as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    <span>{{ $item->nama_sekbid }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($item->members as $member)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200/60">
                                            {{ $member->nama_anggota }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 italic">Belum ada anggota</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700">
                                {{ $item->members->count() }} Orang
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.penilaian') }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold text-xs transition-colors inline-flex items-center gap-1">
                                    <i class="fa-solid fa-award text-[10px]"></i> Beri Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada data Seksi Bidang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

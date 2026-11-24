@extends('layout.admin')

@section('title', 'Hasil Pencarian')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Hasil Pencarian</h1>
            <p class="text-xs text-slate-500 mt-1">
                @if($q !== '')
                    Menampilkan hasil untuk <span class="font-bold text-blue-600">“{{ $q }}”</span>.
                @else
                    Gunakan kolom pencarian di bagian atas untuk mencari data.
                @endif
            </p>
        </div>

        @if($q !== '')
            @php
                $total = $sekbids->count() + $anggotas->count() + $aspirasis->count() + $progjas->count();
            @endphp

            @if($total === 0)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-10 text-center">
                    <i class="fa-solid fa-magnifying-glass text-3xl text-slate-300"></i>
                    <p class="text-sm font-bold text-slate-600 mt-3">Tidak ada data yang cocok.</p>
                    <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    @if($sekbids->isNotEmpty())
                        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-600 text-xs"></i>
                                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider">Seksi Bidang</h2>
                                <span class="ml-auto text-[10px] font-bold text-slate-400">{{ $sekbids->count() }}</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($sekbids as $sekbid)
                                    <a href="{{ route('admin.penilaian', ['divisi' => $sekbid->id]) }}" class="block px-5 py-3 hover:bg-slate-50 transition-colors">
                                        <p class="text-xs font-bold text-slate-800">{{ $sekbid->nama_sekbid }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $sekbid->members->count() }} anggota terdaftar</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($anggotas->isNotEmpty())
                        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-users text-emerald-600 text-xs"></i>
                                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider">Anggota Sekbid</h2>
                                <span class="ml-auto text-[10px] font-bold text-slate-400">{{ $anggotas->count() }}</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($anggotas as $anggota)
                                    <a href="{{ route('admin.penilaian', ['divisi' => $anggota->id_sekbid]) }}" class="block px-5 py-3 hover:bg-slate-50 transition-colors">
                                        <p class="text-xs font-bold text-slate-800">{{ $anggota->nama_anggota }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $anggota->division?->nama_sekbid ?? '-' }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($aspirasis->isNotEmpty())
                        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-inbox text-amber-600 text-xs"></i>
                                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider">Aspirasi</h2>
                                <span class="ml-auto text-[10px] font-bold text-slate-400">{{ $aspirasis->count() }}</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($aspirasis as $aspirasi)
                                    <a href="{{ route('admin.aspirasi', ['q' => $q]) }}" class="block px-5 py-3 hover:bg-slate-50 transition-colors">
                                        <p class="text-xs font-bold text-slate-800">{{ $aspirasi->judul_aspirasi }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $aspirasi->nama_pengirim }} · {{ ucfirst($aspirasi->status_aspirasi) }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($progjas->isNotEmpty())
                        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days text-indigo-600 text-xs"></i>
                                <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider">Kegiatan</h2>
                                <span class="ml-auto text-[10px] font-bold text-slate-400">{{ $progjas->count() }}</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($progjas as $progja)
                                    <a href="{{ route('admin.kegiatan', ['q' => $q]) }}" class="block px-5 py-3 hover:bg-slate-50 transition-colors">
                                        <p class="text-xs font-bold text-slate-800">{{ $progja->nama_progja }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ ucfirst(str_replace('_', ' ', $progja->status_progja)) }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection

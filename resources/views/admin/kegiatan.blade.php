@extends('layout.admin')

@section('title', 'Kelola Kegiatan OSIS / MPK')

@section('content')
    <div class="space-y-6" x-data="kegiatanPage()">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Kegiatan OSIS / MPK</h1>
                <p class="text-xs text-slate-500 mt-1">Data program kerja dan kegiatan rutin OSIS serta MPK.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.kegiatan.export.csv', request()->query()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-file-csv text-slate-600"></i>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('admin.kegiatan.export.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-file-pdf text-rose-600"></i>
                    <span>Export PDF</span>
                </a>
                <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Kegiatan</span>
                </button>
            </div>
        </div>

        <!-- Flash & Error -->
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

        <!-- Statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kegiatan</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $statistik['total'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rencana</p>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ $statistik['rencana'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Berjalan</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $statistik['berjalan'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Terlaksana</p>
                <p class="text-2xl font-black text-indigo-600 mt-1">{{ $statistik['terlaksana'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Batal</p>
                <p class="text-2xl font-black text-rose-600 mt-1">{{ $statistik['batal'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('admin.kegiatan') }}"
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari</label>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Nama atau deskripsi kegiatan..."
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-none focus:border-blue-500 focus:bg-white">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">Status</label>
                <select name="status" onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-none focus:border-blue-500 focus:bg-white">
                    <option value="">Semua Status</option>
                    @foreach (['rencana' => 'Rencana', 'berjalan' => 'Berjalan', 'terlaksana' => 'Terlaksana', 'belum_terlaksana' => 'Belum Terlaksana', 'sedang_berjalan' => 'Sedang Berjalan', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Tabel Kegiatan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table id="tabelKegiatan" class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6 w-16">No</th>
                            <th class="py-3.5 px-6">Nama Kegiatan</th>
                            <th class="py-3.5 px-6">Deskripsi</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6">Dibuat Pada</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kegiatans as $index => $row)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-400">{{ $kegiatans->firstItem() + $index }}</td>
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $row->nama_progja }}</td>
                                <td class="py-4 px-6 text-slate-600 max-w-xs line-clamp-2">
                                    {{ $row->deskripsi_progja ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    @php
                                        $warna = match ($row->status_progja) {
                                            'rencana' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'berjalan',
                                            'sedang_berjalan'
                                                => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlaksana', 'selesai' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'belum_terlaksana' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $warna }}">
                                        {{ ucfirst(str_replace('_', ' ', $row->status_progja)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    {{ $row->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <button type="button" data-id="{{ $row->id }}"
                                        data-nama="{{ $row->nama_progja }}"
                                        data-deskripsi="{{ $row->deskripsi_progja ?? '' }}"
                                        data-status="{{ $row->status_progja }}" @click="openEdit($el.dataset)"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold text-xs transition-colors inline-flex items-center gap-1">
                                        <i class="fa-solid fa-pen text-[10px]"></i> Ubah
                                    </button>
                                    <form action="{{ route('admin.kegiatan.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus kegiatan ini?')" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data kegiatan OSIS /
                                    MPK.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kegiatans->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $kegiatans->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Form -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="closeModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900"
                                x-text="editing ? 'Ubah Kegiatan' : 'Tambah Kegiatan'">
                                Tambah Kegiatan
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-1">Isi data kegiatan OSIS / MPK.</p>
                        </div>
                        <button type="button" @click="closeModal()"
                            class="p-2 text-slate-400 hover:text-slate-600 shrink-0">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="simpanKegiatan()" class="space-y-4">
                        <input type="hidden" x-model="form.id">

                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">Nama Kegiatan <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" x-model="form.nama_progja" required
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-none focus:border-blue-500 focus:bg-white"
                                placeholder="Contoh: LDKS, Porseni, Bakti Sosial">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">Deskripsi</label>
                            <textarea x-model="form.deskripsi_progja" rows="3"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-none focus:border-blue-500 focus:bg-white resize-none"
                                placeholder="Ceritakan singkat tentang kegiatan ini..."></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">Status</label>
                            <select x-model="form.status_progja"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-none focus:border-blue-500 focus:bg-white">
                                <option value="rencana">Rencana</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="terlaksana">Terlaksana</option>
                                <option value="belum_terlaksana">Belum Terlaksana</option>
                                <option value="sedang_berjalan">Sedang Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit" :disabled="saving"
                                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-bold text-xs shadow-md shadow-blue-600/20"
                                x-text="saving ? 'Menyimpan...' : (editing ? 'Simpan Perubahan' : 'Simpan Kegiatan')">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const CSRF_TOKEN = @json(csrf_token());
        const ROUTE_STORE = @json(route('admin.kegiatan.store'));
        const ROUTE_UPDATE = @json(route('admin.kegiatan.update', ['kegiatan' => '__ID__']));

        function kegiatanPage() {
            return {
                modalOpen: false,
                editing: false,
                saving: false,
                form: {
                    id: null,
                    nama_progja: '',
                    deskripsi_progja: '',
                    status_progja: 'rencana',
                },

                openCreate() {
                    this.editing = false;
                    this.form = {
                        id: null,
                        nama_progja: '',
                        deskripsi_progja: '',
                        status_progja: 'rencana'
                    };
                    this.modalOpen = true;
                },

                openEdit(data) {
                    this.editing = true;
                    this.form = {
                        id: data.id,
                        nama_progja: data.nama_progja,
                        deskripsi_progja: data.deskripsi_progja,
                        status_progja: data.status_progja,
                    };
                    this.modalOpen = true;
                },

                closeModal() {
                    this.modalOpen = false;
                    this.editing = false;
                    this.saving = false;
                },

                simpanKegiatan() {
                    if (this.saving) return;
                    this.saving = true;

                    const isEdit = !!this.form.id;
                    const url = isEdit ?
                        ROUTE_UPDATE.replace('__ID__', this.form.id) :
                        ROUTE_STORE;

                    const body = new FormData();
                    body.append('_token', CSRF_TOKEN);
                    body.append('_method', isEdit ? 'PATCH' : 'POST');
                    body.append('nama_progja', this.form.nama_progja);
                    body.append('deskripsi_progja', this.form.deskripsi_progja ?? '');
                    body.append('status_progja', this.form.status_progja);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body,
                        })
                        .then(async (r) => {
                            if (r.ok) {
                                window.location.reload();
                                return;
                            }
                            let msg = 'Gagal menyimpan.';
                            try {
                                const json = await r.json();
                                if (json.errors) msg = Object.values(json.errors).flat().join('\n');
                                else if (json.message) msg = json.message;
                            } catch (e) {
                                /* ignore */
                            }
                            alert(msg);
                            this.saving = false;
                        })
                        .catch(() => {
                            alert('Gagal menyambung ke server.');
                            this.saving = false;
                        });
                },
            };
        }
    </script>
@endpush

@extends('layout.admin')

@section('title', 'Penilaian Kinerja Sekbid')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penilaian Kinerja Anggota Sekbid</h1>
            <p class="text-xs text-slate-500 mt-1">Evaluasi kedisiplinan dan capaian kegiatan OSIS berdasarkan 5 aspek standar.</p>
        </div>
        <button @click="modalOpen = true" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Input Penilaian Baru</span>
        </button>
    </div>

    <!-- Table of Evaluations -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Anggota Dinilai</th>
                        <th class="py-3.5 px-6">Seksi Bidang</th>
                        <th class="py-3.5 px-6">Keaktifan GDS</th>
                        <th class="py-3.5 px-6">Piket RO</th>
                        <th class="py-3.5 px-6">Kekompakan</th>
                        <th class="py-3.5 px-6">Komunikasi</th>
                        <th class="py-3.5 px-6">Progja</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6 text-right">Penilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penilaians as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($item->anggotaSekbid->nama_anggota ?? 'A', 0, 1)) }}
                                    </div>
                                    <span>{{ $item->anggotaSekbid->nama_anggota ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $item->anggotaSekbid->division->nama_sekbid ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-600">
                                {{ $item->aspect->keaktifan_gds ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $item->aspect->piket_ro ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $item->aspect->kekompakan ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $item->aspect->komunikasi ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $item->aspect->kegiatan_progja ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $item->aspect ? \Carbon\Carbon::parse($item->aspect->tanggal_penilaian)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right text-slate-600 font-medium">
                                {{ $item->user->username ?? 'MPK' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                Belum ada riwayat penilaian kinerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penilaians->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $penilaians->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL FORM INPUT PENILAIAN (MATCHING FIGMA FORM) -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="modalOpen = false"></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Formulir Penilaian Kinerja</h3>
                            <p class="text-xs text-slate-400">Pilih anggota dan isi penilaian 5 aspek kerja.</p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.penilaian.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Pilih Anggota -->
                    <div class="space-y-1.5">
                        <label for="id_anggota_sekbid" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Pilih Anggota Sekbid <span class="text-rose-500">*</span>
                        </label>
                        <select id="id_anggota_sekbid" name="id_anggota_sekbid" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-blue-500 outline-hidden">
                            <option value="" disabled selected>Pilih nama anggota OSIS...</option>
                            @foreach($anggotaList as $anggota)
                                <option value="{{ $anggota->id }}">
                                    {{ $anggota->nama_anggota }} — ({{ $anggota->division->nama_sekbid ?? 'Sekbid' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 6 Rating Aspects -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <!-- Keaktifan GDS -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">1. Keaktifan GDS (Pagi)</label>
                            <select name="keaktifan_gds" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="hadir">Hadir (Tepat Waktu)</option>
                                <option value="telat 1x">Telat 1x</option>
                                <option value="telat 2x">Telat 2x</option>
                                <option value="telat 3x">Telat 3x</option>
                                <option value="izin/lainnya">Izin / Lainnya</option>
                                <option value="tidak hadir">Tidak Hadir</option>
                            </select>
                        </div>

                        <!-- Piket RO -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">2. Kedisiplinan Piket RO</label>
                            <select name="piket_ro" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="hadir">Hadir</option>
                                <option value="tidak ada jadwal">Tidak Ada Jadwal</option>
                                <option value="izin/sakit">Izin / Sakit</option>
                                <option value="tanpa keterangan">Tanpa Keterangan</option>
                                <option value="tidak bagiannya">Tidak Bagiannya</option>
                            </select>
                        </div>

                        <!-- Kekompakan -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">3. Kekompakan Sekbid</label>
                            <select name="kekompakan" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="ramai aktif">Ramai & Aktif</option>
                                <option value="sepi ada komunikasi">Sepi Ada Komunikasi</option>
                                <option value="ramai saat progja">Ramai Saat Progja</option>
                                <option value="grup mati">Grup Mati</option>
                            </select>
                        </div>

                        <!-- Komunikasi -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">4. Komunikasi & Koordinasi</label>
                            <select name="komunikasi" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="baik responsif">Baik & Responsif</option>
                                <option value="lambat merespon">Lambat Merespon</option>
                                <option value="sulit koordinasi">Sulit Koordinasi</option>
                                <option value="tidak berkomunikasi">Tidak Berkomunikasi</option>
                            </select>
                        </div>

                        <!-- Kehadiran Rapat -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">5. Kehadiran Rapat</label>
                            <select name="kehadiran_rapat" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="tepat waktu">Tepat Waktu</option>
                                <option value="telat">Telat</option>
                                <option value="izin alasan jelas">Izin Alasan Jelas</option>
                                <option value="sakit">Sakit</option>
                                <option value="tanpa keterangan">Tanpa Keterangan</option>
                            </select>
                        </div>

                        <!-- Kegiatan Progja -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">6. Pelaksanaan Progja</label>
                            <select name="kegiatan_progja" required class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                <option value="sesuai job">Sesuai Job Desk</option>
                                <option value="kurang maksimal">Kurang Maksimal</option>
                                <option value="tidak ikut alasan">Tidak Ikut (Ada Alasan)</option>
                                <option value="tidak ada progja">Tidak Ada Progja</option>
                            </select>
                        </div>
                    </div>

                    <!-- Inisiatif Bantuan Checkbox -->
                    <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-2.5">
                        <input type="checkbox" name="inisiatif_bantuan" id="inisiatif_bantuan" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <label for="inisiatif_bantuan" class="text-xs font-semibold text-slate-700 cursor-pointer">
                            Memberikan Inisiatif Bantuan Tambahan (+15 Poin)
                        </label>
                    </div>

                    <!-- Tanggal Penilaian -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Tanggal Penilaian</label>
                        <input type="datetime-local" name="tanggal_penilaian" required 
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                    </div>

                    <!-- Catatan Evaluasi -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Catatan & Masukan Pembinaan (Opsional)</label>
                        <textarea name="catatan_terhadap_anggota" rows="3" 
                                  placeholder="Tuliskan catatan evaluasi kinerja untuk anggota bersangkutan..."
                                  class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden resize-none"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="modalOpen = false" 
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20">
                            Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

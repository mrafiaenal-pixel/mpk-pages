<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penilaian Kinerja OSIS</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 10px; color: #334155; }
        h1 { font-size: 16px; margin: 0 0 4px 0; color: #0f172a; }
        .subtitle { font-size: 9px; color: #64748b; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: left; font-size: 8.5px; }
        thead { background: #f1f5f9; color: #475569; }
        thead th { font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; font-size: 7.5px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .meta { font-size: 8.5px; color: #64748b; margin-bottom: 10px; }
        .meta span { margin-right: 16px; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 10px; font-size: 7px; font-weight: 700; }
        .bg-emerald { background: #dcfce7; color: #166534; }
        .bg-blue { background: #dbeafe; color: #1d4ed8; }
        .bg-amber { background: #fef3c7; color: #92400e; }
        .bg-orange { background: #ffedd5; color: #c2410c; }
        .bg-rose { background: #ffe4e6; color: #9d174d; }
    </style>
</head>
<body>
    <h1>Laporan Penilaian Kinerja Anggota Sekbid</h1>
    <p class="subtitle">MPK Portal &middot; Dibuat pada {{ $generatedAt }}</p>

    <div class="meta">
        <span><strong>Jumlah Data:</strong> {{ $penilaians->count() }}</span>
        @foreach($filterAktif as $k => $v)
            <span><strong>{{ ucfirst(str_replace('_', ' ', $k)) }}:</strong> {{ $v }}</span>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">ID</th>
                <th style="width:14%">Tanggal</th>
                <th style="width:16%">Seksi Bidang</th>
                <th style="width:14%">Anggota</th>
                <th style="width:14%">MPK Penilai</th>
                <th style="width:8%">Nilai</th>
                <th style="width:12%">Predikat</th>
                <th style="width:18%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaians as $row)
                @php
                    $aspek = $row->aspect;
                    $predikat = $aspek ? PenilaiSkor::predikat((float) $aspek->nilai) : '-';
                    $warna = match ($predikat) {
                        'Sangat Baik' => 'bg-emerald',
                        'Baik' => 'bg-blue',
                        'Cukup' => 'bg-amber',
                        'Perlu Pembinaan' => 'bg-orange',
                        default => 'bg-rose',
                    };
                @endphp
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $aspek?->tanggal_penilaian?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $row->divisionMember?->division?->nama_sekbid ?? '-' }}</td>
                    <td>{{ $row->divisionMember?->nama_anggota ?? '-' }}</td>
                    <td>{{ $row->user?->username ?? '-' }}</td>
                    <td>{{ $aspek?->nilai !== null ? number_format($aspek->nilai, 2) : '-' }}</td>
                    <td><span class="badge {{ $warna }}">{{ $predikat }}</span></td>
                    <td>{{ $aspek?->catatan_terhadap_anggota ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center">Tidak ada data penilaian.</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
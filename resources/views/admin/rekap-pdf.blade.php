<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Rekap Penilaian OSIS</title>
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
    <h1>Laporan Rekap Visual Penilaian OSIS</h1>
    <p class="subtitle">MPK Portal &middot; Rata-rata nilai per Seksi Bidang &middot; Dibuat pada {{ $generatedAt }}</p>

    <div class="meta">
        <span><strong>Total Penilaian:</strong> {{ $statistikSekbid->sum('jumlah_penilaian') }}</span>
        <span><strong>Sekdi Terbaik:</strong> {{ $statistikSekbid->first()['nama'] ?? '-' }}</span>
    </div>

    <h2 style="font-size:12px; margin-top:16px; color:#0f172a;">Rata-rata Nilai per Seksi Bidang</h2>
    <table>
        <thead>
            <tr>
                <th style="width:5%">Peringkat</th>
                <th style="width:30%">Seksi Bidang</th>
                <th style="width:14%">Anggota</th>
                <th style="width:14%">Jumlah Penilaian</th>
                <th style="width:14%">Rata-rata</th>
                <th style="width:11%">Tertinggi</th>
                <th style="width:11%">Terendah</th>
                <th style="width:11%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($statistikSekbid as $row)
                @php $no++; @endphp
                @php
                    $predikat = $row['predikat'];
                    $warna = match ($predikat) {
                        'Sangat Baik' => 'bg-emerald',
                        'Baik' => 'bg-blue',
                        'Cukup' => 'bg-amber',
                        'Perlu Pembinaan' => 'bg-orange',
                        default => 'bg-rose',
                    };
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['jumlah_anggota'] }}</td>
                    <td>{{ $row['jumlah_penilaian'] }}</td>
                    <td>{{ number_format($row['rata_nilai'], 2) }}</td>
                    <td>{{ number_format($row['tertinggi'], 2) }}</td>
                    <td>{{ number_format($row['terendah'], 2) }}</td>
                    <td><span class="badge {{ $warna }}">{{ $predikat }}</span></td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center">Tidak ada data.</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2 style="font-size:12px; margin-top:16px; color:#0f172a;">Rata-rata per Aspek (Skala 0-100)</h2>
    <table>
        <thead>
            <tr><th style="width:50%">Aspek</th><th style="width:20%">Rata-rata</th><th style="width:12%">Jumlah</th></tr>
        </thead>
        <tbody>
            @foreach($statistikAspek as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td>{{ number_format($row['rata'], 2) }}</td>
                    <td>{{ $row['jumlah'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center">Tidak ada data.</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2 style="font-size:12px; margin-top:16px; color:#0f172a;">Tren Bulanan</h2>
    <table>
        <thead>
            <tr><th style="width:40%">Periode</th><th style="width:30%">Rata-rata Nilai</th><th style="width:20%">Jumlah</th></tr>
        </thead>
        <tbody>
            @foreach($trenBulanan as $row)
                <tr>
                    <td>{{ $row['periode'] }}</td>
                    <td>{{ number_format($row['rata_nilai'], 2) }}</td>
                    <td>{{ $row['jumlah'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center">Tidak ada data.</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
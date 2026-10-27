<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Kegiatan OSIS / MPR</title>
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
        .badge { display: inline-block; padding: 1px 5px; border-radius: 10px; font-size: 7px; font-weight: 700; }
        .bg-rencana { background: #dbeafe; color: #1d4ed8; }
        .bg-berjalan { background: #dcfce7; color: #166534; }
        .bg-terlaksana { background: #ccfbf1; color: #115e59; }
        .bg-batal { background: #ffe4e6; color: #9d174d; }
    </style>
</head>
<body>
    <h1>Laporan Kegiatan OSIS / MPR</h1>
    <p class="subtitle">MPK Portal &middot; Dibuat pada {{ $generatedAt }}</p>

    <div class="meta">
        <span><strong>Total Kegiatan:</strong> {{ $kegiatans->count() }}</span>
        @if($statusFilter)
            <span><strong>Status Filter:</strong> {{ $statusFilter }}</span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">ID</th>
                <th style="width:28%">Nama Kegiatan</th>
                <th style="width:40%">Deskripsi</th>
                <th style="width:15%">Status</th>
                <th style="width:12%">Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatans as $row)
                @php
                    $warna = match ($row->status_progja) {
                        'rencana' => 'bg-rencana',
                        'berjalan', 'sedang_berjalan' => 'bg-berjalan',
                        'terlaksana', 'selesai' => 'bg-terlaksana',
                        default => 'bg-batal',
                    };
                @endphp
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->nama_progja }}</td>
                    <td>{{ $row->deskripsi_progja ?? '-' }}</td>
                    <td><span class="badge {{ $warna }}">{{ ucfirst(str_replace('_', ' ', $row->status_progja)) }}</span></td>
                    <td>{{ $row->created_at?->format('d M Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center">Tidak ada data kegiatan.</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
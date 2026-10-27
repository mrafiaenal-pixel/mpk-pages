<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Aspirasi OSIS</title>
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
        .bg-rose { background: #ffe4e6; color: #9d174d; }
    </style>
</head>
<body>
    <h1>Laporan Aspirasi Masuk OSIS</h1>
    <p class="subtitle">MPK Portal &middot; Dibuat pada {{ $generatedAt }}</p>

    <div class="meta">
        <span><strong>Total Data:</strong> {{ $aspirasis->count() }}</span>
        @foreach($filterAktif as $k => $v)
            <span><strong>{{ ucfirst(str_replace('_', ' ', $k)) }}:</strong> {{ $v }}</span>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">ID</th>
                <th style="width:14%">Tanggal Masuk</th>
                <th style="width:14%">Pengirim</th>
                <th style="width:26%">Judul Aspirasi</th>
                <th style="width:14%">Kategori</th>
                <th style="width:12%">Status</th>
                <th style="width:15%">Tanggapan MPK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aspirasis as $row)
                @php
                    $warnaStatus = match ($row->status_aspirasi) {
                        'selesai' => 'bg-emerald',
                        'proses' => 'bg-blue',
                        'ditolak' => 'bg-rose',
                        default => 'bg-amber',
                    };
                @endphp
                <tr>
                    <td>#{{ $row->id_aspirasi }}</td>
                    <td>{{ $row->created_at?->format('d M Y H:i') ?? '-' }}</td>
                    <td>{{ $row->nama_pengirim ?? '-' }}</td>
                    <td>{{ $row->judul_aspirasi }}</td>
                    <td>{{ $row->category->nama_kategori ?? $row->kode_kategori }}</td>
                    <td><span class="badge {{ $warnaStatus }}">{{ ucfirst($row->status_aspirasi) }}</span></td>
                    <td>{{ $row->tanggapan_mpk ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center">Tidak ada data aspirasi.</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
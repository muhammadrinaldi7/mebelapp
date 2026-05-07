<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $reportTitle }}</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Referensi</th>
                <th>Tanggal</th>
                <th>Admin</th>
                <th>Keterangan</th>
                <th>Total Item</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($opnames as $index => $opname)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $opname->reference_code }}</td>
                <td>{{ $opname->opname_date->format('d/m/Y') }}</td>
                <td>{{ $opname->user->name }}</td>
                <td>{{ $opname->notes }}</td>
                <td>{{ $opname->details->count() }}</td>
                <td>{{ $opname->status == 'completed' ? 'Selesai' : 'Draft' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

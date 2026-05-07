<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 0; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-red { color: #e3342f; }
        .text-green { color: #38c172; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Detail Stock Opname</h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>Kode Referensi</strong></td>
            <td width="30%">: {{ $opname->reference_code }}</td>
            <td width="20%"><strong>Tanggal</strong></td>
            <td width="30%">: {{ $opname->opname_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Admin</strong></td>
            <td>: {{ $opname->user->name }}</td>
            <td><strong>Status</strong></td>
            <td>: {{ $opname->status == 'completed' ? 'Selesai' : 'Draft' }}</td>
        </tr>
        <tr>
            <td><strong>Keterangan</strong></td>
            <td colspan="3">: {{ $opname->notes ?: '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="35%">Produk</th>
                <th width="15%" class="text-center">Stok Sistem</th>
                <th width="15%" class="text-center">Stok Fisik</th>
                <th width="10%" class="text-center">Selisih</th>
                <th width="20%">Keterangan Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($opname->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $detail->product->name }}</strong><br>
                    <small>SKU: {{ $detail->product->sku }}</small>
                </td>
                <td class="text-center">{{ $detail->system_stock }}</td>
                <td class="text-center">{{ $detail->physical_stock }}</td>
                <td class="text-center font-bold">
                    @if($detail->difference > 0)
                        <span class="text-green">+{{ $detail->difference }}</span>
                    @elseif($detail->difference < 0)
                        <span class="text-red">{{ $detail->difference }}</span>
                    @else
                        <span>0</span>
                    @endif
                </td>
                <td>{{ $detail->notes ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

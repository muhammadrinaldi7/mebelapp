<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Jalan - {{ $transaction->reference_code }}</title>
    <style>
        /* A5 Half Letter Landscape / 3-Ply Dot Matrix Optimization */
        @page {
            size: A5 landscape;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 210mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .wrapper {
            border: 1px dotted #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .company-info h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-info p {
            margin: 2px 0;
        }

        .doc-title {
            text-align: right;
        }

        .doc-title h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .customer-info,
        .invoice-info {
            width: 48%;
        }

        .customer-info strong {
            display: block;
            margin-bottom: 4px;
        }

        .customer-info p,
        .invoice-info p {
            margin: 2px 0;
        }

        .invoice-info {
            text-align: right;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th,
        td {
            border: 1px dashed #000;
            padding: 8px 4px;
            /* Slightly taller rows for checking */
            text-align: left;
        }

        th {
            background: transparent;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .text-center {
            text-align: center;
        }

        /* Signatures Container - Taller for delivery notes */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 30%;
            text-align: center;
        }

        .sig-box p {
            font-weight: bold;
            margin: 0 0 50px 0;
            /* More space for actual signing */
        }

        .sig-box .line {
            border-top: 1px solid #000;
            display: inline-block;
            width: 80%;
            padding-top: 5px;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">[🖨️ CETAK SURAT JALAN / TEKAN CTRL+P]</button>
    </div>

    <div class="wrapper">
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Mebel Stock') }}</h1>
                <p>Retail Furniture</p>
                <p>Admin Gudang: {{ Auth::user()->name }}</p>
            </div>
            <div class="doc-title">
                <h2>SURAT JALAN</h2>
                <p>REF: <strong>{{ $transaction->reference_code }}</strong></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="customer-info">
                <strong>Alamat Pengiriman (Penerima):</strong>
                <p>{{ $transaction->customer_name ?: 'Bpk/Ibu (Umum)' }}</p>
                <p>{{ $transaction->customer_address ?: '-' }}</p>
                @if ($transaction->customer_phone)
                    <p>Telp/WA: {{ $transaction->customer_phone }}</p>
                @endif
            </div>
            <div class="invoice-info">
                <p><strong>Tanggal Keluar:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <p><strong>Armada Pengiriman:</strong>
                    {{ str_replace('_', ' ', Str::title($transaction->shipping_status)) }}
                </p>
                @if ($transaction->driver_name)
                    <p><strong>Nama Supir:</strong> {{ $transaction->driver_name }}</p>
                @endif
                @if ($transaction->notes)
                    <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Kode Item/SKU</th>
                    <th width="60%">Nama Item</th>
                    <th width="10%" class="text-center">Qty / Jumlah</th>
                    <th width="10%" class="text-center">Kondisi Cek</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->product->sku ?? '-' }}</td>
                        <td>{{ $detail->product->name ?? '-' }}</td>
                        <td class="text-center font-bold" style="font-size: 13px;">{{ $detail->quantity }}</td>
                        <td class="text-center">[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Signatures (3 parts for delivery) -->
        <div class="signatures">
            <div class="sig-box">
                <p>Diterima Oleh (Pembeli),</p>
                <div class="line">Nama Lengkap & TTD</div>
            </div>
            <div class="sig-box">
                <p>Dikirim Oleh (Supir),</p>
                <div class="line">Nama Supir / Kenek</div>
            </div>
            <div class="sig-box">
                <p>Hormat Kami (Gudang),</p>
                <div class="line">Bag. Logistik / QC</div>
            </div>
        </div>

        <div
            style="text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed #000; padding-top: 5px;">
            Mohon periksa kondisi barang sebelum supir meninggalkan lokasi. Klaim garansi atau kerusakan setelah supir
            pergi mengikuti syarat dan ketentuan toko.<br>
            *** Lembar 1: Pembeli | Lembar 2: Supir | Lembar 3: Toko ***
        </div>
    </div>
</body>

</html>

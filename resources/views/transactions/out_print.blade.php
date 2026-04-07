<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Jalan Keluar - {{ $transaction->reference_code }}</title>
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
            margin-bottom: 15px;
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

        /* Signatures Container */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 45%;
            text-align: center;
        }

        .sig-box p {
            font-weight: bold;
            margin: 0 0 50px 0;
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
        <button class="btn-print" onclick="window.print()">[🖨️ CETAK SURAT JALAN BARANG KELUAR]</button>
    </div>

    <div class="wrapper">
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Mebel Stock') }}</h1>
                <p>Pusat Grosir & Retail Furniture</p>
                <p>Dicetak Oleh: {{ Auth::user()->name }}</p>
            </div>
            <div class="doc-title">
                <h2>SURAT JALAN</h2>
                <p>REF: <strong>{{ $transaction->reference_code }}</strong></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="customer-info" style="border: 1px solid #000; padding: 5px;">
                <strong>Tujuan / Alasan Pengeluaran:</strong>
                <p style="font-size: 14px; font-weight: bold;">
                    @php
                        // Memisahkan alasan (misal: [Pindah]) dari catatan utamanya
                        preg_match('/\[(.*?)\] (.*)/', $transaction->notes, $matches);
                        $reason = $matches[1] ?? 'Lainnya';
                        $note = $matches[2] ?? $transaction->notes;
                    @endphp
                    {{ strtoupper($reason) }}
                </p>
                <p><strong>Catatan:</strong> {{ $note }}</p>
            </div>
            <div class="invoice-info">
                <p><strong>Tanggal Keluar:</strong> {{ $transaction->transaction_date->format('d/m/Y') }}</p>
                <p><strong>Dikeluarkan Oleh:</strong> {{ $transaction->user->name ?? '-' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Kode/SKU</th>
                    <th width="60%">Nama Mebel & Spesifikasi</th>
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

        <div class="signatures">
            <div class="sig-box">
                <p>Penerima / Supir / Tujuan,</p>
                <div class="line">Nama Lengkap & TTD</div>
            </div>
            <div class="sig-box">
                <p>Menyerahkan (Gudang/Logistik),</p>
                <div class="line">Nama Lengkap & TTD</div>
            </div>
        </div>

        <div
            style="text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed #000; padding-top: 5px;">
            Dokumen ini sah sebagai tanda pemindahan barang atau barang keluar dari gudang utama.<br>
            *** Lembar 1: Pihak Tujuan | Lembar 2: Arsip Gudang ***
        </div>
    </div>
</body>

</html>

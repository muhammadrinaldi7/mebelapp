<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Nota Penjualan - {{ $transaction->reference_code }}</title>
    <style>
        /* A5 Half Letter Landscape / 3-Ply Dot Matrix Optimization */
        @page {
            size: A5 landscape;
            margin: 8mm;
            /* Mengurangi margin kertas A5 */
        }

        * {
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            /* Pengecilan font sedikit agar muat margin */
            line-height: 1.3;
            /* Mengurangi jarak antar baris */
            color: #000;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 240mm;
        }

        /* Hide browser print overlay items */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        /* Layout Grid */
        .wrapper {
            border: 1px dotted #000;
            padding: 10px;
            /* Kurangi padding wrapper */
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            /* Kurangi jarak header */
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
            /* Kurangi jarak info-grid */
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
            /* Kurangi jarak bawah tabel */
        }

        th,
        td {
            border: 1px dashed #000;
            padding: 4px;
            /* Kurangi padding tabel */
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

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Totals Area */
        .totals-wrapper {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            /* Kurangi jarak totals */
        }

        .totals-table {
            width: 50%;
            border: none;
        }

        .totals-table th,
        .totals-table td {
            border: none;
            border-bottom: 1px dotted #000;
            padding: 2px 4px;
            /* Kurangi padding totals-table */
        }

        .totals-table tr:last-child th,
        .totals-table tr:last-child td {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            /* Kurangi margin atas tanda tangan */
            page-break-inside: avoid;
            /* Hindari pemotongan halaman di ttd */
        }

        .sig-box {
            width: 30%;
            text-align: center;
        }

        .sig-box p {
            margin: 0 0 35px 0;
            /* Kurangi jarak tinggi ttd */
        }

        .sig-box .line {
            border-top: 1px dashed #000;
            display: inline-block;
            width: 80%;
            padding-top: 5px;
        }

        .btn-print {
            background: #000;
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
        <button class="btn-print" onclick="window.print()">[🖨️ CETAK NOTA / TEKAN CTRL+P]</button>
    </div>

    <div class="wrapper">
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Mebel Stock') }}</h1>
                <p>Retail Furniture</p>
                <p>Dicetak oleh: {{ Auth::user()->name }}</p>
            </div>
            <div class="doc-title">
                <h2>NOTA PENJUALAN</h2>
                <p>KODE: <strong>{{ $transaction->reference_code }}</strong></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="customer-info">
                <strong>Kepada Yth Pembeli:</strong>
                <p>{{ $transaction->customer_name ?: 'Bpk/Ibu (Umum)' }}</p>
                @if ($transaction->customer_phone)
                    <p>Telp/WA: {{ $transaction->customer_phone }}</p>
                @endif
                <p>
                    <strong>Alamat Kirim:</strong>
                    {{ $transaction->customer_address ?: '-' }}
                </p>
            </div>
            <div class="invoice-info">
                <p><strong>Tanggal Transaksi:</strong> {{ $transaction->transaction_date->format('d/m/Y') }}</p>
                <p><strong>Status Pembayaran:</strong>
                    {{ $transaction->payment_status === 'dp' ? 'DP (Hutang)' : ($transaction->payment_status === 'lunas' ? 'LUNAS' : 'Belum Dibayar') }}
                </p>
                <p><strong>Status Pengiriman:</strong>
                    {{ str_replace('_', ' ', Str::title($transaction->shipping_status)) }} @if ($transaction->driver_name)
                        (Supir: {{ $transaction->driver_name }})
                    @endif
                </p>
                @if ($transaction->notes)
                    <p><strong>Keterangan:</strong> {{ $transaction->notes }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode Item/SKU</th>
                    <th width="40%">Nama Item</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Harga Satuan</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->product->sku ?? '-' }}</td>
                        <td>{{ $detail->product->name ?? '-' }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">{{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                        <td class="text-right">
                            {{ number_format($detail->quantity * $detail->price_at_transaction, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-wrapper">
            @php
                $subtotal = $transaction->total_amount;
                $discount = $transaction->discount ?? 0;
                $shipping = $transaction->shipping_cost ?? 0;
                $grandTotal = $subtotal - $discount + $shipping;
            @endphp
            <table class="totals-table">
                <tr>
                    <th>Subtotal (Rp) :</th>
                    <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                @if ($shipping > 0)
                    <tr>
                        <th>Ongkos Kirim (+) :</th>
                        <td class="text-right">{{ number_format($shipping, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($discount > 0)
                    <tr>
                        <th>Diskon (-) :</th>
                        <td class="text-right">{{ number_format($discount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <th style="font-size: 14px;">GRAND TOTAL (Rp) :</th>
                    <td class="text-right font-bold" style="font-size: 14px;">
                        {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->payment_status === 'dp')
                    <tr>
                        <th>Uang Muka / DP (-) :</th>
                        <td class="text-right">{{ number_format($transaction->down_payment, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th style="font-size: 14px; text-decoration: underline;">SISA TAGIHAN (Rp) :</th>
                        <td class="text-right font-bold" style="font-size: 14px; text-decoration: underline;">
                            {{ number_format($grandTotal - $transaction->down_payment, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="signatures">
            <div class="sig-box">
                <p>Penerima Barang,</p>
                <div class="line">Nama Lengkap & TTD</div>
            </div>
            <div class="sig-box">
                <p>Hormat Kami,</p>
                <div class="line">Pengirim / Sales</div>
            </div>
        </div>
        {{-- 
        <div
            style="text-align: center; margin-top: 10px; font-size: 10px; border-top: 1px dashed #000; padding-top: 5px;">
            Terima kasih atas kepercayaannya. Barang yang sudah dibeli tidak dapat ditukar/dikembalikan kecuali ada
            perjanjian.<br>
            *** Lembar Putih (Pembeli), Lembar Merah/Kuning (Pabrik/Arsip) ***
        </div> --}}
    </div>
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    @if($tab === 'transactions')
        <table>
            <thead>
                <tr><th colspan="8" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Laporan Log Transaksi' }}</th></tr>
                <tr><th colspan="8" style="text-align: center;">Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}</th></tr>
                <tr><th colspan="8"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Kode Referensi</th>
                    <th style="font-weight: bold; text-align: left;">Tipe</th>
                    <th style="font-weight: bold; text-align: left;">Tanggal</th>
                    <th style="font-weight: bold; text-align: left;">User</th>
                    <th style="font-weight: bold; text-align: left;">Item Produk</th>
                    <th style="font-weight: bold; text-align: center;">Qty</th>
                    <th style="font-weight: bold; text-align: right;">Harga Satuan</th>
                    <th style="font-weight: bold; text-align: right;">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($transactions as $trx)
                    @foreach($trx->details as $idx => $detail)
                    @php $subtotal = $detail->quantity * $detail->price_at_transaction; $grandTotal += $subtotal; @endphp
                    <tr>
                        <td style="font-weight: bold;">{{ $idx === 0 ? $trx->reference_code : '' }}</td>
                        <td>{{ $idx === 0 ? ($trx->type === 'in' ? 'Masuk' : ($trx->type === 'out' ? 'Keluar' : 'Penjualan')) : '' }}</td>
                        <td>{{ $idx === 0 ? $trx->transaction_date->format('d/m/Y') : '' }}</td>
                        <td>{{ $idx === 0 ? ($trx->user->name ?? '-') : '' }}</td>
                        <td>{{ $detail->product->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $detail->quantity }}</td>
                        <td style="text-align: right;">{{ $detail->price_at_transaction }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ $subtotal }}</td>
                    </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right; font-weight: bold;">GRAND TOTAL</td>
                    <td style="text-align: right; font-weight: bold;">{{ $grandTotal }}</td>
                </tr>
            </tbody>
        </table>
    @elseif($tab === 'stock')
        <table>
            <thead>
                <tr><th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Laporan Stok & Valuasi Aset' }}</th></tr>
                <tr><th colspan="7" style="text-align: center;">Dicetak: {{ date('d/m/Y H:i:s') }}</th></tr>
                <tr><th colspan="7"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">SKU</th>
                    <th style="font-weight: bold; text-align: left;">Kategori</th>
                    <th style="font-weight: bold; text-align: left;">Merek</th>
                    <th style="font-weight: bold; text-align: left;">Nama Produk</th>
                    <th style="font-weight: bold; text-align: right;">Stok Fisik</th>
                    <th style="font-weight: bold; text-align: right;">Harga Modal</th>
                    <th style="font-weight: bold; text-align: right;">Total Nilai Aset</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAsset = 0; $totalQty = 0; @endphp
                @foreach($products as $product)
                @php 
                    $asset = $product->current_stock * $product->base_price; 
                    $totalAsset += $asset;
                    $totalQty += $product->current_stock;
                @endphp
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->brand->name ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $product->current_stock }}</td>
                    <td style="text-align: right;">{{ $product->base_price }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $asset }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">TOTAL KESELURUHAN</td>
                    <td style="text-align: right; font-weight: bold;">{{ $totalQty }}</td>
                    <td></td>
                    <td style="text-align: right; font-weight: bold;">{{ $totalAsset }}</td>
                </tr>
            </tbody>
        </table>
    @elseif($tab === 'movement')
        <table>
            <thead>
                <tr><th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Pergerakan Fast & Slow Moving' }}</th></tr>
                <tr><th colspan="6" style="text-align: center;">Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}</th></tr>
                <tr><th colspan="6"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">SKU</th>
                    <th style="font-weight: bold; text-align: left;">Nama Produk</th>
                    <th style="font-weight: bold; text-align: right;">Masuk (In)</th>
                    <th style="font-weight: bold; text-align: right;">Keluar (Out)</th>
                    <th style="font-weight: bold; text-align: right;">Terjual (Sale)</th>
                    <th style="font-weight: bold; text-align: center;">Status Movement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productsMove as $pm)
                <tr>
                    <td>{{ $pm->sku }}</td>
                    <td>{{ $pm->name }}</td>
                    <td style="text-align: right;">{{ $pm->total_in }}</td>
                    <td style="text-align: right;">{{ $pm->total_out }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $pm->total_sale }}</td>
                    <td style="text-center">
                        @if($pm->total_sale > 10) Fast Moving
                        @elseif($pm->total_sale > 0) Normal
                        @elseif($pm->total_in == 0 && $pm->total_sale == 0) Dead Stock
                        @else Slow Moving @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tab === 'profit')
        <table>
            <thead>
                <tr><th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Laporan Keuntungan Kotor' }}</th></tr>
                <tr><th colspan="7" style="text-align: center;">Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}</th></tr>
                <tr><th colspan="7"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Tanggal</th>
                    <th style="font-weight: bold; text-align: left;">Referensi Penjualan</th>
                    <th style="font-weight: bold; text-align: left;">Nama Produk</th>
                    <th style="font-weight: bold; text-align: right;">Qty Terjual</th>
                    <th style="font-weight: bold; text-align: right;">Harga Jual Satuan</th>
                    <th style="font-weight: bold; text-align: right;">HPP (Modal Satuan)</th>
                    <th style="font-weight: bold; text-align: right;">Laba Kotor (Profit)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalProfit = 0; $uniqueTransactions = []; $totalDiscount = 0; @endphp
                @foreach($profitDetails as $detail)
                @php
                    $hpp = $detail->product->base_price ?? 0;
                    $profit_per_unit = $detail->price_at_transaction - $hpp;
                    $total_profit = $profit_per_unit * $detail->quantity;
                    $totalProfit += $total_profit;
                    
                    if (!isset($uniqueTransactions[$detail->transaction_id])) {
                        $uniqueTransactions[$detail->transaction_id] = true;
                        $totalDiscount += $detail->transaction->discount;
                    }
                @endphp
                <tr>
                    <td>{{ $detail->transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $detail->transaction->reference_code }}</td>
                    <td>{{ $detail->product->name ?? '-' }}</td>
                    <td style="text-align: right;">{{ $detail->quantity }}</td>
                    <td style="text-align: right;">{{ $detail->price_at_transaction }}</td>
                    <td style="text-align: right;">{{ $hpp }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $total_profit }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL Keuntungan (Belum Potongan)</td>
                    <td style="text-align: right; font-weight: bold;">{{ $totalProfit }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL Potongan Diskon</td>
                    <td style="text-align: right; font-weight: bold;">{{ $totalDiscount }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL Laba Kotor (Sudah Termasuk Potongan)</td>
                    <td style="text-align: right; font-weight: bold;">{{ $totalProfit - $totalDiscount }}</td>
                </tr>
            </tbody>
        </table>
    @elseif($tab === 'buy_price')
        <table>
            <thead>
                <tr><th colspan="5" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Analisa Harga Beli' }}</th></tr>
                <tr><th colspan="5" style="text-align: center;">Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}</th></tr>
                <tr><th colspan="5"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Tanggal Masuk</th>
                    <th style="font-weight: bold; text-align: left;">No. Referensi</th>
                    <th style="font-weight: bold; text-align: center;">Kuantitas</th>
                    <th style="font-weight: bold; text-align: right;">Harga Beli / Unit</th>
                    <th style="font-weight: bold; text-align: right;">Total Nilai Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($priceHistory as $hist)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($hist->transaction->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $hist->transaction->reference_code }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $hist->quantity }}</td>
                    <td style="text-align: right; border: 1px solid #000; color: #ef4444; font-weight: bold;">{{ $hist->price_at_transaction }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $hist->price_at_transaction * $hist->quantity }}</td>
                </tr>
                @endforeach
                @if(count($priceHistory) === 0)
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada riwayat transaksi barang masuk untuk produk ini di rentang waktu terpilih.</td>
                </tr>
                @endif
            </tbody>
        </table>
    @elseif($tab === 'expenses')
        <table>
            <thead>
                <tr><th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold;">{{ $reportTitle ?? 'Laporan Pengeluaran Operasional' }}</th></tr>
                <tr><th colspan="4" style="text-align: center;">Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}</th></tr>
                <tr><th colspan="4"></th></tr>
                <tr>
                    <th style="font-weight: bold; text-align: left;">Tanggal</th>
                    <th style="font-weight: bold; text-align: left;">Kategori / Judul</th>
                    <th style="font-weight: bold; text-align: left;">Catatan</th>
                    <th style="font-weight: bold; text-align: right;">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalExp = 0; @endphp
                @foreach($expenses as $item)
                @php $totalExp += $item->amount; @endphp
                <tr>
                    <td>{{ $item->expense_date->format('d/m/Y') }}</td>
                    <td style="font-weight: bold;">{{ $item->category }}</td>
                    <td>{{ $item->notes ?: '-' }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $item->amount }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL PENGELUARAN</td>
                    <td style="text-align: right; font-weight: bold; color: #ef4444;">{{ $totalExp }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</body>
</html>

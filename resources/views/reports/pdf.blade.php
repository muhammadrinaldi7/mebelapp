<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle ?? 'Laporan' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; padding: 30px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; color: #1e1b4b; }
        .header h2 { font-size: 14px; color: #4f46e5; margin-bottom: 5px; }
        .header p { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #4f46e5; color: #fff; font-size: 10px; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 10px; vertical-align: top; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; color: #fff; }
        .bg-green { background: #10b981; }
        .bg-orange { background: #f97316; }
        .bg-blue { background: #3b82f6; }
        .bg-red { background: #ef4444; }
        .bg-gray { background: #6b7280; }
        .footer { margin-top: 30px; font-size: 9px; text-align: center; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Mebel Stock') }}</h1>
        <h2>{{ $reportTitle ?? 'Laporan' }}</h2>
        <p>
            Periode: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : 'Awal' }} - {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : 'Akhir' }}
            <br>Dicetak: {{ date('d/m/Y H:i:s') }}
        </p>
    </div>

    @if($tab === 'transactions')
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Item Produk</th>
                    <th>Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($transactions as $trx)
                    @foreach($trx->details as $idx => $detail)
                    @php $subtotal = $detail->quantity * $detail->price_at_transaction; $grandTotal += $subtotal; @endphp
                    <tr>
                        <td class="font-bold">{{ $idx === 0 ? $trx->reference_code : '' }}</td>
                        <td>
                            @if($idx === 0)
                                @if($trx->type === 'in') <span class="badge bg-green">Masuk</span>
                                @elseif($trx->type === 'out') <span class="badge bg-orange">Keluar</span>
                                @else <span class="badge bg-blue">Penjualan</span> @endif
                            @endif
                        </td>
                        <td>{{ $idx === 0 ? $trx->transaction_date->format('d/m/Y') : '' }}</td>
                        <td>{{ $idx === 0 ? ($trx->user->name ?? '-') : '' }}</td>
                        <td>{{ $detail->product->name ?? '-' }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="7" class="text-right font-bold">GRAND TOTAL</td>
                    <td class="text-right font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @elseif($tab === 'stock')
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Merek</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Stok Fisik</th>
                    <th class="text-right">Harga Modal</th>
                    <th class="text-right">Nilai Aset</th>
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
                    <td class="text-right font-bold">{{ $product->current_stock }}</td>
                    <td class="text-right">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($asset, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right font-bold">TOTAL KESELURUHAN</td>
                    <td class="text-right font-bold">{{ $totalQty }} Unit</td>
                    <td></td>
                    <td class="text-right font-bold">Rp {{ number_format($totalAsset, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @elseif($tab === 'movement')
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Masuk (In)</th>
                    <th class="text-right">Keluar (Out)</th>
                    <th class="text-right">Terjual (Sale)</th>
                    <th class="text-center">Status Mvmt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productsMove as $pm)
                <tr>
                    <td>{{ $pm->sku }}</td>
                    <td>{{ $pm->name }}</td>
                    <td class="text-right">{{ $pm->total_in }}</td>
                    <td class="text-right">{{ $pm->total_out }}</td>
                    <td class="text-right font-bold">{{ $pm->total_sale }}</td>
                    <td class="text-center">
                        @if($pm->total_sale > 10) <span class="badge bg-green">Fast Moving</span>
                        @elseif($pm->total_sale > 0) <span class="badge bg-blue">Normal</span>
                        @elseif($pm->total_in == 0 && $pm->total_sale == 0) <span class="badge bg-red">Dead Stock</span>
                        @else <span class="badge bg-gray">Slow Moving</span> @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tab === 'profit')
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Referensi</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-right">HPP</th>
                    <th class="text-right">Laba Kotor</th>
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
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($total_profit, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="text-right font-bold">TOTAL KEUNTUNGAN (BELUM POTONGAN)</td>
                    <td class="text-right font-bold">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right font-bold">TOTAL POTONGAN DISKON</td>
                    <td class="text-right font-bold">- Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right font-bold">TOTAL LABA KOTOR (SUDAH TERMASUK POTONGAN)</td>
                    <td class="text-right font-bold">Rp {{ number_format($totalProfit - $totalDiscount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        {{ config('app.name') }} &copy; {{ date('Y') }} — Laporan ini di-generate secara otomatis oleh Sistem Manajemen Stok Mebel.
    </div>
</body>
</html>

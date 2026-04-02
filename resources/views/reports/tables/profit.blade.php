<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Produk & Transaksi</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Qty Terjual</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Harga Jual/Unit</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">HPP / Modal</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Laba Kotor</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white">
        @forelse($profitDetails as $detail)
            @php
                $hpp = $detail->product->base_price ?? 0;
                $profit_per_unit = $detail->price_at_transaction - $hpp;
                $total_profit = $profit_per_unit * $detail->quantity;
            @endphp
            <tr>
                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-500">
                    {{ $detail->transaction->transaction_date->format('d/m/Y') }}
                </td>
                <td class="py-3 px-3 text-sm text-gray-900">
                    <div class="font-medium">{{ $detail->product->name ?? '-' }}</div>
                    <div class="text-xs text-gray-400">Ref: {{ $detail->transaction->reference_code }}</div>
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 font-bold">
                    {{ $detail->quantity }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-600">
                    Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-500">
                    Rp {{ number_format($hpp, 0, ',', '.') }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-bold text-emerald-600">
                    Rp {{ number_format($total_profit, 0, ',', '.') }}
                </td>
            </tr>
        @empty
        <tr>
            <td colspan="6" class="py-8 text-center text-sm text-gray-400">Tidak ada data penjualan pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

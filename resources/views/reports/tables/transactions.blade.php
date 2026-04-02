<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Kode</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tipe</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Detail Produk</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Total</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white">
        @forelse($transactions as $trx)
            @foreach($trx->details as $idx => $detail)
            <tr>
                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $idx === 0 ? $trx->reference_code : '' }}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm">
                    @if($idx === 0)
                        @if($trx->type === 'in')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Masuk</span>
                        @elseif($trx->type === 'out')
                            <span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-700">Keluar</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Penjualan</span>
                        @endif
                    @endif
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $idx === 0 ? $trx->transaction_date->format('d/m/Y') : '' }}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $idx === 0 ? ($trx->user->name ?? '-') : '' }}</td>
                <td class="px-3 py-3 text-sm text-gray-500">
                    <div class="text-xs">{{ $detail->product->name ?? '-' }} (Qty: {{ $detail->quantity }})</div>
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-medium text-gray-900">
                    Rp {{ number_format($detail->quantity * $detail->price_at_transaction, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        @empty
        <tr>
            <td colspan="6" class="py-8 text-center text-sm text-gray-400">Tidak ada data transaksi.</td>
        </tr>
        @endforelse
    </tbody>
</table>

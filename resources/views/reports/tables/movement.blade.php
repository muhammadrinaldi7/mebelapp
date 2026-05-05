<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tanggal</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Kode Referensi</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Produk</th>
            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Tipe Mutasi</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Qty</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Harga/Unit</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Total</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white">
        @forelse($mutations as $mutation)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-500">
                    {{ $mutation->transaction->transaction_date->format('d/m/Y') }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm font-medium text-gray-900">
                    {{ $mutation->transaction->reference_code }}
                </td>
                <td class="px-3 py-3 text-sm text-gray-900">
                    <div class="font-medium">{{ $mutation->product->name ?? '-' }}</div>
                    <div class="text-xs text-gray-400">SKU: {{ $mutation->product->sku ?? '-' }}</div>
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-center">
                    @if($mutation->transaction->type === 'in')
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75"/></svg>
                            MASUK
                        </span>
                    @elseif($mutation->transaction->type === 'out')
                        <span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-700">
                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75"/></svg>
                            KELUAR
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/></svg>
                            TERJUAL
                        </span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-bold text-gray-900">
                    {{ $mutation->quantity }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-600">
                    Rp {{ number_format($mutation->price_at_transaction, 0, ',', '.') }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-bold text-gray-900">
                    Rp {{ number_format($mutation->quantity * $mutation->price_at_transaction, 0, ',', '.') }}
                </td>
            </tr>
        @empty
        <tr>
            <td colspan="7" class="py-8 text-center text-sm text-gray-400">Tidak ada data mutasi barang pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

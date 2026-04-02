<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Produk</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Masuk (In)</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Keluar (Out)</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Terjual (Sale)</th>
            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Status Movement</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white">
        @forelse($productsMove as $pm)
            <tr>
                <td class="py-3 pl-4 pr-3 text-sm text-gray-900">
                    <div class="font-medium">{{ $pm->name }}</div>
                    <div class="text-xs text-gray-500">SKU: {{ $pm->sku }}</div>
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-700">{{ $pm->total_in }}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-700">{{ $pm->total_out }}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-bold text-emerald-600">{{ $pm->total_sale }}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-center">
                    @if($pm->total_sale > 10)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Fast Moving 🔥</span>
                    @elseif($pm->total_sale > 0)
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Normal</span>
                    @elseif($pm->total_in == 0 && $pm->total_sale == 0)
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Dead Stock ⚠️</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700">Slow Moving</span>
                    @endif
                </td>
            </tr>
        @empty
        <tr>
            <td colspan="5" class="py-8 text-center text-sm text-gray-400">Tidak ada pergerakan barang pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

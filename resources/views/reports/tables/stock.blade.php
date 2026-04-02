<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">SKU</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Nama Produk</th>
            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Kategori & Merek</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Fisik (Unit)</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Harga Modal</th>
            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Nilai Aset</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white">
        @forelse($products as $product)
            <tr>
                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                <td class="px-3 py-3 text-sm text-gray-900">{{ $product->name }}</td>
                <td class="px-3 py-3 text-sm text-gray-500">
                    <div>{{ $product->category->name ?? '-' }}</div>
                    <div class="text-xs text-gray-400">{{ $product->brand->name ?? '-' }}</div>
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-bold {{ $product->current_stock <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $product->current_stock }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-500">
                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-right font-medium text-purple-700">
                    Rp {{ number_format($product->current_stock * $product->base_price, 0, ',', '.') }}
                </td>
            </tr>
        @empty
        <tr>
            <td colspan="6" class="py-8 text-center text-sm text-gray-400">Tidak ada data stok.</td>
        </tr>
        @endforelse
    </tbody>
</table>

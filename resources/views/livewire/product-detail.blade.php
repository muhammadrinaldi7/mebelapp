<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Navigation -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}"
                class="p-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition-all text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Detail Produk</h2>
                <p class="text-sm text-gray-500 font-medium">Pantau mutasi stok dan informasi harga</p>
            </div>
        </div>
    </div>

    @if ($product)
        <!-- Info Card (Tanpa Image) -->
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 mb-10">
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-center gap-8">

                    <div class="flex-1">
                        <!-- Category & Brand Badges -->
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <span
                                class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-100">
                                {{ $product->category->name ?? 'General' }}
                            </span>
                            @if ($product->brand)
                                <span
                                    class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                    {{ $product->brand->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Title -->
                        <h1 class="text-4xl font-black text-gray-900 leading-tight mb-2 tracking-tighter">
                            {{ $product->name }}
                        </h1>
                        <p class="text-gray-400 font-mono text-xs mb-6">SKU: {{ $product->sku ?? '-' }}</p>

                        <!-- Highlight Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 py-6 border-t border-gray-50">
                            <!-- Selling Price -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga Jual
                                </p>
                                <p class="text-2xl font-black text-custom">
                                    <span
                                        class="text-sm font-bold mr-0.5">Rp</span>{{ number_format($product->selling_price, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">Modal: Rp
                                    {{ number_format($product->base_price, 0, ',', '.') }}</p>
                            </div>

                            <!-- Current Stock -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok
                                    Tersedia</p>
                                <div class="flex items-baseline gap-1">
                                    <p class="text-2xl font-black text-gray-900">
                                        {{ number_format($product->current_stock, 0, ',', '.') }}</p>
                                    <span
                                        class="text-[10px] font-bold text-gray-500 uppercase">{{ $product->satuan ?? 'Unit' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <div
                                        class="w-1.5 h-1.5 rounded-full {{ $product->current_stock > 5 ? 'bg-green-500' : 'bg-orange-500 animate-pulse' }}">
                                    </div>
                                    <span
                                        class="text-[9px] font-black uppercase {{ $product->current_stock > 5 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $product->current_stock > 5 ? 'Safe' : 'Low Stock' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Last Update -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Terakhir
                                    Diperbarui</p>
                                <p class="text-lg font-black text-gray-800">{{ $product->updated_at->format('d M Y') }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $product->updated_at->format('H:i') }}
                                    WITA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction History Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Riwayat Mutasi Stok</h3>
            <span class="px-2 py-1 bg-white border border-gray-200 rounded text-[10px] font-bold text-gray-500">
                Total {{ $product->transactionDetails->count() }} Transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] bg-gray-50/30">
                        <th class="px-8 py-4 font-bold">Waktu</th>
                        <th class="px-8 py-4 font-bold">Jenis Pergerakan</th>
                        <th class="px-8 py-4 font-bold text-right">Qty</th>
                        <th class="px-8 py-4 font-bold">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($product->transactionDetails->sortByDesc('created_at') as $transaction)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-8 py-4">
                                <div class="text-sm font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-4">
                                @php
                                    $type = $transaction->transaction->type;
                                    $config = [
                                        'in' => [
                                            'bg' => 'bg-green-50',
                                            'text' => 'text-green-700',
                                            'label' => 'Stok Masuk',
                                            'icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3',
                                        ],
                                        'out' => [
                                            'bg' => 'bg-red-50',
                                            'text' => 'text-red-700',
                                            'label' => 'Stok Keluar',
                                            'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18',
                                        ],
                                        'sales' => [
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-700',
                                            'label' => 'Penjualan',
                                            'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                                        ],
                                    ];
                                    $style = $config[$type] ?? $config['out'];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $style['bg'] }} {{ $style['text'] }} border {{ str_replace('bg-', 'border-', $style['bg']) }}">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="{{ $style['icon'] }}"></path>
                                    </svg>
                                    {{ $style['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span
                                    class="text-sm font-black {{ $type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $type === 'in' ? '+' : '-' }}{{ number_format($transaction->quantity, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <p class="text-xs text-gray-500 font-medium leading-relaxed max-w-xs truncate">
                                    {{ $transaction->transaction->notes ?? 'Tanpa catatan' }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-3 bg-gray-50 rounded-2xl mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest italic">Data
                                        Riwayat Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

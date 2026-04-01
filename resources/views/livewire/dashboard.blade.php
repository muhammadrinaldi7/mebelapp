<div>
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">Ringkasan stok dan transaksi mebel Anda.</p>

    {{-- Stats Cards --}}
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Produk</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalProducts }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Merek</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalBrands }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Kategori</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalCategories }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Transaksi Hari Ini</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalStockIn + $totalStockOut + $totalSales }}</dd>
        </div>
    </div>

    {{-- Transaction Summary --}}
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-green-50 border border-green-200 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-green-600">Barang Masuk</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-700">{{ $totalStockIn }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-orange-50 border border-orange-200 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-orange-600">Barang Keluar</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-orange-700">{{ $totalStockOut }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-blue-50 border border-blue-200 px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-blue-600">Penjualan</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-blue-700">{{ $totalSales }}</dd>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    @if($lowStockProducts->count() > 0)
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-red-600">⚠️ Stok Menipis (≤ 5 unit)</h2>
        <div class="mt-3 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-red-50">
                    <tr>
                        <th class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">SKU</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Nama Produk</th>
                        <th class="px-3 py-3 text-right text-sm font-semibold text-gray-900">Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $product->name }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-right">
                            <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">{{ $product->current_stock }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Recent Transactions --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h2>
        <div class="mt-3 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Kode Referensi</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Tipe</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-900">User</th>
                        <th class="px-3 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($recentTransactions as $trx)
                    <tr>
                        <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $trx->reference_code }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm">
                            @if($trx->type === 'in')
                                <span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Masuk</span>
                            @elseif($trx->type === 'out')
                                <span class="inline-flex items-center rounded-md bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700">Keluar</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">Penjualan</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $trx->user->name ?? '-' }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-500">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-sm text-gray-500">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

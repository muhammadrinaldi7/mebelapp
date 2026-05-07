<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Lakukan Stock Opname
            </h2>
            <p class="text-sm text-gray-600">Scan atau cari barang untuk menyesuaikan stok fisik dengan sistem</p>
        </div>
        <a href="{{ route('stock-opname.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
            &larr; Kembali ke Riwayat
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Sidebar: Cari Barang -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Cari Produk</h3>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="searchProduct" type="text"
                        class="block p-4 w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Ketik nama atau SKU produk..." autofocus>
                </div>

                @if (!empty($products))
                    <div class="mt-2 overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <ul class="max-h-60 overflow-auto bg-white">
                            @foreach ($products as $product)
                                <li wire:click="addProduct({{ $product->id }})"
                                    class="cursor-pointer border-b border-gray-100 p-3 hover:bg-gray-50 transition-colors last:border-0">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                                        <span>SKU: {{ $product->sku }}</span>
                                        <span class="rounded bg-blue-100 px-2 py-0.5 text-blue-800">Stok:
                                            {{ $product->current_stock }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(strlen($searchProduct) >= 2)
                    <div class="mt-4 rounded-lg bg-gray-50 p-4 text-center text-sm text-gray-500">
                        Produk tidak ditemukan.
                    </div>
                @endif

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Umum Opname</label>
                    <textarea wire:model="notes" rows="3"
                        class="block w-full p-2 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Contoh: Opname rutin akhir bulan Mei 2026..."></textarea>
                </div>
            </div>
        </div>

        <!-- Main Content: Daftar Item Opname -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 h-full flex flex-col">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Barang Opname</h3>
                </div>

                <div class="flex-1 overflow-x-auto p-0">
                    @if (count($opnameItems) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold tracking-wider text-gray-500 uppercase w-24">
                                        Stok Sistem</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold tracking-wider text-gray-500 uppercase w-32">
                                        Stok Fisik</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold tracking-wider text-gray-500 uppercase w-24">
                                        Selisih</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold tracking-wider text-gray-500 uppercase w-16">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($opnameItems as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                            <div class="text-xs text-gray-500">SKU: {{ $item['sku'] }}</div>
                                            <div class="mt-2">
                                                <input type="text"
                                                    wire:change="updateNotes({{ $index }}, $event.target.value)"
                                                    value="{{ $item['notes'] }}" placeholder="Keterangan (Opsional)"
                                                    class="block w-full rounded border-gray-300 py-1 px-2 text-xs focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-800">
                                                {{ $item['system_stock'] }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <input type="number"
                                                wire:change="updatePhysicalStock({{ $index }}, $event.target.value)"
                                                value="{{ $item['physical_stock'] }}"
                                                class="block w-full rounded-lg border-gray-300 text-center font-semibold focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center font-bold">
                                            @if ($item['difference'] > 0)
                                                <span class="text-green-600">+{{ $item['difference'] }}</span>
                                            @elseif($item['difference'] < 0)
                                                <span class="text-red-600">{{ $item['difference'] }}</span>
                                            @else
                                                <span class="text-gray-500">0</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            <button wire:click="removeProduct({{ $index }})"
                                                class="rounded p-1 text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors"
                                                title="Hapus">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="flex flex-col items-center justify-center h-64 text-center px-4">
                            <div class="rounded-full bg-blue-50 p-3 mb-4">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Belum ada barang</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai cari dan tambahkan barang dari panel di sebelah
                                kiri untuk melakukan opname.</p>
                        </div>
                    @endif
                </div>

                @if (count($opnameItems) > 0)
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Total <span class="font-bold text-gray-900">{{ count($opnameItems) }}</span> barang akan
                            disesuaikan
                        </div>
                        <button wire:click="saveOpname"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Simpan & Selesaikan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

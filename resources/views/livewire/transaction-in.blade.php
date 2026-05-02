<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Barang Masuk</h1>
            <p class="mt-1 text-sm text-gray-500">Catat penerimaan barang ke gudang.</p>
        </div>
        <button wire:click="openForm"
            class="mt-4 sm:mt-0 inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
            + Transaksi Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4">
            <p class="text-sm text-green-700">{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Transaction Form --}}
    @if ($showForm)
        <div class="mt-6 bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Form Barang Masuk</h3>
            <form wire:submit="save" class="mt-4 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Referensi</label>
                        <input wire:model="reference_code" type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('reference_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input wire:model="transaction_date" type="date"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('transaction_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <input wire:model="notes" type="text"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Detail Item</h4>
                    <div class="hidden sm:flex gap-3 mb-2 px-3">
                        <div class="flex-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Produk
                        </div>
                        <div class="w-28 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">Jumlah
                        </div>
                        @if (Auth::user()->hasRole('admin'))
                            <div class="w-40 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">
                                Harga
                                Satuan</div>
                        @endif
                        <div class="w-6"></div>
                    </div>
                    @foreach ($items as $index => $item)
                        <div class="flex flex-col sm:flex-row gap-3 mb-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama
                                    Produk</label>
                                <select wire:model="items.{{ $index }}.product_id"
                                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.{$index}.product_id")
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full sm:w-28">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Jumlah</label>
                                <input wire:model="items.{{ $index }}.quantity" type="number" min="1"
                                    placeholder="Qty"
                                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                @error("items.{$index}.quantity")
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (Auth::user()->hasRole('admin'))
                                <div class="w-full sm:w-40">
                                    <label
                                        class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Harga
                                        Satuan</label>
                                    <input wire:model="items.{{ $index }}.price" type="number" min="0"
                                        placeholder="Harga"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                    @error("items.{$index}.price")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <button type="button" wire:click="removeItem({{ $index }})"
                                class="text-red-500 hover:text-red-700 text-sm font-medium self-center">✕</button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addItem"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 font-medium">+ Tambah Item</button>
                </div>

                <div class="flex justify-end space-x-3 border-t pt-4">
                    <button type="button" wire:click="$set('showForm', false)"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">Simpan
                        Transaksi</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Transaction History --}}
    <div class="mt-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode referensi..."
            class="block w-full sm:w-64 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
    </div>
    <div class="mt-4 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Item</th>
                    @if (Auth::user()->hasRole('admin'))
                        <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total</th>
                        <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($transactions as $trx)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                            {{ $trx->reference_code }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trx->user->name ?? '-' }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            @foreach ($trx->details as $detail)
                                <div>{{ $detail->product->name ?? '-' }} × {{ $detail->quantity }}</div>
                            @endforeach
                        </td>
                        @can('edit-barang-masuk')
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">
                                @if ($trx->total_amount == 0)
                                    <span
                                        class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                        ⚠️ Belum Ada Harga
                                    </span>
                                @else
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-center">
                                <button type="button" wire:click="openEditForm({{ $trx->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 shadow-sm ring-1 ring-inset ring-indigo-600/20 hover:bg-indigo-100">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                            </td>
                        @endcan

                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->hasRole('admin') ? '6' : '4' }}"
                            class="py-4 text-center text-sm text-gray-500">Belum ada transaksi barang
                            masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $transactions->links() }}</div>

    {{-- Edit Prices Modal (Hanya Admin) --}}
    @if ($showEditForm)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form wire:submit="updatePrices">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900 border-b pb-2">Edit
                                        Barang Masuk</h3>

                                    <div class="mt-4 space-y-4">
                                        @foreach ($edit_items as $index => $item)
                                            <div
                                                class="bg-gray-50 p-3 rounded-lg flex flex-col gap-2 border border-gray-100">
                                                <div class="flex justify-between">
                                                    <span
                                                        class="text-sm font-semibold text-gray-800">{{ $item['product_name'] }}</span>
                                                    <span class="text-xs text-gray-500">Qty:
                                                        {{ $item['quantity'] }}</span>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Quantity</label>
                                                    <input wire:model="edit_items.{{ $index }}.quantity"
                                                        type="number"
                                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                                    @error("edit_items.{$index}.quantity")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Harga
                                                        Satuan (Beli)</label>
                                                    <input wire:model="edit_items.{{ $index }}.price"
                                                        type="number" min="0" required
                                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                                    @error("edit_items.{$index}.price")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">Simpan
                                </button>
                                <button type="button" wire:click="$set('showEditForm', false)"
                                    class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

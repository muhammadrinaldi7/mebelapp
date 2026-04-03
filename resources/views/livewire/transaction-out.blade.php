<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Barang Keluar</h1>
            <p class="mt-1 text-sm text-gray-500">Catat pengeluaran barang dari gudang.</p>
        </div>
        <button wire:click="openForm"
            class="mt-4 sm:mt-0 inline-flex items-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500">
            + Transaksi Baru
        </button>
    </div>

    @if ($showForm)
        <div class="mt-6 bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Form Barang Keluar</h3>
            <form wire:submit="save" class="mt-4 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
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
                        <label class="block text-sm font-medium text-gray-700">Alasan Keluar</label>
                        <select wire:model="out_reason"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            <option value="Rusak">Rusak / Cacat</option>
                            <option value="Diperbaiki">Diperbaiki (Servis)</option>
                            <option value="Retur">Retur ke Pabrik/Supplier</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                        @error('out_reason')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                        <input wire:model="notes" type="text" placeholder="Misal: Kaki meja patah"
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
                        <div class="w-40 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">Harga
                            Satuan</div>
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
                                            (Stok: {{ $product->current_stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-28">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Jumlah</label>
                                <input wire:model="items.{{ $index }}.quantity" type="number" min="1"
                                    placeholder="Qty"
                                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>
                            <div class="w-full sm:w-40">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Harga
                                    Satuan</label>
                                <input wire:model="items.{{ $index }}.price" type="number" min="0"
                                    placeholder="Harga"
                                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>
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
                        class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500">Simpan
                        Transaksi</button>
                </div>
            </form>
        </div>
    @endif

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
                    <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total</th>
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
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">Rp
                            {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-sm text-gray-500">Belum ada transaksi barang
                            keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $transactions->links() }}</div>
</div>

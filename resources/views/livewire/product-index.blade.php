<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Produk</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola data produk mebel.</p>
        </div>
        @can('tambah-produk')
            <button wire:click="create"
                class="mt-4 sm:mt-0 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Tambah Produk
            </button>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4">
            <p class="text-sm text-green-700">{{ session('message') }}</p>
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / SKU..."
            class="block w-full sm:w-64 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
        <select wire:model.live="brandFilter"
            class="block w-full sm:w-48 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <option value="">Semua Merek</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="categoryFilter"
            class="block w-full sm:w-48 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-6 overflow-x-auto shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">SKU</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Merek</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kategori</th>
                    @can('lihat-hargaBeli')
                        <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Harga Beli</th>
                    @endcan
                    <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Harga Jual</th>
                    <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Stok</th>
                    <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($products as $product)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                            {{ $product->sku }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->brand->name ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $product->category->name ?? '-' }}</td>
                        @can('lihat-hargaBeli')
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">Rp
                                {{ number_format($product->base_price, 0, ',', '.') }}</td>
                        @endcan
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">Rp
                            {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right">
                            <span
                                class="inline-flex items-center rounded-md {{ $product->current_stock > 5 ? 'bg-green-50 text-green-700 ring-green-600/20' : ($product->current_stock > 0 ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 'bg-red-50 text-red-700 ring-red-600/10') }} px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                {{ $product->current_stock }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium space-x-2">
                            @can('edit-produk')
                                <button wire:click="edit({{ $product->id }})"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            @endcan
                            @can('delete-produk')
                                <button wire:click="confirmDelete({{ $product->id }})"
                                    class="text-red-600 hover:text-red-900">Hapus</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-sm text-gray-500">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                wire:click="$set('showModal', false)"></div>
            <div class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-medium text-gray-900">{{ $editMode ? 'Edit Produk' : 'Tambah Produk' }}</h3>
                <form wire:submit="save" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU</label>
                            <input wire:model="sku" type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            @error('sku')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input wire:model="name" type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Merek</label>
                            <select wire:model="brand_id"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                <option value="">Pilih Merek</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select wire:model="category_id"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Beli</label>
                            <input wire:model="base_price" type="number" step="1"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            @error('base_price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Jual</label>
                            <input wire:model="selling_price" type="number" step="1"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            @error('selling_price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
</div>
@endif

{{-- Delete Confirmation --}}
@if ($confirmingDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
            <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus produk ini? Semua data transaksi
                terkait juga akan terhapus.</p>
            <div class="mt-4 flex justify-end space-x-3">
                <button wire:click="$set('confirmingDelete', false)"
                    class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                <button wire:click="delete"
                    class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Hapus</button>
            </div>
        </div>
    </div>
    </div>
@endif
</div>

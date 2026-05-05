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
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selectedLabel: '',
                                    init() {
                                        const pid = @js($item['product_id']);
                                        if (pid) {
                                            const found = @js($products->map(fn($p) => ['id' => $p->id, 'label' => $p->sku . ' - ' . $p->name])->toArray()).find(p => p.id == pid);
                                            if (found) this.selectedLabel = found.label;
                                        }
                                    },
                                    get filtered() {
                                        if (!this.search) return @js($products->map(fn($p) => ['id' => $p->id, 'label' => $p->sku . ' - ' . $p->name])->toArray());
                                        const s = this.search.toLowerCase();
                                        return @js($products->map(fn($p) => ['id' => $p->id, 'label' => $p->sku . ' - ' . $p->name])->toArray()).filter(p => p.label.toLowerCase().includes(s));
                                    },
                                    select(product) {
                                        this.selectedLabel = product.label;
                                        this.search = '';
                                        this.open = false;
                                        $wire.set('items.{{ $index }}.product_id', product.id);
                                    },
                                    clear() {
                                        this.selectedLabel = '';
                                        this.search = '';
                                        $wire.set('items.{{ $index }}.product_id', '');
                                    }
                                }" @click.outside="open = false" class="relative">
                                    <div class="relative">
                                        <input type="text" x-show="open || !selectedLabel" x-ref="searchInput"
                                            x-model="search" @focus="open = true" @click="open = true"
                                            placeholder="Cari produk..."
                                            class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm" />
                                        <button type="button" x-show="!open && selectedLabel"
                                            @click="open = true; $nextTick(() => $refs.searchInput.focus())"
                                            class="block w-full rounded-md border border-gray-300 px-3 py-2 text-left text-gray-900 shadow-sm hover:bg-gray-50 sm:text-sm bg-white">
                                            <span
                                                x-text="selectedLabel.length > 50 ? selectedLabel.substring(0, 50) + '...' : selectedLabel"
                                                class="truncate block"></span>
                                        </button>
                                        <button type="button" x-show="selectedLabel" @click="clear()"
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400 hover:text-gray-600">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div x-show="open" x-cloak
                                        class="absolute z-50 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black/5 max-h-48 overflow-y-auto">
                                        <template x-for="product in filtered" :key="product.id">
                                            <button type="button" @click="select(product)"
                                                class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                <span
                                                    x-text="product.label.length > 50 ? product.label.substring(0, 50) + '...' : product.label"></span>
                                            </button>
                                        </template>
                                        <div x-show="filtered.length === 0"
                                            class="px-3 py-2 text-sm text-gray-400 italic">Produk tidak ditemukan
                                        </div>
                                    </div>
                                </div>
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
    <div class="flex flex-row mt-5 items-center justify-between">
        <div class="">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode referensi..."
                class="block w-full sm:w-64 bg-white rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
        </div>
        <div class="flex flex-row items-center gap-2">
            <input type="date" wire:model.live.debounce.300ms="start_date"
                class="bg-white rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <input type="date" wire:model.live.debounce.300ms="end_date"
                class="bg-white rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <a href="{{ route('report.export', ['type' => 'pdf', 'tab' => 'movement', 'from' => $this->start_date, 'to' => $this->end_date, 'mt' => 'in']) }}"
                target="_blank"
                class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">PDF</a>
        </div>
    </div>
    <div class="mt-4 overflow-auto shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Catatan</th>
                    {{-- <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Item</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Satuan</th> --}}
                    @if (Auth::user()->hasRole('admin'))
                        <th class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total</th>
                        <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Aksi</th>
                    @endif
                    @can('hapus-barang-masuk')
                        @unless (Auth::user()->hasRole('admin'))
                            <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Aksi</th>
                        @endunless
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($transactions as $trx)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                            <button type="button" wire:click="showDetail({{ $trx->id }})"
                                class="text-indigo-600 hover:text-indigo-900 hover:underline cursor-pointer">{{ $trx->reference_code }}</button>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trx->user->name ?? '-' }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            {{ $trx->notes ?? '-' }}
                        </td>
                        {{-- <td class="px-3 py-4 text-sm text-gray-500">
                            @foreach ($trx->details as $detail)
                                <div>{{ $detail->product->name ?? '-' }}</div>
                            @endforeach
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            @foreach ($trx->details as $detail)
                                <div>{{ $detail->quantity }}</div>
                            @endforeach
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            @foreach ($trx->details as $detail)
                                <div>{{ $detail->product->satuan ?? '-' }}</div>
                            @endforeach
                        </td> --}}

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
                            @can('edit-barang-masuk')
                                <button type="button" wire:click="openEditForm({{ $trx->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 shadow-sm ring-1 ring-inset ring-indigo-600/20 hover:bg-indigo-100">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </button>
                            @endcan
                            @can('hapus-barang-masuk')
                                <button type="button" wire:click="confirmDelete({{ $trx->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-md bg-red-50 px-2 py-1 text-xs font-semibold text-red-700 shadow-sm ring-1 ring-inset ring-red-600/20 hover:bg-red-100">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.519.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>
                            @endcan
                        </td>

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

    {{-- Edit Transaction Modal (Hanya Admin) --}}
    @if ($showEditForm)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                            <div class="flex items-center justify-between border-b pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Edit Transaksi Barang Masuk
                                </h3>
                                <button type="button" wire:click="$set('showEditForm', false)"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Editable Header --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode
                                        Referensi</label>
                                    <input type="text" wire:model="edit_reference_code"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600">
                                    @error('edit_reference_code')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</label>
                                    <input type="date" wire:model="edit_transaction_date"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600">
                                    @error('edit_transaction_date')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</label>
                                    <input type="text" wire:model="edit_notes"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600">
                                </div>
                            </div>

                            {{-- Editable Detail Items --}}
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Kode</th>
                                            <th
                                                class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Produk</th>
                                            <th
                                                class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Satuan</th>
                                            <th
                                                class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-24">
                                                Qty</th>
                                            <th
                                                class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-36">
                                                Harga Satuan</th>
                                            <th
                                                class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($edit_items as $index => $item)
                                            <tr>
                                                <td class="px-4 py-2.5 text-sm text-gray-900">
                                                    {{ $item['sku'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-900">
                                                    {{ $item['product_name'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-700 text-center">
                                                    {{ $item['satuan'] }}</td>
                                                <td class="px-4 py-1.5 text-center">
                                                    <input type="number" min="1"
                                                        wire:model="edit_items.{{ $index }}.quantity"
                                                        class="w-20 rounded-md border border-gray-300 px-2 py-1.5 text-sm text-center text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600">
                                                    @error("edit_items.{$index}.quantity")
                                                        <span
                                                            class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td class="px-4 py-1.5 text-right">
                                                    <input type="number" min="0"
                                                        wire:model="edit_items.{{ $index }}.price"
                                                        class="w-32 rounded-md border border-gray-300 px-2 py-1.5 text-sm text-right text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600">
                                                    @error("edit_items.{$index}.price")
                                                        <span
                                                            class="text-red-500 text-xs block">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td class="px-4 py-2.5 text-sm font-medium text-gray-900 text-right">
                                                    Rp
                                                    {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 py-2.5 text-sm font-semibold text-gray-900 text-right">
                                                Total</td>
                                            <td class="px-4 py-2.5 text-sm font-bold text-gray-900 text-right">
                                                Rp
                                                {{ number_format(collect($edit_items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['price'] ?? 0)), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                            <button type="button" wire:click="updateTransaction"
                                class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                Simpan Perubahan
                            </button>
                            <button type="button" wire:click="$set('showEditForm', false)"
                                class="mt-3 sm:mt-0 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Transaction Modal --}}
    @if ($showDetailModal && $detail_transaction)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                            <div class="flex items-center justify-between border-b pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi Barang Masuk
                                </h3>
                                <button type="button" wire:click="$set('showDetailModal', false)"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex flex-row gap-5 justify-between mb-5">
                                <div class="flex flex-col gap-5">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode
                                            Referensi</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $detail_transaction['reference_code'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            Tanggal</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $detail_transaction['transaction_date'] }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            Dibuat Oleh</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $detail_transaction['user_name'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            Catatan</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $detail_transaction['notes'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="max-h-[50dvh] overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y-2 divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th
                                                class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Kode</th>
                                            <th
                                                class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Produk</th>
                                            <th
                                                class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Qty</th>
                                            <th
                                                class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Satuan</th>
                                            <th
                                                class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Harga Satuan</th>
                                            <th
                                                class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($detail_transaction['details'] as $detail)
                                            <tr>
                                                <td class="px-4 py-2.5 text-sm text-gray-900">
                                                    {{ $detail['sku'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-900">
                                                    {{ $detail['product_name'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-700 text-center">
                                                    {{ $detail['quantity'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-700 text-center">
                                                    {{ $detail['satuan'] }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-700 text-right">
                                                    Rp {{ number_format($detail['price'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-2.5 text-sm font-medium text-gray-900 text-right">
                                                    Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 sticky bottom-0">
                                        <tr>
                                            <td colspan="4"
                                                class="px-4 py-2.5 text-sm font-semibold text-gray-900 text-right">
                                                Total</td>
                                            <td class="px-4 py-2.5 text-sm font-bold text-gray-900 text-right">
                                                {{ $detail_transaction['totalQty'] }}
                                            </td>
                                            <td class="px-4 py-2.5 text-sm font-bold text-gray-900 text-right">
                                                Rp
                                                {{ number_format($detail_transaction['total_amount'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" wire:click="$set('showDetailModal', false)"
                                class="inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteConfirm)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Transaksi
                                        Barang Masuk</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus
                                            transaksi ini? Stok produk akan dikurangi sesuai jumlah yang
                                            tercatat pada transaksi. Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" wire:click="deleteTransaction"
                                class="inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Ya,
                                Hapus</button>
                            <button type="button" wire:click="$set('showDeleteConfirm', false)"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

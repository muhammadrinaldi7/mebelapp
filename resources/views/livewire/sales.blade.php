<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penjualan</h1>
            <p class="mt-1 text-sm text-gray-500">Catat Penjualan, Info Pelanggan, dan Cetak Nota.</p>
        </div>
        <button wire:click="openForm"
            class="mt-4 sm:mt-0 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 active:scale-95 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Penjualan Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 rounded-xl bg-green-50 p-4 ring-1 ring-green-500/20">
            <p class="text-sm font-medium text-green-800 flex items-center gap-2"><svg class="h-4 w-4"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mt-4 rounded-xl bg-red-50 p-4 ring-1 ring-red-500/20">
            <p class="text-sm font-medium text-red-800 flex items-center gap-2"><svg class="h-4 w-4" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                        clip-rule="evenodd" />
                </svg>{{ session('error') }}</p>
        </div>
    @endif

    @if ($showForm)
        <div class="mt-6 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl p-6 relative overflow-hidden" x-data="{ catalogProducts: @js($products->map(fn($p) => ['id' => $p->id, 'sku' => $p->sku, 'name' => $p->name, 'stock' => $p->current_stock, 'price' => number_format($p->selling_price, 0, ',', '.'), 'label' => '[' . ($p->sku ?: '-') . '] ' . $p->name . ' (stok: ' . $p->current_stock . ')'])->toArray()) }">
            <div class="absolute inset-x-0 top-0 h-1 bg-linear-to-r from-blue-500 to-indigo-500"></div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Formulir Penjualan</h3>
            <form wire:submit="save" class="space-y-6">

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="col-span-full">
                        <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-3"># Informasi Dasar</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kode
                            Referensi / Invoice</label>
                        <input wire:model="reference_code" type="text"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('reference_code')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tanggal
                            Penjualan</label>
                        <input wire:model="transaction_date" type="date"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('transaction_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama
                            Sales / Penjual</label>
                        <input wire:model="salesperson_name" type="text" placeholder="Misal: Bapak Andi"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('salesperson_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Keterangan
                            Umum</label>
                        <input wire:model="notes" type="text" placeholder="opsional..."
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="col-span-full mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-3 mt-4"># Detail Pembeli
                            (Tercetak di Nota)</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama
                            Pelanggan (Yth.)</label>
                        <input wire:model="customer_name" type="text" placeholder="Bapak Budi..."
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('customer_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">No.
                            Telepon / WA</label>
                        <input wire:model="customer_phone" type="text" placeholder="08123..."
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('customer_phone')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Alamat
                            Pengiriman</label>
                        <input wire:model="customer_address" type="text" placeholder="Jl. Raya..."
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @error('customer_address')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-full mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-3 mt-4"># Pengiriman</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Armada /
                            Pengiriman</label>
                        <select wire:model.live="shipping_status"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="bawa_sendiri">Pembeli Bawa Sendiri</option>
                            <option value="menunggu_dikirim">Menunggu Jadwal Dikirim</option>
                            <option value="sedang_dikirim">Sedang Di Perjalanan</option>
                            <option value="sudah_diterima">Selesai Dikirim/Diterima</option>
                        </select>
                    </div>
                    @if ($shipping_status !== 'bawa_sendiri')
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama
                                Supir / Driver</label>
                            <input wire:model="driver_name" type="text" placeholder="Misal: Bapak Anto"
                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            @error('driver_name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-4"># Daftar Item Mebel</h4>

                    <div class="hidden sm:flex items-center gap-3 px-1 mb-2">
                        <div class="flex-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Produk</div>
                        <div class="w-32 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah</div>
                        <div class="w-48 text-xs font-semibold text-gray-500 uppercase tracking-wide">Harga Satuan
                        </div>
                        <div class="w-8"></div>
                    </div>

                    @foreach ($items as $index => $item)
                        @php $itemMode = $item['mode'] ?? 'catalog'; @endphp
                        <div
                            class="flex flex-col sm:flex-row gap-3 p-3 {{ $itemMode === 'manual' ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-100' }} border rounded-xl mb-3 relative items-end">

                            {{-- Mode Toggle --}}
                            <div class="absolute top-0 left-1 z-10">
                                <button type="button" wire:click="toggleItemMode({{ $index }})"
                                    class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full transition-colors {{ $itemMode === 'manual' ? 'bg-amber-200 text-amber-800 hover:bg-amber-300' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    @if ($itemMode === 'manual')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        PO / Manual
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        Katalog
                                    @endif
                                </button>
                            </div>

                            <div class="flex-1 w-full mt-5 sm:mt-0">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    {{ $itemMode === 'manual' ? 'Nama Produk (PO)' : 'Produk' }}
                                </label>

                                @if ($itemMode === 'manual')
                                    {{-- Manual/PO Input --}}
                                    <input type="text" wire:model="items.{{ $index }}.custom_name"
                                        placeholder="Ketik nama produk PO, misal: Sofa L Custom Biru..."
                                        class="block w-full rounded-xl border border-amber-300 px-3 py-2 text-sm text-gray-900 shadow-sm bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20" />
                                    @error('items.' . $index . '.custom_name')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                @else
                                    {{-- Catalog Searchable Select --}}
                                    <div x-data="{
                                        open: false,
                                        search: '',
                                        selectedLabel: '',
                                        init() {
                                            const pid = @js($item['product_id']);
                                            if (pid) {
                                                const found = catalogProducts.find(p => p.id == pid);
                                                if (found) this.selectedLabel = found.label;
                                            }
                                        },
                                        get filtered() {
                                            if (!this.search) return catalogProducts;
                                            const s = this.search.toLowerCase();
                                            return catalogProducts.filter(p => p.name.toLowerCase().includes(s) || (p.sku && p.sku.toLowerCase().includes(s)));
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
                                                placeholder="Cari nama atau SKU produk..."
                                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                                            <button type="button" x-show="!open && selectedLabel"
                                                @click="open = true; $nextTick(() => $refs.searchInput.focus())"
                                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-left text-sm text-gray-900 shadow-sm hover:bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-white">
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
                                            class="absolute z-50 mt-1 w-full rounded-xl bg-white shadow-lg ring-1 ring-black/5 max-h-48 overflow-y-auto">
                                            <template x-for="product in filtered" :key="product.id">
                                                <button type="button" @click="select(product)"
                                                    class="block w-full px-3 py-2.5 text-left border-b border-gray-50 hover:bg-blue-50 focus:bg-blue-50 transition-colors last:border-0">
                                                    <div class="text-sm font-semibold text-gray-900"
                                                        x-text="product.name"></div>
                                                    <div
                                                        class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                                        <span x-text="product.sku"
                                                            class="bg-gray-100 px-1.5 py-0.5 rounded text-[10px] font-mono text-gray-600"></span>
                                                        <span>Stok: <span x-text="product.stock"
                                                                class="font-medium text-gray-700"></span></span>
                                                        <span class="text-gray-300">|</span>
                                                        <span class="font-medium text-blue-600">Rp <span
                                                                x-text="product.price"></span></span>
                                                    </div>
                                                </button>
                                            </template>
                                            <div x-show="filtered.length === 0"
                                                class="px-3 py-3 text-sm text-gray-400 italic text-center">Produk tidak
                                                ditemukan
                                            </div>
                                        </div>
                                    </div>
                                    @error('items.' . $index . '.product_id')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                            <div class="w-full sm:w-32">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Qty</label>
                                <input wire:model.live="items.{{ $index }}.quantity" type="number"
                                    min="1"
                                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            <div class="w-full sm:w-48">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Harga
                                    Satuan (Rp)</label>
                                <div x-data="{
                                    raw: $wire.entangle('items.{{ $index }}.price').live,
                                    displayValue: '',
                                    init() {
                                        this.$watch('raw', value => {
                                            if (value !== undefined && value !== null && value !== '') {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                            } else {
                                                this.displayValue = '';
                                            }
                                        });
                                        if (this.raw !== undefined && this.raw !== null && this.raw !== '') {
                                            this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                        }
                                    },
                                    updateValue(val) {
                                        let rawVal = val.toString().replace(/\D/g, '');
                                        this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                        this.raw = rawVal;
                                    }
                                }">
                                    <input type="text" x-model="displayValue"
                                        x-on:input="updateValue($event.target.value)"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 text-right shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="0">
                                </div>
                            </div>
                            <button type="button" wire:click="removeItem({{ $index }})"
                                class="absolute top-2 right-2 sm:relative sm:top-0 sm:right-0 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg p-2 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addItem"
                        class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-500 bg-blue-50 px-4 py-2 rounded-xl transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Item Lain
                    </button>
                </div>


                {{-- Kalkulasi Total --}}
                <div class="mt-8 bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <div class="flex flex-col items-end w-full space-y-3">
                        <div class="flex justify-between w-full max-w-sm">
                            <span class="text-gray-500 font-medium">Subtotal Mebel:</span>
                            <span class="text-gray-900 font-semibold">Rp
                                {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between w-full max-w-sm items-center">
                            <label class="text-gray-500 font-medium flex-1 cursor-pointer">Ongkos Kirim (+):</label>
                            <div class="relative w-32">
                                <div x-data="{
                                    raw: $wire.entangle('shipping_cost').live,
                                    displayValue: '',
                                    init() {
                                        this.$watch('raw', value => {
                                            if (value !== undefined && value !== null && value !== '') {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                            } else {
                                                this.displayValue = '';
                                            }
                                        });
                                        if (this.raw !== undefined && this.raw !== null && this.raw !== '') {
                                            this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                        }
                                    },
                                    updateValue(val) {
                                        let rawVal = val.toString().replace(/\D/g, '');
                                        this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                        this.raw = rawVal;
                                    }
                                }">
                                    <input type="text" x-model="displayValue"
                                        x-on:input="updateValue($event.target.value)"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-right text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full max-w-sm items-center">
                            <label class="font-medium flex-1 cursor-pointer text-red-500">Diskon (-):</label>
                            <div class="relative w-32">
                                <div x-data="{
                                    raw: $wire.entangle('discount').live,
                                    displayValue: '',
                                    init() {
                                        this.$watch('raw', value => {
                                            if (value !== undefined && value !== null && value !== '') {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                            } else {
                                                this.displayValue = '';
                                            }
                                        });
                                        if (this.raw !== undefined && this.raw !== null && this.raw !== '') {
                                            this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                        }
                                    },
                                    updateValue(val) {
                                        let rawVal = val.toString().replace(/\D/g, '');
                                        this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                        this.raw = rawVal;
                                    }
                                }">
                                    <input type="text" x-model="displayValue"
                                        x-on:input="updateValue($event.target.value)"
                                        class="block w-full rounded-lg border border-red-300 px-3 py-1.5 text-sm text-right text-red-700 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-sm border-t border-gray-300 my-1"></div>
                        <div class="flex justify-between w-full max-w-sm">
                            <span class="text-lg font-bold text-gray-900">GRAND TOTAL:</span>
                            <span class="text-xl font-bold text-blue-600">Rp
                                {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rincian Pembayaran --}}
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-4"># Rincian Pembayaran</h4>
                    <div class="space-y-3">
                        @foreach ($payments as $pIndex => $payment)
                            <div class="flex flex-col sm:flex-row gap-3 p-3 bg-blue-50/50 rounded-xl border border-blue-100"
                                wire:key="payment-{{ $pIndex }}">
                                <div class="flex-1">
                                    <label
                                        class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Metode
                                        Pembayaran</label>
                                    <select wire:model.live="payments.{{ $pIndex }}.payment_method_id"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                        <option value="">Pilih Metode...</option>
                                        @foreach ($paymentMethods as $pm)
                                            <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("payments.{$pIndex}.payment_method_id")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full sm:w-48">
                                    <label
                                        class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nominal
                                        (Rp)
                                    </label>
                                    <div x-data="{
                                        raw: $wire.entangle('payments.{{ $pIndex }}.amount').live,
                                        displayValue: '',
                                        init() {
                                            this.$watch('raw', value => {
                                                if (value !== undefined && value !== null && value !== '' && value != 0) {
                                                    this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                                } else {
                                                    this.displayValue = '';
                                                }
                                            });
                                            if (this.raw !== undefined && this.raw !== null && this.raw !== '' && this.raw != 0) {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                            }
                                        },
                                        updateValue(val) {
                                            let rawVal = val.toString().replace(/\D/g, '');
                                            this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                            this.raw = rawVal;
                                        }
                                    }">
                                        <input type="text" x-model="displayValue"
                                            x-on:input="updateValue($event.target.value)"
                                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 text-right shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                            placeholder="0">
                                    </div>
                                    @error("payments.{$pIndex}.amount")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (count($payments) > 1)
                                    <button type="button" wire:click="removePayment({{ $pIndex }})"
                                        class="self-center bg-red-100 text-red-600 hover:bg-red-200 rounded-lg p-2 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" wire:click="addPayment"
                        class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-500 bg-blue-50 px-4 py-2 rounded-xl transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Metode Pembayaran (Split)
                    </button>

                    {{-- Payment Summary --}}
                    <div class="mt-4 bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-emerald-700">Total Dibayar:</span>
                            <span class="text-lg font-bold text-emerald-700">Rp
                                {{ number_format($this->totalPaid, 0, ',', '.') }}</span>
                        </div>
                        @if ($this->remainingBalance > 0)
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm font-semibold text-amber-700">Sisa Tagihan:</span>
                                <span class="text-lg font-bold text-amber-700">Rp
                                    {{ number_format($this->remainingBalance, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs font-semibold text-gray-500">Status Otomatis:</span>
                            @if ($this->totalPaid >= $this->grandTotal && $this->grandTotal > 0)
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-700">LUNAS</span>
                            @elseif ($this->totalPaid > 0)
                                <span
                                    class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-700">DP
                                    / CICILAN</span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700">BELUM
                                    DIBAYAR</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" wire:click="$set('showForm', false)"
                        class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 active:scale-95 transition-transform flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.742 3.742 0 0115 19.5H6.75z" />
                        </svg>
                        Proses Transaksi & Simpan
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Daftar Penjualan --}}
    <div class="mt-8 flex items-center gap-3">
        <label for="search" class="sr-only">Cari</label>
        <div class="relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" id="search"
                placeholder="Cari nota / pembeli..."
                class="block w-full sm:w-72 rounded-xl border border-gray-300 py-2.5 pl-9 pr-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
        </div>
    </div>

    <div class="mt-4 overflow-hidden shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Identitas Nota</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pelanggan</th>
                        {{-- <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Mebel Terjual</th> --}}
                        <th
                            class="px-3 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-x border-gray-200">
                            Kalkulasi Total</th>
                        <th
                            class="px-3 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($transactions as $trx)
                        @php
                            $sub = $trx->total_amount;
                            $disc = $trx->discount ?? 0;
                            $ship = $trx->shipping_cost ?? 0;
                            $grand = $sub - $disc + $ship;
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm">
                                <button type="button" wire:click="openDetailModal({{ $trx->id }})"
                                    class="font-bold text-blue-600 hover:text-blue-800 hover:underline focus:outline-none">{{ $trx->reference_code }}</button>
                                <div class="text-xs font-medium text-gray-500 mt-0.5 mb-1.5">
                                    {{ $trx->transaction_date->format('d/m/Y') }}</div>
                                <div class="flex flex-col gap-1 items-start mt-1">
                                    @if ($trx->payment_status === 'lunas')
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-[10px] font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Lunas</span>
                                    @elseif($trx->payment_status === 'dp')
                                        <span
                                            class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-[10px] font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"
                                            title="DP: Rp {{ number_format($trx->down_payment, 0, ',', '.') }}">DP
                                            (Hutang)
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-[10px] font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Belum
                                            Bayar</span>
                                    @endif

                                    @if ($trx->shipping_status === 'bawa_sendiri')
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Bawa
                                            Sendiri</span>
                                    @elseif($trx->shipping_status === 'sudah_diterima')
                                        <span
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Terkirim</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-[10px] font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">{{ str_replace('_', ' ', Str::title($trx->shipping_status)) }}</span>
                                    @endif

                                    @if ($trx->is_preorder)
                                        <span
                                            class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">PO
                                            / Indent</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-3 text-sm">
                                @if ($trx->customer_name)
                                    <div class="font-medium text-gray-900">{{ $trx->customer_name }}</div>
                                    @if ($trx->customer_phone)
                                        <div class="text-xs text-gray-500">☎ {{ $trx->customer_phone }}</div>
                                    @endif
                                @else
                                    <div class="text-gray-400 italic text-xs">Umum (Tidak terdata)</div>
                                @endif

                                @if ($trx->salesperson_name)
                                    <div
                                        class="mt-2 text-[10px] font-semibold text-indigo-600 bg-indigo-50 inline-flex items-center gap-1 px-1.5 py-0.5 rounded border border-indigo-100">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        Sales: {{ $trx->salesperson_name }}
                                    </div>
                                @endif
                            </td>
                            {{-- <td class="px-3 py-4 text-sm text-gray-500">
                                @foreach ($trx->details as $detail)
                                    <div class="text-xs leading-5">
                                        <span class="font-semibold text-gray-700">{{ $detail->quantity }}x</span>
                                        {{ $detail->product->name ?? '-' }}
                                    </div>
                                @endforeach
                            </td> --}}
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right border-x border-gray-100">
                                <div class="text-xs text-gray-400">Bruto: {{ number_format($sub, 0, ',', '.') }}</div>
                                @if ($disc > 0)
                                    <div class="text-xs text-red-500">Disc: -{{ number_format($disc, 0, ',', '.') }}
                                    </div>
                                @endif
                                @if ($ship > 0)
                                    <div class="text-xs text-green-600">Ongkir:
                                        +{{ number_format($ship, 0, ',', '.') }}
                                    </div>
                                @endif
                                <div class="font-bold text-gray-900 mt-1 pt-1 border-t border-gray-100">Rp
                                    {{ number_format($grand, 0, ',', '.') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-center">
                                <div class="flex flex-col gap-2 items-center">
                                    <button type="button" wire:click="openEditForm({{ $trx->id }})"
                                        class="inline-flex w-full justify-center items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 shadow-sm ring-1 ring-inset ring-indigo-700/10 hover:bg-indigo-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Update Status
                                    </button>
                                    {{-- <a href="{{ route('sales.invoice', $trx->id) }}" target="_blank"
                                        class="inline-flex w-full justify-center items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-900 hover:bg-gray-800 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v3.396c0 .621.504 1.125 1.125 1.125h8.25c.621 0 1.125-.504 1.125-1.125v-3.396zm-9-5.25C8.25 7.618 9.382 6.5 10.75 6.5h2.5c1.368 0 2.5 1.118 2.5 2.5v.75m-6-1.5z" />
                                        </svg>
                                        Cetak Nota
                                    </a>
                                    <a href="{{ route('sales.delivery_note', $trx->id) }}" target="_blank"
                                        class="inline-flex w-full justify-center items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-600/20 hover:bg-emerald-100 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                        </svg>
                                        Surat Jalan
                                    </a> --}}
                                    {{-- Tombol Cetak Dot Matrix (Epson LX-310 via RawBT) --}}
                                    {{-- <button type="button" onclick="printDotMatrix({{ $trx->id }}, this)"
                                        class="inline-flex w-full justify-center items-center gap-1.5 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm ring-1 ring-inset ring-amber-600/20 hover:bg-amber-100 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V10.5" />
                                        </svg>
                                        <span class="print-label">Cetak LX-310</span>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-gray-500">Belum ada riwayat
                                penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $transactions->links() }}</div>

    {{-- Modal Update Status & Pembayaran --}}
    @if ($showEditForm)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">

                        {{-- Header --}}
                        <div
                            class="bg-linear-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">Kelola Pembayaran & Pengiriman</h3>
                                <p class="text-indigo-100 text-sm mt-1">Update status transaksi</p>
                            </div>
                            <button wire:click="$set('showEditForm', false)"
                                class="text-indigo-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Ringkasan Tagihan --}}
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-600">Grand Total:</span>
                                    <span class="text-lg font-bold text-gray-900">Rp
                                        {{ number_format($this->editGrandTotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-emerald-600">Total Dibayar:</span>
                                    <span class="text-lg font-bold text-emerald-600">Rp
                                        {{ number_format($this->editTotalPaid, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span
                                        class="text-sm font-bold {{ $this->editRemaining > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                        {{ $this->editRemaining > 0 ? 'Sisa Tagihan:' : 'Status:' }}
                                    </span>
                                    @if ($this->editRemaining > 0)
                                        <span class="text-lg font-bold text-amber-700">Rp
                                            {{ number_format($this->editRemaining, 0, ',', '.') }}</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-700">✓
                                            LUNAS</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Riwayat Pembayaran yang Sudah Tersimpan --}}
                            @if (count($edit_existing_payments) > 0)
                                <div>
                                    <h4
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                        Riwayat Pembayaran</h4>
                                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Tanggal
                                                    </th>
                                                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Metode
                                                    </th>
                                                    <th class="px-4 py-2 text-right font-semibold text-gray-600">
                                                        Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach ($edit_existing_payments as $ep)
                                                    <tr>
                                                        <td class="px-4 py-2 text-gray-600">
                                                            {{ $ep['payment_date'] }}</td>
                                                        <td class="px-4 py-2 text-gray-900 font-medium">
                                                            {{ $ep['method_name'] }}</td>
                                                        <td class="px-4 py-2 text-right font-semibold text-gray-900">
                                                            Rp
                                                            {{ number_format($ep['amount'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Form Tambah Pembayaran Baru --}}
                            @if ($this->editRemaining > 0)
                                <div>
                                    <h4
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                        Tambah Pembayaran (Pelunasan)</h4>
                                    <div class="space-y-3">
                                        @foreach ($edit_new_payments as $npIndex => $np)
                                            <div class="flex flex-col sm:flex-row gap-3 p-3 bg-blue-50/50 rounded-xl border border-blue-100"
                                                wire:key="edit-payment-{{ $npIndex }}">
                                                <div class="flex-1">
                                                    <select
                                                        wire:model="edit_new_payments.{{ $npIndex }}.payment_method_id"
                                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                                        <option value="">Pilih Metode...</option>
                                                        @foreach ($paymentMethods as $pm)
                                                            <option value="{{ $pm->id }}">{{ $pm->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("edit_new_payments.{$npIndex}.payment_method_id")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="w-full sm:w-48">
                                                    <div x-data="{
                                                        raw: $wire.entangle('edit_new_payments.{{ $npIndex }}.amount'),
                                                        displayValue: '',
                                                        init() {
                                                            this.$watch('raw', value => {
                                                                if (value !== undefined && value !== null && value !== '' && value != 0) {
                                                                    this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                                                } else {
                                                                    this.displayValue = '';
                                                                }
                                                            });
                                                            if (this.raw !== undefined && this.raw !== null && this.raw !== '' && this.raw != 0) {
                                                                this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                                            }
                                                        },
                                                        updateValue(val) {
                                                            let rawVal = val.toString().replace(/\D/g, '');
                                                            this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                                            this.raw = rawVal;
                                                        }
                                                    }">
                                                        <input type="text" x-model="displayValue"
                                                            x-on:input="updateValue($event.target.value)"
                                                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 text-right shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                                            placeholder="0">
                                                    </div>
                                                    @error("edit_new_payments.{$npIndex}.amount")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <button type="button"
                                                    wire:click="removeEditPayment({{ $npIndex }})"
                                                    class="self-center bg-red-100 text-red-600 hover:bg-red-200 rounded-lg p-2 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" wire:click="addEditPayment"
                                        class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-500 bg-blue-50 px-4 py-2 rounded-xl transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Tambah Pembayaran
                                    </button>
                                </div>
                            @endif

                            {{-- Pengiriman --}}
                            <div>
                                <h4
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                    Status Pengiriman</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Armada
                                            / Pengiriman</label>
                                        <select wire:model.live="edit_shipping_status"
                                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="bawa_sendiri">Pembeli Bawa Sendiri</option>
                                            <option value="menunggu_dikirim">Menunggu Dikirim</option>
                                            <option value="sedang_dikirim">Sedang Di Perjalanan</option>
                                            <option value="sudah_diterima">Selesai Dikirim/Diterima</option>
                                        </select>
                                    </div>
                                    @if ($edit_shipping_status !== 'bawa_sendiri')
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama
                                                Supir / Driver</label>
                                            <input wire:model="edit_driver_name" type="text"
                                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            @error('edit_driver_name')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" wire:click="$set('showEditForm', false)"
                                class="inline-flex justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="button" wire:click="updateStatus"
                                class="inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    {{-- Modal Detail Transaksi --}}
    @if ($showDetailModal && $selectedTransaction)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">

                        <!-- Header -->
                        <div
                            class="bg-linear-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">Detail Penjualan:
                                    {{ $selectedTransaction->reference_code }}</h3>
                                <p class="text-blue-100 text-sm mt-1">
                                    {{ \Carbon\Carbon::parse($selectedTransaction->transaction_date)->format('d F Y') }}
                                </p>
                            </div>
                            <button wire:click="closeDetailModal"
                                class="text-blue-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6">
                            <!-- Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                                <!-- Customer Info -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <h4
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                        Informasi Pelanggan</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Nama:</span>
                                            <span
                                                class="font-semibold text-gray-900">{{ $selectedTransaction->customer_name ?: 'Umum (Tidak Terdata)' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Telepon/WA:</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $selectedTransaction->customer_phone ?: '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Alamat:</span>
                                            <span
                                                class="font-medium text-gray-900 text-right">{{ $selectedTransaction->customer_address ?: '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Sales/Penjual:</span>
                                            <span
                                                class="font-medium text-indigo-600">{{ $selectedTransaction->salesperson_name ?: '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Info -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <h4
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                        Status & Pengiriman</h4>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Pembayaran:</span>
                                            @if ($selectedTransaction->payment_status === 'lunas')
                                                <span
                                                    class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-bold">LUNAS</span>
                                            @elseif($selectedTransaction->payment_status === 'dp')
                                                <span
                                                    class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs font-bold">DP
                                                    / HUTANG</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-bold">BELUM
                                                    DIBAYAR</span>
                                            @endif
                                        </div>
                                        @php
                                            $grandTotal =
                                                $selectedTransaction->total_amount -
                                                $selectedTransaction->discount +
                                                $selectedTransaction->shipping_cost;
                                            $totalPaid = $selectedTransaction->payments->sum('amount');
                                            $remaining = max(0, $grandTotal - $totalPaid);
                                        @endphp
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Total Dibayar:</span>
                                            <span class="font-bold text-emerald-600">Rp
                                                {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                        </div>
                                        @if ($remaining > 0)
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Sisa Tagihan:</span>
                                                <span class="font-bold text-red-600">Rp
                                                    {{ number_format($remaining, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Pengiriman:</span>
                                            <span
                                                class="px-2 py-1 rounded bg-gray-200 text-gray-700 text-xs font-bold uppercase">{{ str_replace('_', ' ', $selectedTransaction->shipping_status) }}</span>
                                        </div>
                                        @if ($selectedTransaction->driver_name)
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Nama Supir:</span>
                                                <span
                                                    class="font-medium text-gray-900">{{ $selectedTransaction->driver_name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Pembayaran -->
                            @if ($selectedTransaction->payments->count() > 0)
                                <h4
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                    Riwayat Pembayaran</h4>
                                <div class="overflow-x-auto rounded-xl border border-gray-200 mb-6">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal
                                                </th>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Metode</th>
                                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Nominal
                                                </th>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Catatan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach ($selectedTransaction->payments as $pIdx => $payment)
                                                <tr>
                                                    <td class="px-4 py-3 text-gray-500">{{ $pIdx + 1 }}</td>
                                                    <td class="px-4 py-3 text-gray-600">
                                                        {{ $payment->payment_date->format('d/m/Y') }}</td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                                            {{ $payment->paymentMethod->name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right font-bold text-gray-900">Rp
                                                        {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-3 text-gray-500 text-xs">
                                                        {{ $payment->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Items Table -->
                            <h4
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-gray-200 pb-2">
                                Rincian Produk</h4>
                            <div class="overflow-x-auto rounded-xl border border-gray-200 mb-6">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk</th>
                                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Harga Satuan
                                            </th>
                                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Qty</th>
                                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($selectedTransaction->details as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    @if ($item->product_id)
                                                        <div class="font-medium text-gray-900">
                                                            {{ $item->product->name ?? '-' }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $item->product->sku ?? '' }}
                                                        </div>
                                                    @else
                                                        <div class="font-medium text-gray-900">
                                                            {{ $item->custom_product_name ?? 'Item Manual' }}
                                                        </div>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span
                                                                class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800 ring-1 ring-inset ring-amber-600/20">PO
                                                                / INDENT</span>
                                                            <button type="button"
                                                                wire:click="openConvertModal({{ $item->id }})"
                                                                class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-0.5 rounded-md hover:bg-blue-100 transition-colors">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                                                </svg>
                                                                Convert ke Produk
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center text-gray-600">Rp
                                                    {{ number_format($item->price_at_transaction, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-center font-semibold">{{ $item->quantity }}
                                                </td>
                                                <td class="px-4 py-3 text-right font-bold text-gray-800">Rp
                                                    {{ number_format($item->price_at_transaction * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals -->
                            <div class="flex justify-end">
                                <div
                                    class="w-full md:w-1/2 lg:w-1/3 space-y-2 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Subtotal:</span>
                                        <span class="font-semibold text-gray-900">Rp
                                            {{ number_format($selectedTransaction->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($selectedTransaction->discount > 0)
                                        <div class="flex justify-between text-red-500">
                                            <span>Diskon:</span>
                                            <span class="font-semibold">- Rp
                                                {{ number_format($selectedTransaction->discount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if ($selectedTransaction->shipping_cost > 0)
                                        <div class="flex justify-between text-green-600">
                                            <span>Ongkos Kirim:</span>
                                            <span class="font-semibold">+ Rp
                                                {{ number_format($selectedTransaction->shipping_cost, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                                        <span class="font-bold text-gray-900">GRAND TOTAL:</span>
                                        <span class="text-lg font-black text-blue-600">Rp
                                            {{ number_format($selectedTransaction->total_amount - $selectedTransaction->discount + $selectedTransaction->shipping_cost, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($selectedTransaction->notes)
                                <div
                                    class="mt-4 p-3 bg-yellow-50 text-yellow-800 rounded-lg text-sm border border-yellow-200">
                                    <span class="font-bold">Catatan:</span> {{ $selectedTransaction->notes }}
                                </div>
                            @endif

                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <a href="{{ route('sales.invoice', $selectedTransaction->id) }}" target="_blank"
                                class="inline-flex justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition-colors">
                                Cetak Nota
                            </a>
                            <button type="button" wire:click="closeDetailModal"
                                class="inline-flex justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Convert PO to Product --}}
    @if ($showConvertModal)
        <div class="relative z-50" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                        <!-- Header -->
                        <div
                            class="bg-linear-to-r from-amber-500 to-orange-500 px-6 py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">Konversi ke Produk</h3>
                                <p class="text-amber-100 text-sm mt-0.5">Buat master data produk dari item PO</p>
                            </div>
                            <button wire:click="closeConvertModal"
                                class="text-amber-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nama
                                        Produk</label>
                                    <input wire:model="convert_name" type="text"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                    @error('convert_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">SKU
                                        / Kode Produk</label>
                                    <input wire:model="convert_sku" type="text" placeholder="Misal: MBL-SF-001"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                    @error('convert_sku')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Satuan</label>
                                    <input wire:model="convert_satuan" type="text" placeholder="pcs"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                                    <select wire:model="convert_category_id"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                        <option value="">Pilih Kategori...</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('convert_category_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Merek</label>
                                    <select wire:model="convert_brand_id"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                        <option value="">Pilih Merek...</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('convert_brand_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Harga
                                        Modal (Rp)</label>
                                    <div x-data="{
                                        raw: $wire.entangle('convert_base_price'),
                                        displayValue: '',
                                        init() {
                                            this.$watch('raw', value => {
                                                if (value !== undefined && value !== null && value !== '' && value != 0) {
                                                    this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                                } else {
                                                    this.displayValue = '';
                                                }
                                            });
                                            if (this.raw !== undefined && this.raw !== null && this.raw !== '' && this.raw != 0) {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                            }
                                        },
                                        updateValue(val) {
                                            let rawVal = val.toString().replace(/\D/g, '');
                                            this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                            this.raw = rawVal;
                                        }
                                    }">
                                        <input type="text" x-model="displayValue"
                                            x-on:input="updateValue($event.target.value)"
                                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-right shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"
                                            placeholder="0">
                                    </div>
                                    @error('convert_base_price')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Harga
                                        Jual (Rp)</label>
                                    <div x-data="{
                                        raw: $wire.entangle('convert_selling_price'),
                                        displayValue: '',
                                        init() {
                                            this.$watch('raw', value => {
                                                if (value !== undefined && value !== null && value !== '' && value != 0) {
                                                    this.displayValue = new Intl.NumberFormat('id-ID').format(value);
                                                } else {
                                                    this.displayValue = '';
                                                }
                                            });
                                            if (this.raw !== undefined && this.raw !== null && this.raw !== '' && this.raw != 0) {
                                                this.displayValue = new Intl.NumberFormat('id-ID').format(this.raw);
                                            }
                                        },
                                        updateValue(val) {
                                            let rawVal = val.toString().replace(/\D/g, '');
                                            this.displayValue = rawVal ? new Intl.NumberFormat('id-ID').format(rawVal) : '';
                                            this.raw = rawVal;
                                        }
                                    }">
                                        <input type="text" x-model="displayValue"
                                            x-on:input="updateValue($event.target.value)"
                                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-right shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"
                                            placeholder="0">
                                    </div>
                                    @error('convert_selling_price')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Jumlah
                                        Stok Masuk</label>
                                    <input wire:model="convert_stock" type="number" min="0"
                                        class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-center shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                    @error('convert_stock')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="bg-amber-50 rounded-xl p-3 border border-amber-200 text-xs text-amber-800">
                                <span class="font-bold">Info:</span> Sistem akan otomatis:
                                <ul class="list-disc list-inside mt-1 space-y-0.5">
                                    <li>Buat produk baru di master data</li>
                                    <li>Link item PO ke produk baru</li>
                                    <li>Buat record "Barang Masuk" untuk stok</li>
                                    <li>Kurangi stok sesuai qty yang sudah terjual</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" wire:click="closeConvertModal"
                                class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                            <button type="button" wire:click="convertToProduct"
                                class="rounded-xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 active:scale-95 transition-transform">
                                Konversi & Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        /**
         * Fetch base64-encoded raw ESC/P data and open via RawBT URI intent.
         * Compatible with: RawBT Print Service (Android), NokoPrint, etc.
         */
        window.printDotMatrix = async function(transactionId, btnElement) {
            const label = btnElement.querySelector('.print-label');
            const originalText = label.textContent;

            // Loading state
            label.textContent = 'Memproses...';
            btnElement.disabled = true;
            btnElement.classList.add('opacity-50', 'cursor-wait');

            try {
                const response = await fetch(`/sales/${transactionId}/print-raw`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (!data.success || !data.base64) {
                    throw new Error('Data base64 tidak valid');
                }

                // ── Detect if Android (for URI intent) ──
                const isAndroid = /Android/i.test(navigator.userAgent);

                if (isAndroid) {
                    // RawBT URI scheme: rawbt:base64,<base64_encoded_data>
                    const rawbtUri = `rawbt:base64,${data.base64}`;

                    // Try opening via RawBT intent
                    window.location.href = rawbtUri;

                    label.textContent = 'Mengirim ke RawBT...';
                    setTimeout(() => {
                        label.textContent = originalText;
                        btnElement.disabled = false;
                        btnElement.classList.remove('opacity-50', 'cursor-wait');
                    }, 3000);
                } else {
                    // Desktop fallback: Download as .prn raw printer file
                    const rawBytes = atob(data.base64);
                    const bytes = new Uint8Array(rawBytes.length);
                    for (let i = 0; i < rawBytes.length; i++) {
                        bytes[i] = rawBytes.charCodeAt(i);
                    }
                    const blob = new Blob([bytes], {
                        type: 'application/octet-stream'
                    });
                    const url = URL.createObjectURL(blob);

                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `nota-${data.reference_code}.prn`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);

                    label.textContent = 'Terdownload!';
                    setTimeout(() => {
                        label.textContent = originalText;
                        btnElement.disabled = false;
                        btnElement.classList.remove('opacity-50', 'cursor-wait');
                    }, 2000);
                }
            } catch (error) {
                console.error('Print Error:', error);
                label.textContent = 'Gagal!';
                btnElement.classList.remove('opacity-50');
                btnElement.classList.add('!bg-red-100', '!text-red-700');

                setTimeout(() => {
                    label.textContent = originalText;
                    btnElement.disabled = false;
                    btnElement.classList.remove('cursor-wait', '!bg-red-100', '!text-red-700');
                }, 2000);
            }
        };
    </script>
@endscript

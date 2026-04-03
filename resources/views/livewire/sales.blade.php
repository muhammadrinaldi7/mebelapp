<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penjualan Terpadu</h1>
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
        <div class="mt-6 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl p-6 relative overflow-hidden">
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
                        <h4 class="text-sm font-semibold text-gray-800 border-b pb-2 mb-3 mt-4"># Pembayaran &
                            Pengiriman</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status
                            Pembayaran</label>
                        <select wire:model.live="payment_status"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="lunas">Lunas (Cash)</option>
                            <option value="dp">Hutang / DP (Cicilan)</option>
                            <option value="belum_dibayar">Belum Dibayar</option>
                        </select>
                    </div>
                    @if ($payment_status === 'dp')
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nominal
                                DP / Masuk (Rp)</label>
                            <input wire:model="down_payment" type="number" min="0" placeholder="Misal: 500000"
                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            @error('down_payment')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="hidden lg:block"></div>
                    @endif

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
                        <div class="flex-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pilih Produk
                            (Stok)</div>
                        <div class="w-32 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah</div>
                        <div class="w-48 text-xs font-semibold text-gray-500 uppercase tracking-wide">Harga Satuan
                        </div>
                        <div class="w-8"></div>
                    </div>

                    @foreach ($items as $index => $item)
                        <div
                            class="flex flex-col sm:flex-row gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl mb-3 relative items-end">
                            <div class="flex-1 w-full">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Produk</label>
                                <select wire:model.live="items.{{ $index }}.product_id"
                                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (stok:
                                            {{ $product->current_stock }}) - Rp
                                            {{ number_format($product->selling_price, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-32">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Qty</label>
                                <input wire:model="items.{{ $index }}.quantity" type="number" min="1"
                                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            <div class="w-full sm:w-48">
                                <label
                                    class="sm:hidden block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Harga
                                    Satuan (Rp)</label>
                                <input wire:model="items.{{ $index }}.price" type="number" min="0"
                                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 text-right shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            <button type="button" wire:click="removeItem({{ $index }})"
                                class="absolute top-2 right-2 sm:relative sm:top-0 sm:right-0 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg p-2 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @error('items.' . $index . '.product_id')
                                <span class="text-red-500 text-xs absolute -bottom-5 w-full">{{ $message }}</span>
                            @enderror
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
                                <input wire:model.live="shipping_cost" type="number"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-right text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                        </div>
                        <div class="flex justify-between w-full max-w-sm items-center">
                            <label class="font-medium flex-1 cursor-pointer text-red-500">Diskon (-):</label>
                            <div class="relative w-32">
                                <input wire:model.live="discount" type="number"
                                    class="block w-full rounded-lg border border-red-300 px-3 py-1.5 text-sm text-right text-red-700 bg-red-50 focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                            </div>
                        </div>
                        <div class="w-full max-w-sm border-t border-gray-300 my-1"></div>
                        <div class="flex justify-between w-full max-w-sm">
                            <span class="text-lg font-bold text-gray-900">GRAND TOTAL:</span>
                            <span class="text-xl font-bold text-blue-600">Rp
                                {{ number_format($this->grand_total, 0, ',', '.') }}</span>
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
                        <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Mebel Terjual</th>
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
                                <div class="font-bold text-gray-900">{{ $trx->reference_code }}</div>
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
                                </div>
                            </td>
                            <td class="py-4 px-3 text-sm">
                                @if ($trx->customer_name)
                                    <div class="font-medium text-gray-900">{{ $trx->customer_name }}</div>
                                    @if ($trx->customer_phone)
                                        <div class="text-xs text-gray-500">☎ {{ $trx->customer_phone }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Umum (Tidak terdata)</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                @foreach ($trx->details as $detail)
                                    <div class="text-xs leading-5">
                                        <span class="font-semibold text-gray-700">{{ $detail->quantity }}x</span>
                                        {{ $detail->product->name ?? '-' }}
                                    </div>
                                @endforeach
                            </td>
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
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 shadow-sm ring-1 ring-inset ring-indigo-700/10 hover:bg-indigo-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Update Status
                                    </button>
                                    <a href="{{ route('sales.invoice', $trx->id) }}" target="_blank"
                                        class="inline-flex w-full justify-center items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-900 hover:bg-gray-800 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v3.396c0 .621.504 1.125 1.125 1.125h8.25c.621 0 1.125-.504 1.125-1.125v-3.396zm-9-5.25C8.25 7.618 9.382 6.5 10.75 6.5h2.5c1.368 0 2.5 1.118 2.5 2.5v.75m-6-1.5z" />
                                        </svg>
                                        Cetak Nota
                                    </a>
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

    {{-- Modal Update Status --}}
    @if ($showEditForm)
        <div class="relative z-50">
            <div class="fixed inset-0 bg-white/80 blur-md bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900 border-b pb-2">Update
                                        Progres Transaksi</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status
                                                Pembayaran</label>
                                            <select wire:model.live="edit_payment_status"
                                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                                <option value="lunas">Lunas (Cash)</option>
                                                <option value="dp">Hutang / DP (Cicilan)</option>
                                                <option value="belum_dibayar">Belum Dibayar</option>
                                            </select>
                                        </div>
                                        @if ($edit_payment_status === 'dp')
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nominal
                                                    DP / Masuk (Rp)</label>
                                                <input wire:model="edit_down_payment" type="number" min="0"
                                                    class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                                @error('edit_down_payment')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Armada
                                                / Pengiriman</label>
                                            <select wire:model.live="edit_shipping_status"
                                                class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                                <option value="bawa_sendiri">Pembeli Bawa Sendiri</option>
                                                <option value="menunggu_dikirim">Menunggu Jadwal Dikirim</option>
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
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" wire:click="updateStatus"
                                class="inline-flex w-full justify-center rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">Simpan
                                Update</button>
                            <button type="button" wire:click="$set('showEditForm', false)"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

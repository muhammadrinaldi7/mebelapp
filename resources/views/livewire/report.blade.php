<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan & Analitik</h1>
            <p class="mt-1 text-sm text-gray-500">Pusat data pelaporan gudang dan penjualan mebel.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            @can('export-laporan')
                <a href="{{ route('report.export', ['type' => 'excel', 'tab' => $activeTab, 'from' => $dateFrom, 'to' => $dateTo, 'search' => $search, 'rt' => $reportType, 'mt' => $movementType, 'cid' => $categoryId, 'bid' => $brandId, 'pid' => $compareProductId ?? '']) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 active:scale-95 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('report.export', ['type' => 'pdf', 'tab' => $activeTab, 'from' => $dateFrom, 'to' => $dateTo, 'search' => $search, 'rt' => $reportType, 'mt' => $movementType, 'cid' => $categoryId, 'bid' => $brandId, 'pid' => $compareProductId ?? '']) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 active:scale-95 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Export PDF
                </a>
            @endcan
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto no-scrollbar" aria-label="Tabs">
            @can('lihat-laporan-transaksi')
                <button wire:click="setTab('transactions')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'transactions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'transactions' ? 'text-indigo-500' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5V3L12 16.5 3 3v13.5z" />
                    </svg>
                    Riwayat Transaksi
                </button>
            @endcan
            @can('lihat-laporan-stok')
                <button wire:click="setTab('stock')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'stock' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'stock' ? 'text-indigo-500' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    Sisa Gudang & Modal
                </button>
            @endcan
            @can('lihat-laporan-mutasi')
                <button wire:click="setTab('movement')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'movement' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'movement' ? 'text-indigo-500' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    Mutasi Barang
                </button>
            @endcan
            @can('lihat-laporan-laba')
                <button wire:click="setTab('profit')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'profit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'profit' ? 'text-indigo-500' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Laporan Keuntungan
                </button>
            @endcan
            @if (Auth::user()->hasRole('admin'))
                <button wire:click="setTab('buy_price')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'buy_price' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'buy_price' ? 'text-indigo-500' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    Analisa Harga Beli
                </button>
            @endif
        </nav>
    </div>

    {{-- Filters --}}
    <div class="mt-5 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            @if ($activeTab !== 'stock')
                @can('lihat-laporan-filter')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Dari
                            Tanggal</label>
                        <input wire:model.live="dateFrom" type="date"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Sampai
                            Tanggal</label>
                        <input wire:model.live="dateTo" type="date"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                @endcan
            @endif

            @if ($activeTab === 'transactions')
                @can('lihat-laporan-transaksi')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tipe
                            Transaksi</label>
                        <select wire:model.live="reportType"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                            <option value="all">Semua Tipe</option>
                            <option value="in">Barang Masuk</option>
                            <option value="out">Barang Keluar</option>
                            <option value="sale">Penjualan</option>
                        </select>
                    </div>
                @endcan
            @endif

            @if ($activeTab === 'movement')
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tipe
                        Mutasi</label>
                    <select wire:model.live="movementType"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="all">Semua Mutasi</option>
                        <option value="in">Barang Masuk</option>
                        <option value="out">Barang Keluar</option>
                        <option value="sale">Penjualan</option>
                    </select>
                </div>
            @endif

            @if ($activeTab === 'stock')
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                    <select wire:model.live="categoryId"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Merek</label>
                    <select wire:model.live="brandId"
                        class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Semua Merek</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($activeTab === 'buy_price')
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Pilih Produk
                        (Analisa)</label>
                    <div class="relative">
                        <select wire:model.live="compareProductId"
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 appearance-none">
                            <option value="">-- Pilih Produk untuk Dianalisa --</option>
                            @foreach ($productsFilter as $prodFilter)
                                <option value="{{ $prodFilter->id }}">{{ $prodFilter->sku }} -
                                    {{ $prodFilter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                @can('lihat-laporan-filter')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kata
                            Kunci</label>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, SKU..."
                            class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                @endcan
            @endif
        </div>
    </div>

    {{-- Dynamic Summary Cards --}}
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @if ($activeTab === 'transactions')
            @can('lihat-laporan-transaksi')
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                    <p class="text-xs font-medium text-gray-500">Jumlah Transaksi</p>
                    <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalTransactions'] }}</p>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                    <p class="text-xs font-medium text-emerald-600">Barang Masuk</p>
                    <p class="mt-1 text-xl font-bold text-emerald-700">{{ $summary['totalIn'] }}</p>
                </div>
                <div class="rounded-2xl bg-orange-50 p-4 shadow-sm ring-1 ring-orange-200 text-center">
                    <p class="text-xs font-medium text-orange-600">Barang Keluar</p>
                    <p class="mt-1 text-xl font-bold text-orange-700">{{ $summary['totalOut'] }}</p>
                </div>
                <div class="rounded-2xl bg-indigo-50 p-4 shadow-sm ring-1 ring-indigo-200 text-center">
                    <p class="text-xs font-medium text-indigo-600">Total Uang Penjualan</p>
                    <p class="mt-1 text-lg font-bold text-indigo-700">Rp
                        {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            @endcan
        @elseif($activeTab === 'stock')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Produk</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalItems'] }}</p>
            </div>
            <div class="rounded-2xl bg-blue-50 p-4 shadow-sm ring-1 ring-blue-200 text-center">
                <p class="text-xs font-medium text-blue-600">Jumlah Fisik Barang</p>
                <p class="mt-1 text-xl font-bold text-blue-700">{{ $summary['totalStock'] }} Unit</p>
            </div>
            <div class="rounded-2xl bg-purple-50 p-4 shadow-sm ring-1 ring-purple-200 text-center">
                <p class="text-xs font-medium text-purple-600">Total Modal</p>
                <p class="mt-1 text-lg font-bold text-purple-700">Rp
                    {{ number_format($summary['totalAssetValue'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Laba Kotor Jika Semua Unit Terjual</p>
                <p class="mt-1 text-lg font-bold text-emerald-700">Rp
                    {{ number_format($summary['totalPotentialRevenue'] - $summary['totalAssetValue'], 0, ',', '.') }}
                </p>
            </div>
        @elseif($activeTab === 'movement')
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Total Masuk</p>
                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $summary['totalInQty'] }} Unit</p>
            </div>
            <div class="rounded-2xl bg-orange-50 p-4 shadow-sm ring-1 ring-orange-200 text-center">
                <p class="text-xs font-medium text-orange-600">Total Keluar</p>
                <p class="mt-1 text-xl font-bold text-orange-700">{{ $summary['totalOutQty'] }} Unit</p>
            </div>
            <div class="rounded-2xl bg-blue-50 p-4 shadow-sm ring-1 ring-blue-200 text-center">
                <p class="text-xs font-medium text-blue-600">Total Terjual</p>
                <p class="mt-1 text-xl font-bold text-blue-700">{{ $summary['totalSaleQty'] }} Unit</p>
            </div>
            <div class="rounded-2xl bg-purple-50 p-4 shadow-sm ring-1 ring-purple-200 text-center">
                <p class="text-xs font-medium text-purple-600">Total Nilai Transaksi</p>
                <p class="mt-1 text-lg font-bold text-purple-700">Rp
                    {{ number_format($summary['totalValue'], 0, ',', '.') }}</p>
            </div>
        @elseif($activeTab === 'profit')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Produk Terjual</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalSalesItems'] }}</p>
            </div>
            <div class="rounded-2xl bg-blue-50 p-4 shadow-sm ring-1 ring-blue-200 text-center">
                <p class="text-xs font-medium text-blue-600">Total Omset</p>
                <p class="mt-1 text-lg font-bold text-blue-700">Rp
                    {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-red-50 p-4 shadow-sm ring-1 ring-red-200 text-center">
                <p class="text-xs font-medium text-red-600">Total HPP / Modal</p>
                <p class="mt-1 text-lg font-bold text-red-700">Rp
                    {{ number_format($summary['totalCost'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-orange-50 p-4 shadow-sm ring-1 ring-orange-200 text-center">
                <p class="text-xs font-medium text-orange-600">Total Potongan Diskon</p>
                <p class="mt-1 text-lg font-bold text-orange-700">Rp
                    {{ number_format($summary['totalDiscount'], 0, ',', '.') }}</p>
            </div>
            <div
                class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center col-span-2 sm:col-span-4">
                <p class="text-sm font-medium text-emerald-600">Laba Kotor (Sudah Termasuk Potongan)</p>
                <p class="mt-1 text-2xl font-bold text-emerald-700">Rp
                    {{ number_format($summary['netGrossProfit'], 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    {{-- Render Content (Included properly instead of writing it all here, but for brevity we write it) --}}
    <div class="mt-4 overflow-hidden shadow-sm ring-1 ring-gray-900/5 rounded-2xl bg-white">
        <div class="overflow-x-auto p-0">
            @if ($activeTab === 'transactions')
                @can('lihat-laporan-transaksi')
                    @include('reports.tables.transactions')
                    <div class="p-4">{{ $transactions->links() }}</div>
                @endcan
            @elseif($activeTab === 'stock')
                @include('reports.tables.stock')
                <div class="p-4">{{ $products->links() }}</div>
            @elseif($activeTab === 'movement')
                @include('reports.tables.movement')
                <div class="p-4">{{ $mutations->links() }}</div>
            @elseif($activeTab === 'profit')
                @include('reports.tables.profit')
                <div class="p-4">{{ $profitDetails->links() }}</div>
            @elseif($activeTab === 'buy_price')
                <div class="p-6">
                    @if ($compareProductId && count($priceHistory) > 0)
                        {{-- Chart powered by Alpine.js + Chart.js --}}
                        <div wire:key="chart-{{ $compareProductId }}-{{ $dateFrom }}-{{ $dateTo }}"
                            class="mb-8 p-5 rounded-2xl shadow-sm border border-gray-100 bg-white"
                            x-data="{
                                chart: null,
                                labels: @js($chartLabels),
                                prices: @js($chartPrices),
                                init() {
                                    this.$nextTick(() => this.renderChart());
                                },
                                renderChart() {
                                    const ctx = this.$refs.canvas;
                                    if (!ctx || typeof Chart === 'undefined') return;
                            
                                    if (this.chart) {
                                        this.chart.destroy();
                                    }
                            
                                    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
                                    gradient.addColorStop(0, 'rgba(239, 68, 68, 0.25)');
                                    gradient.addColorStop(1, 'rgba(239, 68, 68, 0.01)');
                            
                                    this.chart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: this.labels,
                                            datasets: [{
                                                label: 'Harga Beli (Rp)',
                                                data: this.prices,
                                                borderColor: '#ef4444',
                                                backgroundColor: gradient,
                                                borderWidth: 2.5,
                                                fill: true,
                                                tension: 0.3,
                                                pointBackgroundColor: '#fff',
                                                pointBorderColor: '#ef4444',
                                                pointBorderWidth: 2,
                                                pointHoverRadius: 7,
                                                pointHoverBorderWidth: 3,
                                                pointRadius: 5,
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            interaction: {
                                                mode: 'index',
                                                intersect: false,
                                            },
                                            plugins: {
                                                legend: { display: false },
                                                tooltip: {
                                                    backgroundColor: '#1e293b',
                                                    titleFont: { size: 13, weight: '600' },
                                                    bodyFont: { size: 12 },
                                                    padding: 12,
                                                    cornerRadius: 10,
                                                    displayColors: false,
                                                    callbacks: {
                                                        label: function(ctx) {
                                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    grid: { display: false },
                                                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                                                },
                                                y: {
                                                    beginAtZero: false,
                                                    grid: { color: 'rgba(0,0,0,0.04)' },
                                                    ticks: {
                                                        font: { size: 11 },
                                                        color: '#94a3b8',
                                                        callback: function(value) {
                                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            }">
                            <h3 class="text-sm font-bold text-gray-900 mb-3">
                                <svg class="inline-block w-4 h-4 mr-1 text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                                Grafik Pergerakan Harga Beli (Rp)
                            </h3>
                            <div class="relative w-full" style="height: 300px;" wire:ignore>
                                <canvas x-ref="canvas"></canvas>
                            </div>
                        </div>

                        {{-- Summary cards --}}
                        @php
                            $minPrice = $priceHistory->min('price_at_transaction');
                            $maxPrice = $priceHistory->max('price_at_transaction');
                            $avgPrice = $priceHistory->avg('price_at_transaction');
                            $lastPrice = $priceHistory->last()->price_at_transaction ?? 0;
                            $firstPrice = $priceHistory->first()->price_at_transaction ?? 0;
                            $priceDiff = $lastPrice - $firstPrice;
                            $pricePct = $firstPrice > 0 ? round(($priceDiff / $firstPrice) * 100, 1) : 0;
                        @endphp
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <div class="rounded-xl bg-red-50 p-3 ring-1 ring-red-100 text-center">
                                <p class="text-[10px] font-semibold text-red-500 uppercase tracking-wider">Harga
                                    Tertinggi</p>
                                <p class="mt-1 text-base font-bold text-red-700">Rp
                                    {{ number_format($maxPrice, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-xl bg-emerald-50 p-3 ring-1 ring-emerald-100 text-center">
                                <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-wider">Harga
                                    Terendah</p>
                                <p class="mt-1 text-base font-bold text-emerald-700">Rp
                                    {{ number_format($minPrice, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-xl bg-blue-50 p-3 ring-1 ring-blue-100 text-center">
                                <p class="text-[10px] font-semibold text-blue-500 uppercase tracking-wider">Rata-Rata
                                </p>
                                <p class="mt-1 text-base font-bold text-blue-700">Rp
                                    {{ number_format($avgPrice, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3 ring-1 ring-gray-200 text-center">
                                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Perubahan
                                </p>
                                <p
                                    class="mt-1 text-base font-bold {{ $priceDiff > 0 ? 'text-red-600' : ($priceDiff < 0 ? 'text-emerald-600' : 'text-gray-600') }}">
                                    {{ $priceDiff > 0 ? '▲' : ($priceDiff < 0 ? '▼' : '—') }} {{ abs($pricePct) }}%
                                </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tanggal Masuk</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Referensi</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Kuantitas</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Harga Beli / Unit</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($priceHistory as $hist)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($hist->transaction->transaction_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                                {{ $hist->transaction->reference_code }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-500 font-bold">
                                                {{ $hist->quantity }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-red-600 font-bold">Rp
                                                {{ number_format($hist->price_at_transaction, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 font-bold">Rp
                                                {{ number_format($hist->price_at_transaction * $hist->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($compareProductId)
                        <div class="py-10 text-center text-gray-500 text-sm">
                            Belum ada riwayat transaksi barang masuk di rentang tanggal ini untuk produk tersebut.
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Pilih Produk</h3>
                            <p class="mt-1 text-sm text-gray-500">Silakan pilih produk dari dropdown di atas untuk
                                melihat analisa tren harga belinya.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>

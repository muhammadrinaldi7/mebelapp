<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan & Analitik</h1>
            <p class="mt-1 text-sm text-gray-500">Pusat data pelaporan gudang dan penjualan mebel.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <a href="{{ route('report.export', ['type' => 'excel', 'tab' => $activeTab, 'from' => $dateFrom, 'to' => $dateTo, 'search' => $search, 'rt' => $reportType, 'cid' => $categoryId, 'bid' => $brandId]) }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 active:scale-95 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3"/></svg>
                Export Excel
            </a>
            <a href="{{ route('report.export', ['type' => 'pdf', 'tab' => $activeTab, 'from' => $dateFrom, 'to' => $dateTo, 'search' => $search, 'rt' => $reportType, 'cid' => $categoryId, 'bid' => $brandId]) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 active:scale-95 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            <button wire:click="setTab('transactions')" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'transactions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'transactions' ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5V3L12 16.5 3 3v13.5z"/></svg>
                Riwayat Transaksi
            </button>
            <button wire:click="setTab('stock')" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'stock' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'stock' ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                Sisa Gudang & Modal
            </button>
            <button wire:click="setTab('movement')" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'movement' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'movement' ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                Barang Laris & Tidak Laris
            </button>
            <button wire:click="setTab('profit')" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'profit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                <svg class="-ml-0.5 mr-2 h-5 w-5 inline-block {{ $activeTab === 'profit' ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Laporan Keuntungan
            </button>
        </nav>
    </div>

    {{-- Filters --}}
    <div class="mt-5 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            
            @if($activeTab !== 'stock')
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Dari Tanggal</label>
                <input wire:model.live="dateFrom" type="date" class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Sampai Tanggal</label>
                <input wire:model.live="dateTo" type="date" class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            @endif

            @if($activeTab === 'transactions')
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tipe Transaksi</label>
                <select wire:model.live="reportType" class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="all">Semua Tipe</option>
                    <option value="in">Barang Masuk</option>
                    <option value="out">Barang Keluar</option>
                    <option value="sale">Penjualan</option>
                </select>
            </div>
            @endif

            @if($activeTab === 'stock')
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                <select wire:model.live="categoryId" class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Merek</label>
                <select wire:model.live="brandId" class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Merek</option>
                    @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Kata Kunci</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, SKU..." class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
    </div>

    {{-- Dynamic Summary Cards --}}
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @if($activeTab === 'transactions')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Jumlah Transaksi</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalTransactions'] }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Barang Masuk / Retur</p>
                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $summary['totalIn'] }}</p>
            </div>
            <div class="rounded-2xl bg-orange-50 p-4 shadow-sm ring-1 ring-orange-200 text-center">
                <p class="text-xs font-medium text-orange-600">Barang Keluar</p>
                <p class="mt-1 text-xl font-bold text-orange-700">{{ $summary['totalOut'] }}</p>
            </div>
            <div class="rounded-2xl bg-indigo-50 p-4 shadow-sm ring-1 ring-indigo-200 text-center">
                <p class="text-xs font-medium text-indigo-600">Total Uang Penjualan</p>
                <p class="mt-1 text-lg font-bold text-indigo-700">Rp {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</p>
            </div>
        @elseif($activeTab === 'stock')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Macam Bentuk Barang</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalItems'] }}</p>
            </div>
            <div class="rounded-2xl bg-blue-50 p-4 shadow-sm ring-1 ring-blue-200 text-center">
                <p class="text-xs font-medium text-blue-600">Jumlah Fisik Barang</p>
                <p class="mt-1 text-xl font-bold text-blue-700">{{ $summary['totalStock'] }} Unit</p>
            </div>
            <div class="rounded-2xl bg-purple-50 p-4 shadow-sm ring-1 ring-purple-200 text-center">
                <p class="text-xs font-medium text-purple-600">Total Uang Modal</p>
                <p class="mt-1 text-lg font-bold text-purple-700">Rp {{ number_format($summary['totalAssetValue'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Terjual Semua Jadi Untung</p>
                <p class="mt-1 text-lg font-bold text-emerald-700">Rp {{ number_format($summary['totalPotentialRevenue'] - $summary['totalAssetValue'], 0, ',', '.') }}</p>
            </div>
        @elseif($activeTab === 'movement')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Barang Masuk Gudang</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalInQty'] }}</p>
            </div>
             <div class="rounded-2xl bg-orange-50 p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-orange-600">Barang Keluar Titipan</p>
                <p class="mt-1 text-xl font-bold text-orange-700">{{ $summary['totalOutQty'] }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Total Laku Terjual</p>
                <p class="mt-1 text-xl font-bold text-emerald-700">{{ $summary['totalSaleQty'] }}</p>
            </div>
            <div class="rounded-2xl bg-red-50 p-4 shadow-sm ring-1 ring-red-200 text-center">
                <p class="text-xs font-medium text-red-600">Barang Tidak Laku-laku</p>
                <p class="mt-1 text-xl font-bold text-red-700">{{ $summary['deadStockCount'] }} Produk</p>
            </div>
        @elseif($activeTab === 'profit')
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 text-center">
                <p class="text-xs font-medium text-gray-500">Produk Terjual</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $summary['totalSalesItems'] }}</p>
            </div>
            <div class="rounded-2xl bg-blue-50 p-4 shadow-sm ring-1 ring-blue-200 text-center">
                <p class="text-xs font-medium text-blue-600">Total Uang Masuk</p>
                <p class="mt-1 text-lg font-bold text-blue-700">Rp {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-red-50 p-4 shadow-sm ring-1 ring-red-200 text-center">
                <p class="text-xs font-medium text-red-600">Tanggungan Modal</p>
                <p class="mt-1 text-lg font-bold text-red-700">Rp {{ number_format($summary['totalCost'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200 text-center">
                <p class="text-xs font-medium text-emerald-600">Keuntungan (Belum Potongan)</p>
                <p class="mt-1 text-xl font-bold text-emerald-700">Rp {{ number_format($summary['grossProfit'], 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    {{-- Render Content (Included properly instead of writing it all here, but for brevity we write it) --}}
    <div class="mt-4 overflow-hidden shadow-sm ring-1 ring-gray-900/5 rounded-2xl bg-white">
        <div class="overflow-x-auto p-0">
            @if($activeTab === 'transactions')
                @include('reports.tables.transactions')
                <div class="p-4">{{ $transactions->links() }}</div>
            @elseif($activeTab === 'stock')
                @include('reports.tables.stock')
                <div class="p-4">{{ $products->links() }}</div>
            @elseif($activeTab === 'movement')
                @include('reports.tables.movement')
                <div class="p-4">{{ $productsMove->links() }}</div>
            @elseif($activeTab === 'profit')
                @include('reports.tables.profit')
                <div class="p-4">{{ $profitDetails->links() }}</div>
            @endif
        </div>
    </div>
</div>

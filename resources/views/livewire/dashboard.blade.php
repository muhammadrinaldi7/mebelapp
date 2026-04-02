<div>
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Mebel</h1>
    <p class="mt-1 text-sm text-gray-500">Ringkasan statistik, valuasi aset, dan performa bisnis bulan ini.</p>

    {{-- Stats Cards - Grade A Financial Focus --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card 1: Valuasi Aset --}}
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5 bg-linear-to-br from-indigo-50 to-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">Total Valuasi Aset</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">Rp {{ number_format($totalAssetValue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Laba Kotor --}}
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5 bg-linear-to-br from-emerald-50 to-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Laba Kotor Bulan Ini</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">Rp {{ number_format($grossProfitThisMonth ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Card 3: Omset --}}
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Omset Sebulan</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">Rp {{ number_format($totalRevenueThisMonth ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Card 4: Total Produk --}}
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Macam Varian Mebel</p>
                    <p class="text-xl font-bold text-gray-900 mt-0.5">{{ number_format($totalProducts ?? 0, 0, ',', '.') }} SKU</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Chart 1: Transaction Trend --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-base font-bold text-gray-900">Alur Transaksi (30 Hari)</h3>
            <p class="text-xs text-gray-500 mt-1">Pergerakan keluar masuk barang digudang</p>
            <div class="mt-6 relative" style="height: 280px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Revenue Trend --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-base font-bold text-gray-900">Performa Omset (30 Hari)</h3>
            <p class="text-xs text-gray-500 mt-1">Pendapatan kotor harian sebulan terakhir</p>
            <div class="mt-6 relative" style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Chart 3: Top Products --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-base font-bold text-gray-900">Top 5 Bintang Omset</h3>
            <p class="text-xs text-gray-500 mt-1">Berdasarkan Total Nilai Penjualan (Rupiah)</p>
            <div class="mt-6 relative" style="height: 260px;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        {{-- Chart 4: Stock Asset by Category --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-base font-bold text-gray-900">Distribusi Keuangan Aset Gudang</h3>
            <p class="text-xs text-gray-500 mt-1">Persentase Valuasi Harga Modal per Kategori</p>
            <div class="mt-6 flex items-center justify-center relative" style="height: 260px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Table Rows --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1 border rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-red-50/50">
                <h2 class="text-sm font-bold text-red-600 flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Peringatan Stok Menipis
                </h2>
            </div>
            <div class="overflow-y-auto max-h-[300px]">
                @if ($lowStockProducts->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach ($lowStockProducts as $product)
                        <li class="p-4 hover:bg-gray-50 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20 shadow-sm">Sisa: {{ $product->current_stock }}</span>
                        </li>
                    @endforeach
                </ul>
                @else
                <div class="p-8 text-center text-sm text-gray-400">Gudang anda aman. Tidak ada stok kritis.</div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 border rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900">Aktivitas Terkini (10 Transaksi Terakhir)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 pl-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Waktu</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Sandi Referensi</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Mutasi</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="whitespace-nowrap py-3.5 pl-5 text-sm text-gray-500">{{ $trx->transaction_date->translatedFormat('d M H:i') }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm font-bold text-gray-900">{{ $trx->reference_code }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm">
                                    @if ($trx->type === 'in') <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-bold">STOK MASUK</span>
                                    @elseif($trx->type === 'out') <span class="bg-orange-100 text-orange-700 px-2.5 py-1 rounded-lg text-xs font-bold">STOK KELUAR</span>
                                    @else <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold">PENJUALAN</span> @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm text-right font-bold text-gray-900">
                                    @if($trx->type === 'sale')
                                        Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">Belum ada perpindahan barang sama sekali.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);

            // --- GLOBAL CONFIGURATION (Apple/iOS Style) ---
            Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#9ca3af';
            Chart.defaults.scale.grid.color = '#f3f4f6';
            
            // Rich Tooltip iOS Glass effect
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(17, 24, 39, 0.85)';
            Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
            Chart.defaults.plugins.tooltip.bodyColor = '#f3f4f6';
            Chart.defaults.plugins.tooltip.cornerRadius = 12;
            Chart.defaults.plugins.tooltip.padding = 12;
            Chart.defaults.plugins.tooltip.boxPadding = 6;
            Chart.defaults.plugins.tooltip.usePointStyle = true;
            Chart.defaults.plugins.tooltip.borderColor = 'rgba(255,255,255,0.1)';
            Chart.defaults.plugins.tooltip.borderWidth = 1;


            // Shared Axis configs
            const hideGridX = { grid: { display: false }, border: { display: false }, ticks: { maxRotation: 0, autoSkipPadding: 20 } };
            const subtleGridY = { grid: { color: '#f8fafc' }, border: { display: false }, beginAtZero: true };

            // Function to create smooth line gradients (Canvas)
            function getGradient(ctx, colorStart, colorEnd) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            }

            // -- CHART 1: ALUR TRANSAKSI (Garis Curvy) --
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: chartData.trendLabels,
                    datasets: [
                        {
                            label: 'Terjual (Sales)',
                            data: chartData.trendSale,
                            borderColor: '#3b82f6', // blue
                            backgroundColor: getGradient(ctxTrend, 'rgba(59,130,246,0.3)', 'rgba(59,130,246,0)'),
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4, // Super smooth
                            pointRadius: 0, // Hidden points until hover
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Masuk Gudang (In)',
                            data: chartData.trendIn,
                            borderColor: '#10b981', // emerald
                            backgroundColor: getGradient(ctxTrend, 'rgba(16,185,129,0.2)', 'rgba(16,185,129,0)'),
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#10b981'
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 6 } } },
                    scales: { x: hideGridX, y: subtleGridY }
                }
            });


            // -- CHART 2: PERFORMA OMSET (Gradient Bar) --
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRev, {
                type: 'bar',
                data: {
                    labels: chartData.trendLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: chartData.revenueTrend,
                        backgroundColor: getGradient(ctxRev, '#8b5cf6', '#a78bfa'), // Violet gradients
                        hoverBackgroundColor: '#7c3aed',
                        borderRadius: 6, // iOS style rounded bars
                        borderSkipped: false,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw); }
                            }
                        }
                    },
                    scales: {
                        x: hideGridX,
                        y: {
                            ...subtleGridY,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });


            // -- CHART 3: TOP 5 BINTANG OMSET (Horizontal Bar) --
            const ctxTop = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: chartData.topProductLabels.length ? chartData.topProductLabels : ['Kosong'],
                    datasets: [{
                        label: 'Total Nilai (Rp)',
                        data: chartData.topProductData.length ? chartData.topProductData : [0],
                        // Array of aesthetic colors
                        backgroundColor: ['#4f46e5', '#ec4899', '#f59e0b', '#06b6d4', '#8b5cf6'], 
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 24
                    }]
                },
                options: {
                    indexAxis: 'y', // Makes it horizontal
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw); },
                                // Show qty on tooltip too
                                afterLabel: function(ctx) {
                                    const qtyArr = chartData.topProductQty || [];
                                    const qty = qtyArr[ctx.dataIndex] || 0;
                                    return `Total Unit Terjual: ${qty} Unit`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: false }, // Hide bottom scale completely for pure clean look
                        y: { border: { display: false }, grid: { display: false }, ticks: { font: { weight: '600' }, color: '#374151' } }
                    }
                }
            });


            // -- CHART 4: ASSET CATEGORY (Doughnut) --
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: chartData.categoryLabels.length ? chartData.categoryLabels : ['Kosong'],
                    datasets: [{
                        data: chartData.categoryAssetData.length ? chartData.categoryAssetData : [1],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '70%', // thinner rings
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8, font: { size: 11} } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw); }
                            }
                        }
                    }
                }
            });

        });
    </script>
</div>

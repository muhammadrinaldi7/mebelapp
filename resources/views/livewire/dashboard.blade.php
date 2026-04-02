<div>
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">Ringkasan stok dan transaksi mebel Anda.</p>

    {{-- Stats Cards --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">

                <div>
                    <p class="text-xs font-medium text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">

                <div>
                    <p class="text-xs font-medium text-gray-500">Total Merek</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBrands }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">

                <div>
                    <p class="text-xs font-medium text-gray-500">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white px-5 py-5 shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center gap-3">

                <div>
                    <p class="text-xs font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Summary Cards --}}
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="overflow-hidden rounded-2xl bg-emerald-500 px-5 py-4 shadow-sm">
            <p class="text-sm font-medium text-emerald-100">Barang Masuk</p>
            <p class="mt-1 text-3xl font-bold text-white">{{ $totalStockIn }}</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-orange-500 px-5 py-4 shadow-sm">
            <p class="text-sm font-medium text-orange-100">Barang Keluar</p>
            <p class="mt-1 text-3xl font-bold text-white">{{ $totalStockOut }}</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-blue-500 px-5 py-4 shadow-sm">
            <p class="text-sm font-medium text-blue-100">Penjualan</p>
            <p class="mt-1 text-3xl font-bold text-white">{{ $totalSales }}</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Chart 1: Transaction Trend --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-sm font-semibold text-gray-900">Tren Transaksi (7 Hari Terakhir)</h3>
            <p class="text-xs text-gray-500 mt-0.5">Jumlah transaksi per hari</p>
            <div class="mt-4" style="height: 260px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Revenue Trend --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-sm font-semibold text-gray-900">Pendapatan Penjualan (7 Hari)</h3>
            <p class="text-xs text-gray-500 mt-0.5">Total pendapatan per hari</p>
            <div class="mt-4" style="height: 260px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Chart 3: Top Products --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-sm font-semibold text-gray-900">Top 5 Produk Terlaris</h3>
            <p class="text-xs text-gray-500 mt-0.5">Berdasarkan jumlah unit terjual</p>
            <div class="mt-4" style="height: 260px;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        {{-- Chart 4: Stock by Category --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
            <h3 class="text-sm font-semibold text-gray-900">Stok per Kategori</h3>
            <p class="text-xs text-gray-500 mt-0.5">Distribusi stok berdasarkan kategori</p>
            <div class="mt-4 flex items-center justify-center" style="height: 260px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    @if ($lowStockProducts->count() > 0)
        <div class="mt-6">
            <h2 class="text-sm font-semibold text-red-600 flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                Stok Menipis (≤ 5 unit)
            </h2>
            <div class="mt-3 overflow-hidden shadow-sm ring-1 ring-gray-900/5 rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-red-50">
                        <tr>
                            <th
                                class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                SKU</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Nama Produk</th>
                            <th
                                class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($lowStockProducts as $product)
                            <tr>
                                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    {{ $product->sku }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $product->name }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-right">
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">{{ $product->current_stock }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Recent Transactions --}}
    <div class="mt-6">
        <h2 class="text-sm font-semibold text-gray-900">Transaksi Terbaru</h2>
        <div class="mt-3 overflow-hidden shadow-sm ring-1 ring-gray-900/5 rounded-2xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Kode</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tipe
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Tanggal</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User
                        </th>
                        <th class="px-3 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($recentTransactions as $trx)
                        <tr>
                            <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900">
                                {{ $trx->reference_code }}</td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm">
                                @if ($trx->type === 'in')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Masuk</span>
                                @elseif($trx->type === 'out')
                                    <span
                                        class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-semibold text-orange-700">Keluar</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Penjualan</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">
                                {{ $trx->transaction_date->format('d M Y') }}</td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500">{{ $trx->user->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 font-medium">Rp
                                {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-gray-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);

            // Shared font & style config
            Chart.defaults.font.family = "'Inter', -apple-system, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.pointStyleWidth = 8;

            // -- Chart 1: Transaction Trend (Line) --
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: chartData.trendLabels,
                    datasets: [{
                            label: 'Masuk',
                            data: chartData.trendIn,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                        },
                        {
                            label: 'Keluar',
                            data: chartData.trendOut,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249,115,22,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#f97316',
                        },
                        {
                            label: 'Penjualan',
                            data: chartData.trendSale,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#9ca3af'
                            },
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9ca3af'
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 8,
                                padding: 16,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // -- Chart 2: Revenue Trend (Bar) --
            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: chartData.trendLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: chartData.revenueTrend,
                        backgroundColor: 'rgba(99,102,241,0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 28,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9ca3af',
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) +
                                        'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9ca3af'
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                                }
                            }
                        }
                    }
                }
            });

            // -- Chart 3: Top Products (Horizontal Bar) --
            new Chart(document.getElementById('topProductsChart'), {
                type: 'bar',
                data: {
                    labels: chartData.topProductLabels.length ? chartData.topProductLabels : [
                        'Belum ada data'
                    ],
                    datasets: [{
                        label: 'Unit Terjual',
                        data: chartData.topProductData.length ? chartData.topProductData : [0],
                        backgroundColor: ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe'],
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 24,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#9ca3af'
                            },
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // -- Chart 4: Stock by Category (Doughnut) --
            const categoryColors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#f59e0b',
                '#ef4444'
            ];
            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: chartData.categoryLabels.length ? chartData.categoryLabels : ['Belum ada data'],
                    datasets: [{
                        data: chartData.categoryStockData.length ? chartData.categoryStockData : [
                            1],
                        backgroundColor: categoryColors.slice(0, chartData.categoryLabels.length ||
                            1),
                        borderWidth: 0,
                        spacing: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 12,
                                font: {
                                    size: 11
                                },
                                boxWidth: 10
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>

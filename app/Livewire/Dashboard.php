<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // --- Chart 1: Transaction trend last 7 days ---
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $days->push(Carbon::today()->subDays($i));
        }

        $trendLabels = $days->map(fn($d) => $d->translatedFormat('d M'))->toArray();

        $trendIn = $days->map(fn($d) => Transaction::where('type', 'in')
            ->whereDate('transaction_date', $d)->count())->toArray();
        $trendOut = $days->map(fn($d) => Transaction::where('type', 'out')
            ->whereDate('transaction_date', $d)->count())->toArray();
        $trendSale = $days->map(fn($d) => Transaction::where('type', 'sale')
            ->whereDate('transaction_date', $d)->count())->toArray();

        // --- Chart 2: Revenue trend last 7 days ---
        $revenueTrend = $days->map(fn($d) => (int) Transaction::where('type', 'sale')
            ->whereDate('transaction_date', $d)->sum('total_amount'))->toArray();

        // --- Chart 3: Top 5 selling products ---
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('transaction', fn($q) => $q->where('type', 'sale'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product:id,name')
            ->get();

        $topProductLabels = $topProducts->map(fn($p) => $p->product->name ?? 'N/A')->toArray();
        $topProductData = $topProducts->map(fn($p) => (int) $p->total_qty)->toArray();

        // --- Chart 4: Stock by category ---
        $stockByCategory = Category::withSum('products', 'current_stock')->get();
        $categoryLabels = $stockByCategory->map(fn($c) => $c->name)->toArray();
        $categoryStockData = $stockByCategory->map(fn($c) => (int) ($c->products_sum_current_stock ?? 0))->toArray();

        return view('livewire.dashboard', [
            'totalProducts' => Product::count(),
            'totalBrands' => Brand::count(),
            'totalCategories' => Category::count(),
            'lowStockProducts' => Product::where('current_stock', '<=', 5)->get(),
            'recentTransactions' => Transaction::with('user', 'details.product')
                ->latest('transaction_date')
                ->limit(10)
                ->get(),
            'totalStockIn' => Transaction::where('type', 'in')->count(),
            'totalStockOut' => Transaction::where('type', 'out')->count(),
            'totalSales' => Transaction::where('type', 'sale')->count(),
            'totalRevenue' => Transaction::where('type', 'sale')->sum('total_amount'),
            // Chart data
            'chartData' => [
                'trendLabels' => $trendLabels,
                'trendIn' => $trendIn,
                'trendOut' => $trendOut,
                'trendSale' => $trendSale,
                'revenueTrend' => $revenueTrend,
                'topProductLabels' => $topProductLabels,
                'topProductData' => $topProductData,
                'categoryLabels' => $categoryLabels,
                'categoryStockData' => $categoryStockData,
            ],
        ]);
    }
}

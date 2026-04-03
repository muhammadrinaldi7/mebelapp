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
        // 1. Data Summary - Industri Mebel
        $totalProducts = Product::count();
        $totalAssetValue = Product::sum(DB::raw('current_stock * base_price'));

        $thisMonth = Carbon::now()->startOfMonth();
        $totalRevenueThisMonth = Transaction::where('type', 'sale')
                                ->where('transaction_date', '>=', $thisMonth)
                                ->sum(DB::raw('total_amount - COALESCE(discount, 0) + COALESCE(shipping_cost, 0)'));
        
        // Modal Pokok Terjual (COGS) bulan ini: Base Price * Qty
        $cogsThisMonth = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->where('transactions.type', 'sale')
            ->where('transactions.transaction_date', '>=', $thisMonth)
            ->sum(DB::raw('transaction_details.quantity * products.base_price'));

        // Cuan Kotor = Uang Masuk Bersih (Revenue) - Modal Pokok Terjual
        $grossProfitThisMonth = $totalRevenueThisMonth - $cogsThisMonth;

        // --- Chart 1 & 2: Trend & Revenue last 30 days ---
        $days = collect();
        // 30 days is a lot for x-axis, let's step it nicely in front or collect 30 days.
        for ($i = 29; $i >= 0; $i--) {
            $days->push(Carbon::today()->subDays($i));
        }

        $trendLabels = $days->map(fn($d) => $d->translatedFormat('d M'))->toArray();

        // Optimized queries for 30 days trend
        $transactions30Days = Transaction::where('transaction_date', '>=', Carbon::today()->subDays(29))
            ->get();
            
        $trendIn = $days->map(fn($d) => $transactions30Days->where('type', 'in')
            ->filter(fn($trx) => Carbon::parse($trx->transaction_date)->isSameDay($d))->count())->toArray();
        $trendOut = $days->map(fn($d) => $transactions30Days->where('type', 'out')
            ->filter(fn($trx) => Carbon::parse($trx->transaction_date)->isSameDay($d))->count())->toArray();
        $trendSale = $days->map(fn($d) => $transactions30Days->where('type', 'sale')
            ->filter(fn($trx) => Carbon::parse($trx->transaction_date)->isSameDay($d))->count())->toArray();
        
        // Daily revenue 
        $revenueTrend = $days->map(fn($d) => (int) $transactions30Days->where('type', 'sale')
            ->filter(fn($trx) => Carbon::parse($trx->transaction_date)->isSameDay($d))
            ->sum(fn($trx) => $trx->total_amount - ($trx->discount ?? 0) + ($trx->shipping_cost ?? 0)))->toArray();

        // --- Chart 3: Top 5 selling products (Based on Value & Qty) ---
        $topProducts = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price_at_transaction) as total_revenue'))
            ->where('transactions.type', 'sale')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->with('product:id,name')
            ->get();

        $topProductLabels = $topProducts->map(fn($p) => $p->product->name ?? 'N/A')->toArray();
        $topProductData = $topProducts->map(fn($p) => (int) $p->total_revenue)->toArray();
        $topProductQty = $topProducts->map(fn($p) => (int) $p->total_qty)->toArray();

        // --- Chart 4: Stock by category (Asset Valuation View instead of just units) ---
        // Menampilkan persentase harta per kategori
        $stockByCategory = Category::with('products')->get();
        $categoryLabels = [];
        $categoryAssetData = [];
        
        foreach ($stockByCategory as $category) {
            $categoryLabels[] = $category->name;
            $assetSum = $category->products->sum(fn($p) => $p->current_stock * $p->base_price);
            $categoryAssetData[] = (int) $assetSum;
        }

        return view('livewire.dashboard', [
            'totalProducts' => $totalProducts,
            'totalAssetValue' => $totalAssetValue,
            'totalRevenueThisMonth' => $totalRevenueThisMonth,
            'grossProfitThisMonth' => $grossProfitThisMonth,
            
            'lowStockProducts' => Product::where('current_stock', '<=', 5)->get(),
            'recentTransactions' => Transaction::with('user', 'details.product')
                ->latest('transaction_date')
                ->limit(10)
                ->get(),
                
            'totalStockIn' => $transactions30Days->where('type', 'in')->count(),
            'totalStockOut' => $transactions30Days->where('type', 'out')->count(),
            'totalSales' => $transactions30Days->where('type', 'sale')->count(),
            
            // Chart data
            'chartData' => [
                'trendLabels' => $trendLabels,
                'trendIn' => array_values($trendIn),
                'trendOut' => array_values($trendOut),
                'trendSale' => array_values($trendSale),
                'revenueTrend' => array_values($revenueTrend),
                'topProductLabels' => $topProductLabels,
                'topProductData' => $topProductData,
                'topProductQty' => $topProductQty,
                'categoryLabels' => $categoryLabels,
                'categoryAssetData' => $categoryAssetData,
            ],
        ]);
    }
}


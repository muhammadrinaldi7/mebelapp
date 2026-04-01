<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
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
        ]);
    }
}

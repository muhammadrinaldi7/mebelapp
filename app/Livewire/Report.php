<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Report extends Component
{
    use WithPagination;

    public string $activeTab = 'transactions'; // transactions, stock, movement, profit

    // Common Filters
    public string $search = '';
    
    // Transaction & Movement Filters
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $reportType = 'all'; 

    // Stock Filters
    public $categoryId = '';
    public $brandId = '';

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }
    public function updatingReportType() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }
    public function updatingBrandId() { $this->resetPage(); }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    private function getTransactionsQuery()
    {
        return Transaction::with('user', 'details.product')
            ->when($this->reportType !== 'all', fn($q) => $q->where('type', $this->reportType))
            ->when($this->dateFrom, fn($q) => $q->whereDate('transaction_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('transaction_date', '<=', $this->dateTo))
            ->when($this->search, fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%'))
            ->latest('transaction_date');
    }

    private function getStockQuery()
    {
        return Product::with('category', 'brand')
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->brandId, fn($q) => $q->where('brand_id', $this->brandId))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%'))
            ->orderBy('current_stock', 'asc');
    }

    private function getMovementQuery()
    {
        // Calculate IN, OUT, SALE per product
        $subIn = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_in'))
            ->whereHas('transaction', fn($q) => $q->where('type', 'in')
                ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo)))
            ->groupBy('product_id');

        $subOut = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_out'))
            ->whereHas('transaction', fn($q) => $q->where('type', 'out')
                ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo)))
            ->groupBy('product_id');

        $subSale = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_sale'))
            ->whereHas('transaction', fn($q) => $q->where('type', 'sale')
                ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo)))
            ->groupBy('product_id');

        return Product::with('category', 'brand')
            ->leftJoinSub($subIn, 't_in', function ($join) {
                $join->on('products.id', '=', 't_in.product_id');
            })
            ->leftJoinSub($subOut, 't_out', function ($join) {
                $join->on('products.id', '=', 't_out.product_id');
            })
            ->leftJoinSub($subSale, 't_sale', function ($join) {
                $join->on('products.id', '=', 't_sale.product_id');
            })
            ->select('products.*', 
                DB::raw('COALESCE(t_in.qty_in, 0) as total_in'),
                DB::raw('COALESCE(t_out.qty_out, 0) as total_out'),
                DB::raw('COALESCE(t_sale.qty_sale, 0) as total_sale')
            )
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%'))
            ->orderByRaw('COALESCE(t_sale.qty_sale, 0) DESC');
    }

    private function getProfitQuery()
    {
        return TransactionDetail::with('product', 'transaction')
            ->whereHas('transaction', fn($q) => $q->where('type', 'sale')
                ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo))
            )
            ->when($this->search, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%')))
            ->latest('created_at');
    }

    public function getSummary()
    {
        if ($this->activeTab === 'transactions') {
            $q = clone $this->getTransactionsQuery();
            return [
                'totalTransactions' => $q->count(),
                'totalIn' => (clone $q)->where('type', 'in')->count(),
                'totalOut' => (clone $q)->where('type', 'out')->count(),
                'totalSale' => (clone $q)->where('type', 'sale')->count(),
                'totalRevenue' => (clone $q)->where('type', 'sale')->sum('total_amount'),
            ];
        }

        if ($this->activeTab === 'stock') {
            $qs = clone $this->getStockQuery();
            $items = $qs->get();
            return [
                'totalItems' => $items->count(),
                'totalStock' => $items->sum('current_stock'),
                'totalAssetValue' => $items->sum(fn($p) => $p->base_price * $p->current_stock),
                'totalPotentialRevenue' => $items->sum(fn($p) => $p->selling_price * $p->current_stock),
            ];
        }

        if ($this->activeTab === 'movement') {
            $qm = clone $this->getMovementQuery();
            $items = $qm->get();
            return [
                'totalInQty' => $items->sum('total_in'),
                'totalOutQty' => $items->sum('total_out'),
                'totalSaleQty' => $items->sum('total_sale'),
                'topMoving' => $items->firstWhere('total_sale', '>', 0)->name ?? '-',
                'deadStockCount' => $items->where('total_sale', 0)->where('total_in', 0)->count(),
            ];
        }

        if ($this->activeTab === 'profit') {
            $qp = clone $this->getProfitQuery();
            $items = $qp->get();
            $grossProfit = $items->sum(fn($d) => ($d->price_at_transaction - ($d->product->base_price ?? 0)) * $d->quantity);
            return [
                'totalSalesItems' => $items->count(),
                'totalRevenue' => $items->sum(fn($d) => $d->price_at_transaction * $d->quantity),
                'totalCost' => $items->sum(fn($d) => ($d->product->base_price ?? 0) * $d->quantity),
                'grossProfit' => $grossProfit,
            ];
        }
    }

    public function render()
    {
        $data = [];
        $summary = $this->getSummary();

        if ($this->activeTab === 'transactions') {
            $data['transactions'] = $this->getTransactionsQuery()->paginate(15);
        } elseif ($this->activeTab === 'stock') {
            $data['products'] = $this->getStockQuery()->paginate(15);
            $data['categories'] = Category::all();
            $data['brands'] = Brand::all();
        } elseif ($this->activeTab === 'movement') {
            $data['productsMove'] = $this->getMovementQuery()->paginate(15);
        } elseif ($this->activeTab === 'profit') {
            $data['profitDetails'] = $this->getProfitQuery()->paginate(15);
        }

        return view('livewire.report', array_merge([
            'summary' => $summary,
        ], $data));
    }
}

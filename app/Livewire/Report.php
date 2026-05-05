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

    public string $activeTab = 'transactions'; // transactions, stock, movement, profit, buy_price
    public string $movementType = 'all'; // all, in, out, sale

    // Common Filters
    public string $search = '';
    
    // Transaction & Movement Filters
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $reportType = 'all'; 

    // Stock Filters
    public $categoryId = '';
    public $brandId = '';

    // Buy Price Analysis Filters
    public $compareProductId = '';

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
    public function updatingCompareProductId() { $this->resetPage(); }
    public function updatingMovementType() { $this->resetPage(); }

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
        return TransactionDetail::with('product', 'transaction')
            ->whereHas('transaction', function($q) {
                $q->whereIn('type', ['in', 'out', 'sale'])
                    ->when($this->movementType !== 'all', fn($q2) => $q2->where('type', $this->movementType))
                    ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                    ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo));
            })
            ->when($this->search, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%')))
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->orderBy('transactions.transaction_date', 'desc')
            ->select('transaction_details.*');
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
            $totalIn = $items->filter(fn($d) => $d->transaction->type === 'in')->sum('quantity');
            $totalOut = $items->filter(fn($d) => $d->transaction->type === 'out')->sum('quantity');
            $totalSale = $items->filter(fn($d) => $d->transaction->type === 'sale')->sum('quantity');
            $totalValue = $items->sum(fn($d) => $d->quantity * $d->price_at_transaction);
            return [
                'totalInQty' => $totalIn,
                'totalOutQty' => $totalOut,
                'totalSaleQty' => $totalSale,
                'totalValue' => $totalValue,
            ];
        }

        if ($this->activeTab === 'profit') {
            $qp = clone $this->getProfitQuery();
            $items = $qp->get();
            
            $uniqueTransactionIds = $items->pluck('transaction_id')->unique();
            $totalDiscount = Transaction::whereIn('id', $uniqueTransactionIds)->sum('discount');
            
            $grossProfit = $items->sum(fn($d) => ($d->price_at_transaction - ($d->product->base_price ?? 0)) * $d->quantity);
            $netGrossProfit = $grossProfit - $totalDiscount;

            return [
                'totalSalesItems' => $items->count(),
                'totalRevenue' => $items->sum(fn($d) => $d->price_at_transaction * $d->quantity),
                'totalCost' => $items->sum(fn($d) => ($d->product->base_price ?? 0) * $d->quantity),
                'totalDiscount' => $totalDiscount,
                'grossProfit' => $grossProfit,
                'netGrossProfit' => $netGrossProfit,
            ];
        }

        if ($this->activeTab === 'buy_price') {
            // Summary logic if needed for buy_price
            return [];
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
            $data['mutations'] = $this->getMovementQuery()->paginate(20);
        } elseif ($this->activeTab === 'profit') {
            $data['profitDetails'] = $this->getProfitQuery()->paginate(15);
        } elseif ($this->activeTab === 'buy_price') {
            $data['productsFilter'] = Product::orderBy('name')->get();
            
            // Query for specific product
            if ($this->compareProductId) {
                // Get history of incoming transactions for the product
                $history = TransactionDetail::with('transaction')
                    ->where('product_id', $this->compareProductId)
                    ->whereHas('transaction', fn($q) => $q->where('type', 'in')
                        ->when($this->dateFrom, fn($q2) => $q2->whereDate('transaction_date', '>=', $this->dateFrom))
                        ->when($this->dateTo, fn($q2) => $q2->whereDate('transaction_date', '<=', $this->dateTo))
                    )
                    ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->orderBy('transactions.transaction_date', 'asc')
                    ->select('transaction_details.*') // Ensure we get detail columns, not joined duplicates
                    ->get();
                
                $data['priceHistory'] = $history;
                
                $data['chartLabels'] = $history->map(fn($d) => Carbon::parse($d->transaction->transaction_date)->format('d M Y'))->toArray();
                $data['chartPrices'] = $history->map(fn($d) => (float) $d->price_at_transaction)->toArray();
            } else {
                $data['priceHistory'] = collect();
                $data['chartLabels'] = [];
                $data['chartPrices'] = [];
            }
        }

        return view('livewire.report', array_merge([
            'summary' => $summary,
        ], $data));
    }
}


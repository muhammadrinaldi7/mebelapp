<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Sales extends Component
{
    use WithPagination;

    public $showForm = false;
    public $reference_code = '';
    public $transaction_date = '';
    public $notes = '';
    public $items = [];

    public $search = '';

    protected $rules = [
        'reference_code' => 'required|string|max:255',
        'transaction_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openForm()
    {
        $this->reset(['reference_code', 'notes', 'items']);
        $this->transaction_date = now()->format('Y-m-d');
        $this->reference_code = 'SALE-' . date('YmdHis');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
        $this->showForm = true;
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function updatedItems($value, $key)
    {
        // Auto-fill selling price when product is selected
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'product_id' && $value) {
            $product = Product::find($value);
            if ($product) {
                $index = $parts[0];
                $this->items[$index]['price'] = $product->selling_price;
            }
        }
    }

    public function save()
    {
        $this->validate();

        // Validate stock availability
        foreach ($this->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->current_stock < $item['quantity']) {
                session()->flash('error', "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}");
                return;
            }
        }

        DB::transaction(function () {
            $totalAmount = collect($this->items)->sum(fn($item) => $item['quantity'] * $item['price']);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'sale',
                'reference_code' => $this->reference_code,
                'transaction_date' => $this->transaction_date,
                'notes' => $this->notes,
                'total_amount' => $totalAmount,
            ]);

            foreach ($this->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => $item['price'],
                ]);
            }
        });

        $this->showForm = false;
        session()->flash('message', 'Penjualan berhasil disimpan.');
    }

    public function render()
    {
        $transactions = Transaction::with('user', 'details.product')
            ->where('type', 'sale')
            ->when($this->search, fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%'))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.sales', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
        ]);
    }
}

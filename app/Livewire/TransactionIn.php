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
class TransactionIn extends Component
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
        $this->reference_code = 'IN-' . date('YmdHis');
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

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $totalAmount = collect($this->items)->sum(fn($item) => $item['quantity'] * $item['price']);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'in',
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
        session()->flash('message', 'Transaksi barang masuk berhasil disimpan.');
    }

    public function render()
    {
        $transactions = Transaction::with('user', 'details.product')
            ->where('type', 'in')
            ->when($this->search, fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%'))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.transaction-in', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
        ]);
    }
}

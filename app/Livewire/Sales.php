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
    public $showEditForm = false;
    public $editingTransactionId = null;
    public $reference_code = '';
    public $transaction_date = '';
    public $notes = '';
    public $items = [];

    // Customer & Financial Additions
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $discount = 0;
    public $shipping_cost = 0;

    // Mebel Specific
    public $payment_status = 'lunas';
    public $down_payment = 0;
    public $shipping_status = 'bawa_sendiri';
    public $driver_name = '';

    // Edit Specific
    public $edit_payment_status = 'lunas';
    public $edit_down_payment = 0;
    public $edit_shipping_status = 'bawa_sendiri';
    public $edit_driver_name = '';

    public $search = '';

    protected $rules = [
        'reference_code' => 'required|string|max:255',
        'transaction_date' => 'required|date',
        'customer_name' => 'nullable|string|max:255',
        'customer_phone' => 'nullable|string|max:50',
        'customer_address' => 'nullable|string',
        'discount' => 'nullable|numeric|min:0',
        'shipping_cost' => 'nullable|numeric|min:0',
        'payment_status' => 'required|in:lunas,dp,belum_dibayar',
        'down_payment' => 'nullable|numeric|min:0',
        'shipping_status' => 'required|in:bawa_sendiri,menunggu_dikirim,sedang_dikirim,sudah_diterima',
        'driver_name' => 'nullable|string|max:255',
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
        $this->reset(['reference_code', 'notes', 'items', 'customer_name', 'customer_phone', 'customer_address', 'discount', 'shipping_cost', 'payment_status', 'down_payment', 'shipping_status', 'driver_name']);
        $this->transaction_date = now()->format('Y-m-d');
        $this->reference_code = 'SALE-' . date('YmdHis');
        $this->discount = 0;
        $this->shipping_cost = 0;
        $this->payment_status = 'lunas';
        $this->down_payment = 0;
        $this->shipping_status = 'bawa_sendiri';
        $this->driver_name = '';
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
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'customer_address' => $this->customer_address,
                'notes' => $this->notes,
                'total_amount' => $totalAmount,
                'discount' => $this->discount ?: 0,
                'shipping_cost' => $this->shipping_cost ?: 0,
                'payment_status' => $this->payment_status,
                'down_payment' => $this->payment_status === 'dp' ? ($this->down_payment ?: 0) : 0,
                'shipping_status' => $this->shipping_status,
                'driver_name' => $this->shipping_status !== 'bawa_sendiri' ? $this->driver_name : null,
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

    public function openEditForm($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $this->editingTransactionId = $transaction->id;
            $this->edit_payment_status = $transaction->payment_status ?? 'lunas';
            $this->edit_down_payment = (float)($transaction->down_payment ?? 0);
            $this->edit_shipping_status = $transaction->shipping_status ?? 'bawa_sendiri';
            $this->edit_driver_name = $transaction->driver_name ?? '';
            $this->showEditForm = true;
        }
    }

    public function updateStatus()
    {
        $this->validate([
            'edit_payment_status' => 'required|in:lunas,dp,belum_dibayar',
            'edit_down_payment' => 'nullable|numeric|min:0',
            'edit_shipping_status' => 'required|in:bawa_sendiri,menunggu_dikirim,sedang_dikirim,sudah_diterima',
            'edit_driver_name' => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::find($this->editingTransactionId);
        if ($transaction) {
            $transaction->update([
                'payment_status' => $this->edit_payment_status,
                'down_payment' => $this->edit_payment_status === 'dp' ? ($this->edit_down_payment ?: 0) : 0,
                'shipping_status' => $this->edit_shipping_status,
                'driver_name' => $this->edit_shipping_status !== 'bawa_sendiri' ? $this->edit_driver_name : null,
            ]);
            $this->showEditForm = false;
            session()->flash('message', 'Status transaksi berhasil diperbarui.');
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum(fn($item) => ((float)($item['price'] ?? 0)) * ((int)($item['quantity'] ?? 0)));
    }

    public function getGrandTotalProperty()
    {
        $sub = $this->subtotal;
        $disc = (float)($this->discount ?: 0);
        $ship = (float)($this->shipping_cost ?: 0);
        return $sub - $disc + $ship;
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

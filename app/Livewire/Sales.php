<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionPayment;
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
    public $showDetailModal = false;
    public $editingTransactionId = null;
    public $selectedTransaction = null;
    public $reference_code = '';
    public $transaction_date = '';
    public $notes = '';
    public $items = [];

    public $start_date = '';
    public $end_date = '';

    // Customer & Financial Additions
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $salesperson_name = '';
    public $discount = 0;
    public $shipping_cost = 0;

    // Mebel Specific
    public $shipping_status = 'bawa_sendiri';
    public $driver_name = '';

    // Multi-Payment: Create form
    public $payments = [];

    // Edit Specific
    public $edit_shipping_status = 'bawa_sendiri';
    public $edit_driver_name = '';
    public $edit_existing_payments = []; // Pembayaran yang sudah tersimpan (readonly)
    public $edit_new_payments = [];      // Pembayaran baru (pelunasan)
    public $edit_items = [];
    public $original_items = [];

    public $search = '';

    protected $rules = [
        'reference_code' => 'required|string|max:255',
        'transaction_date' => 'required|date',
        'customer_name' => 'nullable|string|max:255',
        'customer_phone' => 'nullable|string|max:50',
        'customer_address' => 'nullable|string',
        'salesperson_name' => 'nullable|string|max:255',
        'discount' => 'nullable|numeric|min:0',
        'shipping_cost' => 'nullable|numeric|min:0',
        'shipping_status' => 'required|in:bawa_sendiri,menunggu_dikirim,sedang_dikirim,sudah_diterima',
        'driver_name' => 'nullable|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'payments' => 'required|array|min:1',
        'payments.*.payment_method_id' => 'required|exists:payment_methods,id',
        'payments.*.amount' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'payments.required' => 'Minimal satu pembayaran harus diisi.',
        'payments.min' => 'Minimal satu pembayaran harus diisi.',
        'payments.*.payment_method_id.required' => 'Pilih metode pembayaran.',
        'payments.*.amount.required' => 'Nominal pembayaran harus diisi.',
        'payments.*.amount.min' => 'Nominal pembayaran minimal 1.',
    ];

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
        $this->payments = [['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0]];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function getDefaultPaymentMethodId()
    {
        $tunai = PaymentMethod::where('name', 'Tunai')->where('is_active', true)->first();
        return $tunai ? $tunai->id : (PaymentMethod::where('is_active', true)->first()?->id ?? '');
    }

    public function openForm()
    {
        $this->reset(['reference_code', 'notes', 'items', 'customer_name', 'customer_phone', 'customer_address', 'salesperson_name', 'discount', 'shipping_cost', 'shipping_status', 'driver_name', 'payments']);
        $this->transaction_date = now()->format('Y-m-d');
        $this->reference_code = 'SALE-' . date('YmdHis');
        $this->discount = 0;
        $this->shipping_cost = 0;
        $this->shipping_status = 'bawa_sendiri';
        $this->driver_name = '';
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
        $this->payments = [['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0]];
        $this->showForm = true;
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    // Add/Edit item methods for edit modal
    public function addEditItem()
    {
        $this->edit_items[] = ['id' => null, 'product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeEditItem($index)
    {
        if (count($this->edit_items) > 1) {
            unset($this->edit_items[$index]);
            $this->edit_items = array_values($this->edit_items);
        }
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function addPayment()
    {
        $this->payments[] = ['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0];
    }

    public function removePayment($index)
    {
        if (count($this->payments) > 1) {
            unset($this->payments[$index]);
            $this->payments = array_values($this->payments);
        }
    }

    public function addEditPayment()
    {
        $this->edit_new_payments[] = ['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0, 'payment_date' => now()->format('Y-m-d')];
    }

    public function removeEditPayment($index)
    {
        unset($this->edit_new_payments[$index]);
        $this->edit_new_payments = array_values($this->edit_new_payments);
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

    public function updatedEditItems($value, $key)
    {
        // Auto-fill selling price when product is selected in edit modal
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'product_id' && $value) {
            $product = Product::find($value);
            if ($product) {
                $index = $parts[0];
                $this->edit_items[$index]['price'] = $product->selling_price;
            }
        }
    }

    /**
     * Determine payment status based on total paid vs grand total.
     */
    private function calculatePaymentStatus(float $totalPaid, float $grandTotal): string
    {
        if ($totalPaid >= $grandTotal && $grandTotal > 0) {
            return 'lunas';
        } elseif ($totalPaid > 0) {
            return 'dp';
        }
        return 'belum_dibayar';
    }

    public function save()
    {
        $this->validate();

        // Validate stock availability
        foreach ($this->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->current_stock < $item['quantity']) {
                $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}");
                return;
            }
        }

        DB::transaction(function () {
            $totalAmount = collect($this->items)->sum(fn($item) => $item['quantity'] * $item['price']);
            $grandTotal = $totalAmount - ($this->discount ?: 0) + ($this->shipping_cost ?: 0);
            $totalPaid = collect($this->payments)->sum(fn($p) => (float) ($p['amount'] ?? 0));

            $paymentStatus = $this->calculatePaymentStatus($totalPaid, $grandTotal);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'sale',
                'reference_code' => $this->reference_code,
                'transaction_date' => $this->transaction_date,
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'customer_address' => $this->customer_address,
                'salesperson_name' => $this->salesperson_name,
                'notes' => $this->notes,
                'total_amount' => $totalAmount,
                'discount' => $this->discount ?: 0,
                'shipping_cost' => $this->shipping_cost ?: 0,
                'payment_status' => $paymentStatus,
                'down_payment' => $totalPaid, // Keep for backward compat
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

            // Save payment records
            foreach ($this->payments as $payment) {
                if ((float)($payment['amount'] ?? 0) > 0) {
                    TransactionPayment::create([
                        'transaction_id' => $transaction->id,
                        'payment_method_id' => $payment['payment_method_id'],
                        'amount' => $payment['amount'],
                        'payment_date' => $this->transaction_date,
                        'notes' => null,
                    ]);
                }
            }
        });

        $this->showForm = false;
        $this->dispatch('notify', type: 'success', message: 'Penjualan berhasil disimpan.');
    }

    public function openEditForm($id)
    {
        $transaction = Transaction::with(['payments.paymentMethod', 'details'])->find($id);
        if ($transaction) {
            $this->editingTransactionId = $transaction->id;
            $this->edit_shipping_status = $transaction->shipping_status ?? 'bawa_sendiri';
            $this->edit_driver_name = $transaction->driver_name ?? '';

            // Load existing payments for display
            $this->edit_existing_payments = $transaction->payments->map(fn($p) => [
                'id' => $p->id,
                'method_name' => $p->paymentMethod->name ?? 'N/A',
                'amount' => (float) $p->amount,
                'payment_date' => $p->payment_date->format('d/m/Y'),
                'notes' => $p->notes,
            ])->toArray();

            // Load transaction items for editing
            $this->edit_items = $transaction->details->map(fn($d) => [
                'id' => $d->id,
                'product_id' => $d->product_id,
                'quantity' => $d->quantity,
                'price' => $d->price_at_transaction,
            ])->toArray();
            // Keep a copy of original items for stock diff calculation
            $this->original_items = $this->edit_items;

            // Prepare empty new payment form
            $this->edit_new_payments = [];

            $this->showEditForm = true;
        }
    }

    public function updateStatus()
    {
        $rules = [
            'edit_shipping_status' => 'required|in:bawa_sendiri,menunggu_dikirim,sedang_dikirim,sudah_diterima',
            'edit_driver_name' => 'nullable|string|max:255',
            'edit_items' => 'required|array|min:1',
            'edit_items.*.product_id' => 'required|exists:products,id',
            'edit_items.*.quantity' => 'required|integer|min:1',
        ];

        // If there are new payments, validate them
        if (count($this->edit_new_payments) > 0) {
            $rules['edit_new_payments.*.payment_method_id'] = 'required|exists:payment_methods,id';
            $rules['edit_new_payments.*.amount'] = 'required|numeric|min:1';
            $rules['edit_new_payments.*.payment_date'] = 'required|date';
        }

        $this->validate($rules);

        // Stock validation: check if new quantities/products have enough stock
        foreach ($this->edit_items as $idx => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $original = $this->original_items[$idx] ?? null;
            $availableStock = $product->current_stock;

            // If same product, available = current_stock + original_qty (since we'll restore first)
            if ($original && $original['product_id'] == $item['product_id']) {
                $availableStock = $product->current_stock + $original['quantity'];
            }

            if ($availableStock < $item['quantity']) {
                $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$availableStock}");
                return;
            }
        }

        $transaction = Transaction::with('payments', 'details')->find($this->editingTransactionId);
        if ($transaction) {
            DB::transaction(function () use ($transaction) {
                // Build lookup of original items by their detail ID
                $originalById = collect($this->original_items)->keyBy('id');
                $editedIds = collect($this->edit_items)->pluck('id')->filter()->toArray();

                // 1. Handle removed items: restore stock for originals no longer present
                foreach ($this->original_items as $original) {
                    if ($original['id'] && !in_array($original['id'], $editedIds)) {
                        // Restore stock
                        Product::where('id', $original['product_id'])->increment('current_stock', $original['quantity']);
                        // Delete the detail record
                        TransactionDetail::where('id', $original['id'])->delete();
                    }
                }

                // 2. Handle existing and new items
                foreach ($this->edit_items as $idx => $item) {
                    if ($item['id']) {
                        // Existing item — find the matching original
                        $original = $originalById->get($item['id']);
                        if ($original) {
                            if ($original['product_id'] == $item['product_id']) {
                                // Same product: adjust stock by diff
                                $diff = $original['quantity'] - $item['quantity'];
                                if ($diff != 0) {
                                    Product::where('id', $item['product_id'])->increment('current_stock', $diff);
                                }
                            } else {
                                // Product changed: restore old, deduct new
                                Product::where('id', $original['product_id'])->increment('current_stock', $original['quantity']);
                                Product::where('id', $item['product_id'])->decrement('current_stock', $item['quantity']);
                            }
                            // Update detail record
                            TransactionDetail::where('id', $item['id'])->update([
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'price_at_transaction' => $item['price'],
                            ]);
                        }
                    } else {
                        // New item — deduct stock and create detail
                        Product::where('id', $item['product_id'])->decrement('current_stock', $item['quantity']);
                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price_at_transaction' => $item['price'],
                        ]);
                    }
                }

                // Save new payments
                foreach ($this->edit_new_payments as $payment) {
                    if ((float)($payment['amount'] ?? 0) > 0) {
                        TransactionPayment::create([
                            'transaction_id' => $transaction->id,
                            'payment_method_id' => $payment['payment_method_id'],
                            'amount' => $payment['amount'],
                            'payment_date' => $payment['payment_date'] ?? now()->format('Y-m-d'),
                            'notes' => null,
                        ]);
                    }
                }

                // Recalculate total_amount from edited items
                $newTotalAmount = collect($this->edit_items)->sum(fn($item) => ((float)($item['price'] ?? 0)) * ((int)($item['quantity'] ?? 0)));

                // Recalculate payment status
                $totalPaid = $transaction->payments()->sum('amount') + collect($this->edit_new_payments)->sum(fn($p) => (float)($p['amount'] ?? 0));
                $grandTotal = $newTotalAmount - ($transaction->discount ?? 0) + ($transaction->shipping_cost ?? 0);
                $paymentStatus = $this->calculatePaymentStatus($totalPaid, $grandTotal);

                $transaction->update([
                    'total_amount' => $newTotalAmount,
                    'payment_status' => $paymentStatus,
                    'down_payment' => $totalPaid,
                    'shipping_status' => $this->edit_shipping_status,
                    'driver_name' => $this->edit_shipping_status !== 'bawa_sendiri' ? $this->edit_driver_name : null,
                ]);
            });

            $this->showEditForm = false;
            $this->dispatch('notify', type: 'success', message: 'Transaksi berhasil diperbarui.');
        }
    }

    public function deleteTransaction($id)
    {
        $transaction = Transaction::with('details')->find($id);

        if ($transaction) {
            DB::transaction(function () use ($transaction) {
                // Delete details one by one to trigger observer for stock restoration
                foreach ($transaction->details as $detail) {
                    $detail->delete();
                }

                // Delete payments
                $transaction->payments()->delete();

                // Delete transaction
                $transaction->delete();
            });

            $this->dispatch('notify', type: 'success', message: 'Transaksi penjualan berhasil dihapus dan stok dikembalikan.');
        } else {
            $this->dispatch('notify', type: 'error', message: 'Transaksi tidak ditemukan.');
        }
    }

    public function openDetailModal($id)
    {
        $this->selectedTransaction = Transaction::with(['user', 'details.product', 'details.product.category', 'details.product.brand', 'payments.paymentMethod'])->find($id);
        if ($this->selectedTransaction) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
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

    public function getTotalPaidProperty()
    {
        return collect($this->payments)->sum(fn($p) => (float)($p['amount'] ?? 0));
    }

    public function getRemainingBalanceProperty()
    {
        return max(0, $this->grandTotal - $this->totalPaid);
    }

    public function getEditGrandTotalProperty()
    {
        $transaction = Transaction::find($this->editingTransactionId);
        if (!$transaction) return 0;
        // Use edit_items for real-time subtotal calculation
        $subtotal = collect($this->edit_items)->sum(fn($item) => ((float)($item['price'] ?? 0)) * ((int)($item['quantity'] ?? 0)));
        return $subtotal - ($transaction->discount ?? 0) + ($transaction->shipping_cost ?? 0);
    }

    public function getEditTotalPaidProperty()
    {
        $existing = collect($this->edit_existing_payments)->sum('amount');
        $new = collect($this->edit_new_payments)->sum(fn($p) => (float)($p['amount'] ?? 0));
        return $existing + $new;
    }

    public function getEditRemainingProperty()
    {
        return max(0, $this->editGrandTotal - $this->editTotalPaid);
    }

    public function render()
    {
        $transactions = Transaction::with('user', 'details.product', 'payments')
            ->where('type', 'sale')
            ->when(
                $this->search,
                fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            )
            ->when($this->start_date, fn($q) => $q->whereDate('transaction_date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('transaction_date', '<=', $this->end_date))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.sales', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

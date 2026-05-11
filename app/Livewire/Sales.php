<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
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
    public $showConvertModal = false;
    public $editingTransactionId = null;
    public $selectedTransaction = null;
    public $reference_code = '';
    public $transaction_date = '';
    public $notes = '';
    public $items = [];

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

    // Convert PO to Product
    public $convertDetailId = null;
    public $convert_sku = '';
    public $convert_name = '';
    public $convert_category_id = '';
    public $convert_brand_id = '';
    public $convert_base_price = 0;
    public $convert_selling_price = 0;
    public $convert_stock = 1;
    public $convert_satuan = 'pcs';

    public $search = '';

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0, 'mode' => 'catalog', 'custom_name' => '']];
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
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0, 'mode' => 'catalog', 'custom_name' => '']];
        $this->payments = [['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0]];
        $this->showForm = true;
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'mode' => 'catalog', 'custom_name' => ''];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function toggleItemMode($index)
    {
        $currentMode = $this->items[$index]['mode'] ?? 'catalog';
        $this->items[$index]['mode'] = $currentMode === 'catalog' ? 'manual' : 'catalog';
        // Reset fields when toggling
        $this->items[$index]['product_id'] = '';
        $this->items[$index]['custom_name'] = '';
        $this->items[$index]['price'] = 0;
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
        $this->edit_new_payments[] = ['payment_method_id' => $this->getDefaultPaymentMethodId(), 'amount' => 0];
    }

    public function removeEditPayment($index)
    {
        unset($this->edit_new_payments[$index]);
        $this->edit_new_payments = array_values($this->edit_new_payments);
    }

    public function updatedItems($value, $key)
    {
        // Auto-fill selling price when product is selected (catalog mode only)
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'product_id' && $value) {
            $product = Product::find($value);
            if ($product) {
                $index = $parts[0];
                $this->items[$index]['price'] = $product->selling_price;
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
        // Build dynamic validation rules
        $rules = [
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
            'payments' => 'required|array|min:1',
            'payments.*.payment_method_id' => 'required|exists:payment_methods,id',
            'payments.*.amount' => 'required|numeric|min:1',
        ];

        // Dynamic item validation based on mode
        foreach ($this->items as $idx => $item) {
            $mode = $item['mode'] ?? 'catalog';
            if ($mode === 'catalog') {
                $rules["items.{$idx}.product_id"] = 'required|exists:products,id';
            } else {
                $rules["items.{$idx}.custom_name"] = 'required|string|max:255';
            }
            $rules["items.{$idx}.quantity"] = 'required|integer|min:1';
            $rules["items.{$idx}.price"] = 'required|numeric|min:0';
        }

        $this->validate($rules, [
            'items.*.custom_name.required' => 'Nama produk PO harus diisi.',
            'items.*.product_id.required' => 'Pilih produk dari katalog.',
            'payments.*.payment_method_id.required' => 'Pilih metode pembayaran.',
            'payments.*.amount.required' => 'Nominal pembayaran harus diisi.',
            'payments.*.amount.min' => 'Nominal pembayaran minimal 1.',
        ]);

        // Validate stock availability for catalog items only
        foreach ($this->items as $item) {
            if (($item['mode'] ?? 'catalog') === 'catalog') {
                $product = Product::find($item['product_id']);
                if ($product && $product->current_stock < $item['quantity']) {
                    $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}");
                    return;
                }
            }
        }

        // Check if any item is manual/PO
        $hasPreorderItems = collect($this->items)->contains(fn($item) => ($item['mode'] ?? 'catalog') === 'manual');

        DB::transaction(function () use ($hasPreorderItems) {
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
                'down_payment' => $totalPaid,
                'shipping_status' => $this->shipping_status,
                'driver_name' => $this->shipping_status !== 'bawa_sendiri' ? $this->driver_name : null,
                'is_preorder' => $hasPreorderItems,
            ]);

            foreach ($this->items as $item) {
                $mode = $item['mode'] ?? 'catalog';

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $mode === 'catalog' ? $item['product_id'] : null,
                    'custom_product_name' => $mode === 'manual' ? $item['custom_name'] : null,
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
        $transaction = Transaction::with('payments.paymentMethod')->find($id);
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
        ];

        // If there are new payments, validate them
        if (count($this->edit_new_payments) > 0) {
            $rules['edit_new_payments.*.payment_method_id'] = 'required|exists:payment_methods,id';
            $rules['edit_new_payments.*.amount'] = 'required|numeric|min:1';
        }

        $this->validate($rules);

        $transaction = Transaction::with('payments', 'details')->find($this->editingTransactionId);
        if ($transaction) {
            DB::transaction(function () use ($transaction) {
                // Save new payments
                foreach ($this->edit_new_payments as $payment) {
                    if ((float)($payment['amount'] ?? 0) > 0) {
                        TransactionPayment::create([
                            'transaction_id' => $transaction->id,
                            'payment_method_id' => $payment['payment_method_id'],
                            'amount' => $payment['amount'],
                            'payment_date' => now()->format('Y-m-d'),
                            'notes' => null,
                        ]);
                    }
                }

                // Recalculate payment status
                $totalPaid = $transaction->payments()->sum('amount') + collect($this->edit_new_payments)->sum(fn($p) => (float)($p['amount'] ?? 0));
                $grandTotal = ($transaction->total_amount ?? 0) - ($transaction->discount ?? 0) + ($transaction->shipping_cost ?? 0);
                $paymentStatus = $this->calculatePaymentStatus($totalPaid, $grandTotal);

                $transaction->update([
                    'payment_status' => $paymentStatus,
                    'down_payment' => $totalPaid, // Keep backward compat
                    'shipping_status' => $this->edit_shipping_status,
                    'driver_name' => $this->edit_shipping_status !== 'bawa_sendiri' ? $this->edit_driver_name : null,
                ]);
            });

            $this->showEditForm = false;
            $this->dispatch('notify', type: 'success', message: 'Status transaksi berhasil diperbarui.');
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

    // =====================================================
    // Convert PO Item to Product
    // =====================================================

    public function openConvertModal($detailId)
    {
        $detail = TransactionDetail::find($detailId);
        if (!$detail || $detail->product_id !== null) {
            $this->dispatch('notify', type: 'error', message: 'Item ini sudah memiliki produk terdaftar.');
            return;
        }

        $this->convertDetailId = $detailId;
        $this->convert_name = $detail->custom_product_name ?? '';
        $this->convert_sku = '';
        $this->convert_category_id = '';
        $this->convert_brand_id = '';
        $this->convert_base_price = 0;
        $this->convert_selling_price = $detail->price_at_transaction;
        $this->convert_stock = $detail->quantity;
        $this->convert_satuan = 'pcs';

        $this->showConvertModal = true;
    }

    public function closeConvertModal()
    {
        $this->showConvertModal = false;
        $this->convertDetailId = null;
    }

    public function convertToProduct()
    {
        $this->validate([
            'convert_sku' => 'required|string|max:255|unique:products,sku',
            'convert_name' => 'required|string|max:255',
            'convert_category_id' => 'required|exists:categories,id',
            'convert_brand_id' => 'required|exists:brands,id',
            'convert_base_price' => 'required|numeric|min:0',
            'convert_selling_price' => 'required|numeric|min:0',
            'convert_stock' => 'required|integer|min:0',
            'convert_satuan' => 'required|string|max:50',
        ], [
            'convert_sku.required' => 'SKU harus diisi.',
            'convert_sku.unique' => 'SKU sudah digunakan produk lain.',
            'convert_name.required' => 'Nama produk harus diisi.',
            'convert_category_id.required' => 'Pilih kategori.',
            'convert_brand_id.required' => 'Pilih merek.',
        ]);

        $detail = TransactionDetail::with('transaction')->find($this->convertDetailId);
        if (!$detail) {
            $this->dispatch('notify', type: 'error', message: 'Data item tidak ditemukan.');
            return;
        }

        DB::transaction(function () use ($detail) {
            // 1. Create the new Product with stock = 0
            //    (Observer akan otomatis increment saat Barang Masuk dibuat)
            $product = Product::create([
                'sku' => $this->convert_sku,
                'name' => $this->convert_name,
                'category_id' => $this->convert_category_id,
                'brand_id' => $this->convert_brand_id,
                'base_price' => $this->convert_base_price,
                'selling_price' => $this->convert_selling_price,
                'current_stock' => 0, // Mulai dari 0, akan ditambah oleh Observer
                'satuan' => $this->convert_satuan,
            ]);

            // 2. Link the transaction detail to the product
            $detail->update([
                'product_id' => $product->id,
                'custom_product_name' => null,
            ]);

            // 3. Create "Barang Masuk" record → Observer auto-increment stock
            $incomingTrx = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'in',
                'reference_code' => 'PO-IN-' . date('YmdHis'),
                'transaction_date' => now()->format('Y-m-d'),
                'notes' => 'Auto: Konversi PO dari penjualan ' . $detail->transaction->reference_code,
                'total_amount' => $this->convert_base_price * $this->convert_stock,
                'payment_status' => 'lunas',
                'shipping_status' => 'sudah_diterima',
            ]);

            // Observer akan otomatis increment current_stock produk
            TransactionDetail::create([
                'transaction_id' => $incomingTrx->id,
                'product_id' => $product->id,
                'quantity' => $this->convert_stock,
                'price_at_transaction' => $this->convert_base_price,
            ]);

            // 4. Check if all PO items in the parent transaction are now converted
            $remainingPO = $detail->transaction->details()
                ->whereNull('product_id')
                ->where('custom_product_name', '!=', null)
                ->count();

            if ($remainingPO === 0) {
                $detail->transaction->update(['is_preorder' => false]);
            }
        });

        $this->showConvertModal = false;
        $this->convertDetailId = null;

        // Refresh the detail modal if it's open
        if ($this->selectedTransaction) {
            $this->selectedTransaction = Transaction::with(['user', 'details.product', 'details.product.category', 'details.product.brand', 'payments.paymentMethod'])->find($this->selectedTransaction->id);
        }

        $this->dispatch('notify', type: 'success', message: 'Produk berhasil dibuat dan item PO telah di-link!');
    }

    // =====================================================
    // Computed Properties
    // =====================================================

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
        return ($transaction->total_amount ?? 0) - ($transaction->discount ?? 0) + ($transaction->shipping_cost ?? 0);
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
            ->when($this->search, fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%'))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.sales', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }
}

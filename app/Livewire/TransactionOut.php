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
class TransactionOut extends Component
{
    use WithPagination;

    public $showForm = false;
    public $reference_code = '';
    public $transaction_date = '';
    public $out_reason = 'Pindah';
    public $notes = '';
    public $items = [];

    public $search = '';

    // Edit State
    public $showEditForm = false;
    public $edit_transaction_id = null;
    public $edit_reference_code = '';
    public $edit_transaction_date = '';
    public $edit_notes = '';
    public $edit_items = [];

    // Delete State
    public $showDeleteConfirm = false;
    public $delete_transaction_id = null;

    // Detail State
    public $showDetailModal = false;
    public $detail_transaction = null;

    protected $rules = [
        'reference_code' => 'required|string|max:255',
        'transaction_date' => 'required|date',
        'out_reason' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->items = [['product_id' => '', 'quantity' => 1]];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $trx = Transaction::with('user', 'details.product')->findOrFail($id);
        $this->detail_transaction = [
            'reference_code' => $trx->reference_code,
            'transaction_date' => $trx->transaction_date->format('d M Y'),
            'user_name' => $trx->user->name ?? '-',
            'notes' => $trx->notes ?: '-',
            'details' => $trx->details->map(fn($d) => [
                'product_name' => $d->product->name ?? '-',
                'quantity' => $d->quantity,
                'satuan' => $d->product->satuan ?? '-',
            ])->toArray(),
        ];
        $this->showDetailModal = true;
    }

    public function openForm()
    {
        $this->reset(['reference_code', 'notes', 'items', 'out_reason']);
        $this->transaction_date = now()->format('Y-m-d');
        $this->reference_code = 'OUT-' . date('YmdHis');
        $this->out_reason = 'Pindah';
        $this->items = [['product_id' => '', 'quantity' => 1]];
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        if (!Auth::user()->can('edit-barang-keluar')) {
            abort(403, 'Akses ditolak.');
        }

        $transaction = Transaction::with('details.product')->findOrFail($id);

        $this->edit_transaction_id = $transaction->id;
        $this->edit_reference_code = $transaction->reference_code;
        $this->edit_transaction_date = $transaction->transaction_date->format('Y-m-d');
        $this->edit_notes = $transaction->notes ?: '';
        $this->edit_items = [];

        foreach ($transaction->details as $detail) {
            $this->edit_items[] = [
                'id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $detail->product->name ?? 'Produk Terhapus',
                'satuan' => $detail->product->satuan ?? '-',
                'quantity' => $detail->quantity,
                'current_stock' => $detail->product->current_stock ?? 0,
                'original_product_id' => $detail->product_id,
                'original_quantity' => $detail->quantity,
            ];
        }
        $this->showEditForm = true;
    }

    public function updateTransaction()
    {
        if (!Auth::user()->can('edit-barang-keluar')) {
            abort(403, 'Akses ditolak.');
        }

        $rules = [
            'edit_reference_code' => 'required|string|max:255',
            'edit_transaction_date' => 'required|date',
            'edit_items.*.product_id' => 'required|exists:products,id',
            'edit_items.*.quantity' => 'required|integer|min:1',
        ];

        $this->validate($rules);

        // Validate stock availability for qty increases, new items, and product swaps
        foreach ($this->edit_items as $item) {
            if (empty($item['id'])) {
                // New item added
                $product = Product::find($item['product_id']);
                if ($product && $product->current_stock < $item['quantity']) {
                    $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}, dibutuhkan: {$item['quantity']}");
                    return;
                }
            } else {
                $detail = TransactionDetail::find($item['id']);
                if ($detail) {
                    $origProdId = $item['original_product_id'] ?? $detail->product_id;
                    $origQty = $item['original_quantity'] ?? $detail->quantity;

                    if ($origProdId == $item['product_id']) {
                        // Same product
                        $diff = $item['quantity'] - $origQty;
                        if ($diff > 0) {
                            $product = Product::find($detail->product_id);
                            if ($product && $product->current_stock < $diff) {
                                $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}, dibutuhkan tambahan: {$diff}");
                                return;
                            }
                        }
                    } else {
                        // Product changed: check new product has enough stock
                        $newProduct = Product::find($item['product_id']);
                        if ($newProduct && $newProduct->current_stock < $item['quantity']) {
                            $this->dispatch('notify', type: 'error', message: "Stok {$newProduct->name} tidak mencukupi. Tersedia: {$newProduct->current_stock}, dibutuhkan: {$item['quantity']}");
                            return;
                        }
                    }
                }
            }
        }

        $existingDetailIds = TransactionDetail::where('transaction_id', $this->edit_transaction_id)->pluck('id')->toArray();
        $keptDetailIds = collect($this->edit_items)->pluck('id')->filter()->toArray();
        $deletedDetailIds = array_diff($existingDetailIds, $keptDetailIds);

        DB::transaction(function () use ($deletedDetailIds) {
            foreach ($deletedDetailIds as $deletedId) {
                $detail = TransactionDetail::find($deletedId);
                if ($detail) {
                    $detail->delete(); // Observer will increment stock
                }
            }

            foreach ($this->edit_items as $item) {
                if (empty($item['id'])) {
                    // Create new detail
                    TransactionDetail::create([
                        'transaction_id' => $this->edit_transaction_id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_transaction' => 0,
                    ]);
                } else {
                    $detail = TransactionDetail::find($item['id']);
                    if ($detail) {
                        // Observer `updating` will handle stock adjustment automatically
                        $detail->update([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                        ]);
                    }
                }
            }

            $transaction = Transaction::find($this->edit_transaction_id);
            if ($transaction) {
                $transaction->update([
                    'reference_code' => $this->edit_reference_code,
                    'transaction_date' => $this->edit_transaction_date,
                    'notes' => $this->edit_notes,
                ]);
            }
        });

        $this->showEditForm = false;
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang keluar berhasil diupdate.');
    }

    public function removeEditItem($index)
    {
        if (count($this->edit_items) > 1) {
            unset($this->edit_items[$index]);
            $this->edit_items = array_values($this->edit_items);
        } else {
            $this->dispatch('notify', type: 'error', message: 'Transaksi minimal harus memiliki 1 item.');
        }
    }

    public function addEditItem()
    {
        $this->edit_items[] = [
            'id' => null,
            'product_id' => '',
            'product_name' => '',
            'satuan' => '-',
            'current_stock' => 0,
            'quantity' => 1,
        ];
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1];
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

        // Validate stock availability
        foreach ($this->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->current_stock < $item['quantity']) {
                $this->dispatch('notify', type: 'error', message: "Stok {$product->name} tidak mencukupi. Tersedia: {$product->current_stock}");
                return;
            }
        }

        DB::transaction(function () {
            $formattedNotes = "[{$this->out_reason}] " . $this->notes;

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'out',
                'reference_code' => $this->reference_code,
                'transaction_date' => $this->transaction_date,
                'notes' => trim($formattedNotes),
                'total_amount' => 0, // Harga tidak relevan untuk barang keluar
            ]);

            foreach ($this->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => 0,
                ]);
            }
        });

        $this->showForm = false;
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang keluar berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        $this->delete_transaction_id = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteTransaction()
    {
        if (!Auth::user()->can('hapus-barang-keluar')) {
            abort(403, 'Akses ditolak.');
        }

        $transaction = Transaction::with('details')->findOrFail($this->delete_transaction_id);

        DB::transaction(function () use ($transaction) {
            // Delete details first — observer will reverse stock automatically
            foreach ($transaction->details as $detail) {
                $detail->delete();
            }
            $transaction->delete();
        });

        $this->showDeleteConfirm = false;
        $this->delete_transaction_id = null;
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang keluar berhasil dihapus dan stok telah dikembalikan.');
    }

    public function render()
    {
        $transactions = Transaction::with('user', 'details.product')
            ->where('type', 'out')
            ->when($this->search, fn($q) => $q->where('reference_code', 'like', '%' . $this->search . '%'))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.transaction-out', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
        ]);
    }
}

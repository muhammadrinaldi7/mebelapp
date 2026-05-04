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

    public $search = '';

    protected function rules()
    {
        $rules = [
            'reference_code' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];

        if (Auth::user()->hasRole('admin')) {
            $rules['items.*.price'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
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
            'total_amount' => $trx->total_amount,
            'details' => $trx->details->map(fn($d) => [
                'sku' => $d->product->sku ?? '-',
                'product_name' => $d->product->name ?? '-',
                'quantity' => $d->quantity,
                'satuan' => $d->product->satuan ?? '-',
                'price' => $d->price_at_transaction,
                'subtotal' => $d->quantity * $d->price_at_transaction,
            ])->toArray(),
        ];
        $this->showDetailModal = true;
    }

    public function openForm()
    {
        $this->reset(['reference_code', 'notes', 'items']);
        $this->transaction_date = now()->format('Y-m-d');
        $this->reference_code = 'IN-' . date('YmdHis');
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        if (!Auth::user()->hasRole('admin')) {
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
                'sku' => $detail->product->sku ?? '-',
                'product_name' => $detail->product->name ?? 'Produk Terhapus',
                'satuan' => $detail->product->satuan ?? '-',
                'quantity' => $detail->quantity,
                'price' => $detail->price_at_transaction,
            ];
        }
        $this->showEditForm = true;
    }

    public function updateTransaction()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Akses ditolak.');
        }

        $this->validate([
            'edit_reference_code' => 'required|string|max:255',
            'edit_transaction_date' => 'required|date',
            'edit_items.*.quantity' => 'required|integer|min:1',
            'edit_items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $totalAmount = 0;

            foreach ($this->edit_items as $item) {
                $detail = TransactionDetail::find($item['id']);
                if ($detail) {
                    // Observer `updating` will handle stock adjustment automatically
                    $detail->update([
                        'quantity' => $item['quantity'],
                        'price_at_transaction' => $item['price'],
                    ]);
                    $totalAmount += ($item['quantity'] * $item['price']);
                }
            }

            $transaction = Transaction::find($this->edit_transaction_id);
            if ($transaction) {
                $transaction->update([
                    'reference_code' => $this->edit_reference_code,
                    'transaction_date' => $this->edit_transaction_date,
                    'notes' => $this->edit_notes,
                    'total_amount' => $totalAmount,
                ]);
            }
        });

        $this->showEditForm = false;
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang masuk berhasil diupdate.');
    }

    public function confirmDelete($id)
    {
        $this->delete_transaction_id = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteTransaction()
    {
        if (!Auth::user()->can('hapus-barang-masuk')) {
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
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang masuk berhasil dihapus dan stok telah dikembalikan.');
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
            // For non-admin, force prices to 0 to be safe before saving
            if (!Auth::user()->hasRole('admin')) {
                foreach ($this->items as &$item) {
                    $item['price'] = 0;
                }
            }

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
        $this->dispatch('notify', type: 'success', message: 'Transaksi barang masuk berhasil disimpan.');
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

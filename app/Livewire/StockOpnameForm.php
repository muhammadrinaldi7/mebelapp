<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StockOpnameForm extends Component
{
    public $searchProduct = '';
    public $products = []; // Search results
    
    public $opnameItems = []; // Selected items for opname
    public $notes = '';
    
    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) >= 2) {
            $this->products = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                ->limit(10)
                ->get();
        } else {
            $this->products = [];
        }
    }

    public function addProduct($productId)
    {
        // Check if already added
        if (collect($this->opnameItems)->contains('product_id', $productId)) {
            $this->dispatch('notify', type: 'error', message: 'Produk sudah ada di daftar opname.');
            $this->searchProduct = '';
            $this->products = [];
            return;
        }

        $product = Product::find($productId);
        if ($product) {
            $this->opnameItems[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'system_stock' => $product->current_stock,
                'physical_stock' => $product->current_stock, // default to system stock
                'difference' => 0,
                'notes' => '',
            ];
        }

        $this->searchProduct = '';
        $this->products = [];
    }

    public function removeProduct($index)
    {
        unset($this->opnameItems[$index]);
        $this->opnameItems = array_values($this->opnameItems);
    }

    public function updatePhysicalStock($index, $value)
    {
        $physicalStock = (int) $value;
        $this->opnameItems[$index]['physical_stock'] = $physicalStock;
        $this->opnameItems[$index]['difference'] = $physicalStock - $this->opnameItems[$index]['system_stock'];
    }

    public function updateNotes($index, $value)
    {
        $this->opnameItems[$index]['notes'] = $value;
    }

    public function saveOpname()
    {
        if (empty($this->opnameItems)) {
            $this->dispatch('notify', type: 'error', message: 'Daftar produk tidak boleh kosong.');
            return;
        }

        DB::beginTransaction();
        try {
            // Generate Reference Code
            $count = StockOpname::whereDate('created_at', date('Y-m-d'))->count() + 1;
            $refCode = 'OP-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Create Opname
            $opname = StockOpname::create([
                'reference_code' => $refCode,
                'opname_date' => date('Y-m-d'),
                'user_id' => auth()->id(),
                'status' => 'completed',
                'notes' => $this->notes,
            ]);

            // Track adjustment transactions
            $hasIn = false;
            $hasOut = false;
            $inTransactionId = null;
            $outTransactionId = null;

            foreach ($this->opnameItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                // Create Detail
                $opname->details()->create([
                    'product_id' => $product->id,
                    'system_stock' => $item['system_stock'],
                    'physical_stock' => $item['physical_stock'],
                    'difference' => $item['difference'],
                    'notes' => $item['notes'],
                ]);

                if ($item['difference'] > 0) {
                    // Difference > 0 means IN
                    if (!$hasIn) {
                        $trxIn = Transaction::create([
                            'user_id' => auth()->id(),
                            'type' => 'in',
                            'reference_code' => 'ADJ-IN-' . $refCode,
                            'transaction_date' => date('Y-m-d'),
                            'notes' => 'Stock Opname Penyesuaian Lebih ' . $refCode,
                        ]);
                        $inTransactionId = $trxIn->id;
                        $hasIn = true;
                    }
                    
                    TransactionDetail::create([
                        'transaction_id' => $inTransactionId,
                        'product_id' => $product->id,
                        'quantity' => $item['difference'],
                        'price_at_transaction' => $product->base_price, // Or 0
                    ]);

                } elseif ($item['difference'] < 0) {
                    // Difference < 0 means OUT
                    if (!$hasOut) {
                        $trxOut = Transaction::create([
                            'user_id' => auth()->id(),
                            'type' => 'out',
                            'reference_code' => 'ADJ-OUT-' . $refCode,
                            'transaction_date' => date('Y-m-d'),
                            'notes' => 'Stock Opname Penyesuaian Kurang ' . $refCode,
                        ]);
                        $outTransactionId = $trxOut->id;
                        $hasOut = true;
                    }
                    
                    TransactionDetail::create([
                        'transaction_id' => $outTransactionId,
                        'product_id' => $product->id,
                        'quantity' => abs($item['difference']),
                        'price_at_transaction' => $product->base_price, // Or 0
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Stock Opname berhasil diselesaikan!');
            return redirect()->route('stock-opname.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stock Opname Error: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.stock-opname-form')->layout('layouts.app');
    }
}

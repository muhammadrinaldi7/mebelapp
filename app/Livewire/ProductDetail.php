<?php

namespace App\Livewire;

use Livewire\Component;

class ProductDetail extends Component
{
    public $product;

    public function mount($id)
    {
        $this->product = \App\Models\Product::with(['category', 'brand', 'transactionDetails.transaction'])->findOrFail($id);
    }

    public function render()
    {
        // dd($this->product);
        return view('livewire.product-detail');
    }
}

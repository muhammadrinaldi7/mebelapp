<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BarangKeep extends Component
{
    public function render()
    {
        $transactions = Transaction::with('user')->get();
        return view('livewire.barang-keep', compact('transactions'));
    }
}

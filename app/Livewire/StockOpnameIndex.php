<?php

namespace App\Livewire;

use App\Models\StockOpname;
use Livewire\Component;
use Livewire\WithPagination;

class StockOpnameIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isDetailModalOpen = false;
    public $selectedOpname = null;

    public function showDetail($id)
    {
        $this->selectedOpname = StockOpname::with('user', 'details.product')->findOrFail($id);
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedOpname = null;
    }

    public function render()
    {
        $opnames = StockOpname::with('user')
            ->where('reference_code', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest('opname_date')
            ->paginate(10);

        return view('livewire.stock-opname-index', [
            'opnames' => $opnames,
        ])->layout('layouts.app');
    }
}

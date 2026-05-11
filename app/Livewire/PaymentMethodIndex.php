<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PaymentMethodIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $methodId;
    public $name = '';
    public $is_active = true;
    public $confirmingDelete = false;
    public $deleteId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['methodId', 'name', 'is_active', 'editMode']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $this->methodId = $method->id;
        $this->name = $method->name;
        $this->is_active = $method->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . ($this->methodId ?? 'NULL'),
            'is_active' => 'boolean',
        ]);

        if ($this->editMode) {
            $method = PaymentMethod::findOrFail($this->methodId);
            $method->update([
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', type: 'success', message: 'Metode pembayaran berhasil diperbarui.');
        } else {
            PaymentMethod::create([
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', type: 'success', message: 'Metode pembayaran berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['methodId', 'name', 'is_active', 'editMode']);
    }

    public function toggleActive($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update(['is_active' => !$method->is_active]);
        $status = $method->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('notify', type: 'success', message: "Metode \"{$method->name}\" berhasil {$status}.");
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        $method = PaymentMethod::findOrFail($this->deleteId);

        // Check if method is used in any transaction payment
        if ($method->transactionPayments()->exists()) {
            $this->dispatch('notify', type: 'error', message: 'Metode ini tidak bisa dihapus karena sudah digunakan dalam transaksi. Nonaktifkan saja.');
            $this->confirmingDelete = false;
            $this->deleteId = null;
            return;
        }

        $method->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Metode pembayaran berhasil dihapus.');
    }

    public function render()
    {
        $methods = PaymentMethod::when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.payment-method-index', compact('methods'));
    }
}

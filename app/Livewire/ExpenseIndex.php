<?php

namespace App\Livewire;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExpenseIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $dateFrom, $dateTo;
    
    // Form fields
    public $expense_date, $category, $amount, $notes, $receipt_image;
    public $isEditing = false;
    public $expenseId;
    public $showModal = false;

    protected $rules = [
        'expense_date' => 'required|date',
        'category' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'receipt_image' => 'nullable|image|max:2048', // 2MB Max
    ];

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->expense_date = date('Y-m-d');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->expense_date = date('Y-m-d');
        $this->category = '';
        $this->amount = '';
        $this->notes = '';
        $this->receipt_image = null;
        $this->isEditing = false;
        $this->expenseId = null;
        $this->resetErrorBag();
    }

    public function save()
    {
        \Log::info('save() method was called');
        $this->validate();

        $data = [
            'expense_date' => $this->expense_date,
            'category' => $this->category,
            'amount' => $this->amount,
            'notes' => $this->notes,
            'user_id' => auth()->id(),
        ];

        \Log::info('Saving expense data:', $data);

        if ($this->receipt_image) {
            $data['receipt_image'] = $this->receipt_image->store('receipts', 'public');
        }

        if ($this->isEditing) {
            $expense = Expense::findOrFail($this->expenseId);
            
            // Delete old image if new one is uploaded
            if ($this->receipt_image && $expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }

            $expense->update($data);
            $this->dispatch('notify', type: 'success', message: 'Pengeluaran berhasil diperbarui!');
        } else {
            Expense::create($data);
            $this->dispatch('notify', type: 'success', message: 'Pengeluaran berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expenseId = $id;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->notes = $expense->notes;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }
        $expense->delete();
        $this->dispatch('notify', type: 'success', message: 'Pengeluaran berhasil dihapus!');
    }

    public function render()
    {
        $query = Expense::with('user')
            ->when($this->search, function($q) {
                $q->where('category', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('expense_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('expense_date', '<=', $this->dateTo))
            ->latest('expense_date');

        $totalAmount = (clone $query)->sum('amount');

        return view('livewire.expense-index', [
            'expenses' => $query->paginate(10),
            'totalAmount' => $totalAmount
        ])->layout('layouts.app');
    }
}

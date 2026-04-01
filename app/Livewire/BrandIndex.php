<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BrandIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $brandId;
    public $name = '';
    public $description = '';
    public $confirmingDelete = false;
    public $deleteId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['brandId', 'name', 'description', 'editMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $brand = Brand::findOrFail($this->brandId);
            $brand->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Merek berhasil diperbarui.');
        } else {
            Brand::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Merek berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['brandId', 'name', 'description', 'editMode']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Brand::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        session()->flash('message', 'Merek berhasil dihapus.');
    }

    public function render()
    {
        $brands = Brand::withCount('products')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->paginate(10);

        return view('livewire.brand-index', compact('brands'));
    }
}

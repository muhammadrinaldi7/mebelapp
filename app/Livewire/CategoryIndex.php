<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CategoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $categoryId;
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
        $this->reset(['categoryId', 'name', 'description', 'editMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Kategori berhasil diperbarui.');
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Kategori berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['categoryId', 'name', 'description', 'editMode']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Category::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        $categories = Category::withCount('products')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->paginate(10);

        return view('livewire.category-index', compact('categories'));
    }
}

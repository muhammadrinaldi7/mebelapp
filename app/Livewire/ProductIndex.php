<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $brandFilter = '';
    public $categoryFilter = '';
    public $perPage = 10;

    // CRUD properties
    public $showModal = false;
    public $editMode = false;
    public $productId;
    public $sku = '';
    public $name = '';
    public $category_id = '';
    public $brand_id = '';
    public $base_price = 0;
    public $selling_price = 0;
    public $satuan = '';

    public $confirmingDelete = false;
    public $deleteId;

    protected function rules()
    {
        return [
            'sku' => 'required|string|unique:products,sku' . ($this->editMode ? ',' . $this->productId : ''),
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'base_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingBrandFilter()
    {
        $this->resetPage();
    }
    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['productId', 'sku', 'name', 'category_id', 'brand_id', 'base_price', 'selling_price', 'editMode', 'satuan']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->base_price = $product->base_price;
        $this->selling_price = $product->selling_price;
        $this->satuan = $product->satuan;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'base_price' => $this->base_price,
            'selling_price' => $this->selling_price,
            'satuan' => $this->satuan,
        ];

        if ($this->editMode) {
            Product::findOrFail($this->productId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Produk berhasil diperbarui.');
        } else {
            Product::create($data);
            $this->dispatch('notify', type: 'success', message: 'Produk berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['productId', 'sku', 'name', 'category_id', 'brand_id', 'base_price', 'selling_price', 'editMode']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Product::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Produk berhasil dihapus.');
    }

    public function render()
    {
        $products = Product::with(['category', 'brand'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->brandFilter, fn($q) => $q->where('brand_id', $this->brandFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->paginate($this->perPage);

        return view('livewire.product-index', [
            'products' => $products,
            'brands' => Brand::all(),
            'categories' => Category::all(),
        ]);
    }
}

<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = null;
    public $sort = 'recommended';
    public $perPage = 12;
    public $loadAmount = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => null],
        'sort' => ['except' => 'recommended'],
    ];

    public function mount()
    {
        $this->category = request()->query('category', $this->category);
        $this->search = request()->query('search', $this->search);
        $this->sort = request()->query('sort', $this->sort);
    }

    public function loadMore()
    {
        $this->perPage += $this->loadAmount;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function setCategory($categoryId)
    {
        $this->category = $categoryId === $this->category ? null : $categoryId;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with('category');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('public_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('public_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest(); // Default/Recommended
                break;
        }

        $products = $query->paginate($this->perPage);
        $categories = Category::all();
        $totalProducts = $products->total(); // Get total count for logic

        return view('livewire.store.product-catalog', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts
        ]);
    }
}

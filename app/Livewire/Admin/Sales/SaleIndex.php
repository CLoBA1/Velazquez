<?php

namespace App\Livewire\Admin\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class SaleIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sourceFilter = ''; // 'web', 'pos', or '' for all
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSourceFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sales = Sale::query()
            ->with(['user', 'client'])
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->sourceFilter, function ($query) {
                $query->where('source', $this->sourceFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.sales.sale-index', [
            'sales' => $sales
        ])->layout('admin.layouts.app');
    }
}

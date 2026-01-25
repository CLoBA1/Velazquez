<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;

use Livewire\Attributes\Layout;

use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('admin.layouts.app')]
class MovementIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $date_from;
    public $date_to;

    public function render()
    {
        $movements = $this->queryMovements()->paginate(15);

        return view('livewire.admin.inventory.movement-index', [
            'movements' => $movements
        ]);
    }

    public function downloadPdf()
    {
        $movements = $this->queryMovements()->get(); // Get all records matching filter

        $pdf = Pdf::loadView('admin.inventory.pdf.movements', compact('movements'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'reporte_movimientos_' . now()->format('Y_m_d_His') . '.pdf');
    }

    private function queryMovements()
    {
        return InventoryMovement::with(['product', 'user'])
            ->latest()
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('internal_code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->date_from, fn($q) => $q->whereDate('created_at', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate('created_at', '<=', $this->date_to));
    }
}

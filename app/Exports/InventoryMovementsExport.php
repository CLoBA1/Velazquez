<?php

namespace App\Exports;

use App\Models\InventoryMovement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryMovementsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $businessLine;
    protected $startDate;
    protected $endDate;

    public function __construct($businessLine = null, $startDate = null, $endDate = null)
    {
        $this->businessLine = $businessLine;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = InventoryMovement::query()
            ->with(['product.category.family', 'user'])
            ->latest();

        if ($this->businessLine) {
            $query->whereHas('product', function ($q) {
                $q->where('business_line', $this->businessLine);
            });
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha/Hora',
            'Código Producto',
            'Producto',
            'Línea',
            'Tipo Movimiento',
            'Cantidad',
            'Stock Anterior',
            'Nuevo Stock',
            'Usuario',
            'Notas',
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->id,
            $movement->created_at->format('d/m/Y H:i'),
            $movement->product->internal_code ?? 'N/A',
            $movement->product->name ?? 'N/A',
            ucfirst($movement->product->business_line ?? 'N/A'),
            $this->mapType($movement->type),
            $movement->quantity,
            $movement->previous_stock,
            $movement->new_stock,
            $movement->user->name ?? 'Sistema',
            $movement->notes,
        ];
    }

    private function mapType($type)
    {
        return match ($type) {
            'purchase' => 'Compra/Entrada',
            'sale' => 'Venta',
            'adjustment_add' => 'Ajuste (+)',
            'adjustment_sub' => 'Ajuste (-)',
            'return' => 'Devolución',
            default => $type,
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $businessLine;

    public function __construct($businessLine = null)
    {
        $this->businessLine = $businessLine;
    }

    public function collection()
    {
        $query = Product::with(['category.family', 'category', 'brand', 'unit']);

        if ($this->businessLine) {
            $query->where('business_line', $this->businessLine);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Código Interno',
            'Código Barras',
            'Producto',
            'Familia',
            'Categoría',
            'Marca',
            'Línea de Negocio',
            'Stock',
            'Unidad',
            'Costo',
            'P. Público',
            'P. Venta',
            'P. Mayoreo',
            'P. Medio Mayoreo',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->internal_code,
            $product->barcode,
            $product->name,
            $product->category->family->name ?? 'N/A',
            $product->category->name ?? 'N/A',
            $product->brand->name ?? 'N/A',
            ucfirst($product->business_line),
            $product->stock,
            $product->unit->name ?? 'N/A',
            $product->cost_price,
            $product->public_price,
            $product->sale_price,
            $product->wholesale_price,
            $product->mid_wholesale_price,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

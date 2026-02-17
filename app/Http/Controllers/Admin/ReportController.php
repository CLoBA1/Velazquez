<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryExport;
use App\Exports\InventoryMovementsExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function inventory(Request $request)
    {
        $businessLine = $request->input('business_line');
        $format = $request->input('format', 'excel'); // Default to excel

        if ($format === 'pdf') {
            $query = \App\Models\Product::with(['category.family', 'category', 'brand', 'unit']);
            if ($businessLine) {
                $query->where('business_line', $businessLine);
            }
            $products = $query->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf.inventory', [
                'products' => $products,
                'businessLine' => $businessLine
            ]);

            return $pdf->download('inventario_global_' . date('Y-m-d') . '.pdf');
        }

        $fileName = 'inventario_global_' . date('Y-m-d_H-i') . '.xlsx';

        if ($businessLine) {
            $fileName = 'inventario_' . $businessLine . '_' . date('Y-m-d_H-i') . '.xlsx';
        }

        return Excel::download(new InventoryExport($businessLine), $fileName);
    }

    public function movements(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $businessLine = $request->input('business_line');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format', 'excel');

        if ($format === 'pdf') {
            $query = \App\Models\InventoryMovement::with(['product.category.family', 'user'])->latest();

            if ($businessLine) {
                $query->whereHas('product', function ($q) use ($businessLine) {
                    $q->where('business_line', $businessLine);
                });
            }
            if ($startDate)
                $query->whereDate('created_at', '>=', $startDate);
            if ($endDate)
                $query->whereDate('created_at', '<=', $endDate);

            $movements = $query->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf.movements', [
                'movements' => $movements,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);

            return $pdf->download('movimientos_' . date('Y-m-d') . '.pdf');
        }

        $fileName = 'movimientos_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new InventoryMovementsExport($businessLine, $startDate, $endDate), $fileName);
    }
}

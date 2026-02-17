<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sale;

class SaleController extends Controller
{
    public function downloadPdf(Sale $sale)
    {
        $sale->load(['items.product', 'client', 'user', 'payments']);

        // Determine view based on type, or just one generic view capable of both?
        // Let's use a single robust view for now.
        $pdf = Pdf::loadView('admin.sales.pdf.ticket', compact('sale'));

        // Set paper size for ticket printers (e.g., 80mm width) or A4?
        // For "Ticket" type, usually small width. For "Invoice", A4.
        if ($sale->type === 'ticket') {
            $pdf->setPaper([0, 0, 226.77, 800], 'portrait'); // ~80mm width
        } else {
            $pdf->setPaper('a4', 'portrait');
        }

        return $pdf->stream('venta_' . $sale->id . '.pdf');
    }
}

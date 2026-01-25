<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of products on sale + tool to add new ones.
     */
    public function index()
    {
        // Get products currently on sale
        $offers = Product::where('sale_price', '>', 0)
            ->orderBy('sale_deadline', 'asc')
            ->paginate(20);

        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Store a new offer or update existing one.
     * We don't 'create' offers per se, we update products to have sale_price.
     * But we can separate this logic for clarity.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sale_price' => ['required', 'numeric', 'min:0', 'lt:public_price'],
            'sale_deadline' => ['nullable', 'date', 'after:now'],
        ]);

        // Ensure sale price is less than public price is validated by 'lt:public_price' usually, 
        // but if public_price isn't in request, we might need manual check.
        // Simplified:
        if ($data['sale_price'] >= $product->public_price) {
            return back()->withErrors(['sale_price' => 'El precio de oferta debe ser menor al precio público.']);
        }

        $product->update([
            'sale_price' => $data['sale_price'],
            'sale_deadline' => $data['sale_deadline'] ?? null,
        ]);

        return back()->with('ok', 'Oferta actualizada correctamente.');
    }

    /**
     * Remove an offer (set sale_price to 0 or null).
     */
    public function destroy(Product $product)
    {
        $product->update([
            'sale_price' => null, // or 0 depending on DB default. Migration default is usually null or 0.
            'sale_deadline' => null,
        ]);

        return back()->with('ok', 'Oferta eliminada.');
    }
}

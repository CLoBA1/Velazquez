<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Get the user's order history.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = \App\Models\Sale::with(['items.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Create a new order (Checkout).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        // 1. Calculate totals and prepare items
        $total = 0;
        $saleItems = [];

        foreach ($request->items as $itemStr) {
            $product = \App\Models\Product::find($itemStr['product_id']);
            $quantity = $itemStr['quantity'];
            $price = $product->public_price; // Or your specific pricing logic
            $lineTotal = $price * $quantity;

            $total += $lineTotal;

            $saleItems[] = new \App\Models\SaleItem([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $lineTotal,
            ]);

            // Optional: Reduce stock
            // $product->decrement('stock', $quantity);
        }

        // 2. Create the Sale record
        $sale = \App\Models\Sale::create([
            'user_id' => $user->id,
            'client_id' => null, // Assuming App users aren't explicitly linked to wholesale Clients here
            'type' => 'App Sales',
            'status' => 'Pendiente', // or processing
            'payment_method' => 'Efectivo', // Adjust as needed
            'total' => $total,
            'source' => 'Android App',
            'shipping_address' => $request->address . ' | Tel: ' . $request->phone . ' | Notas: ' . $request->notes,
        ]);

        // 3. Attach Items
        $sale->items()->saveMany($saleItems);

        // Transform slightly to return OrderDto format if needed
        return response()->json([
            'success' => true,
            'data' => $sale->load('items.product')
        ]);
    }
}

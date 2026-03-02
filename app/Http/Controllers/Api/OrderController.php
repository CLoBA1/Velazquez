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

        try {
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

                $saleItem = new \App\Models\SaleItem();
                $saleItem->product_id = $product->id;
                $saleItem->quantity = $quantity;
                $saleItem->price = $price;
                $saleItem->total = $lineTotal;

                $saleItems[] = $saleItem;
            }

            // 2. Create the Sale record
            $sale = \App\Models\Sale::create([
                'user_id' => $user->id,
                'client_id' => 1, // Default General Public ID to prevent SQL null constraint failure
                'type' => 'ticket', // Must match ENUM: 'ticket' or 'invoice'
                'status' => 'pending', // Must match ENUM: 'paid', 'pending', 'cancelled'
                'payment_method' => 'Efectivo', // Adjust as needed
                'total' => $total,
                'source' => 'Android App',
                'shipping_address' => $request->address . ' | Tel: ' . $request->phone . ' | Notas: ' . $request->notes,
            ]);

            // 3. Attach Items
            $sale->items()->saveMany($saleItems);

            return response()->json([
                'success' => true,
                'data' => $sale->load('items.product')
            ]);

        } catch (\Exception $e) {
            file_put_contents(public_path('debug.txt'), "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            throw $e; // Re-throw to keep the 500 response but now log is captured publicly
        }
    }
}

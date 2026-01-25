<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        if ($cart->count() == 0) {
            return redirect()->route('store.cart');
        }

        return view('store.checkout', [
            'cartItems' => $cart->getContent(),
            'total' => $cart->total()
        ]);
    }

    public function store(Request $request, CartService $cart)
    {
        if ($cart->count() == 0) {
            return redirect()->route('store.cart');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        try {
            DB::beginTransaction();

            // Create Sale
            $sale = Sale::create([
                'user_id' => auth()->id(), // Nullable now
                'client_id' => null, // We could try to match by email later or create a temporary client
                'type' => 'ticket',
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'total' => $cart->total(),
                'source' => 'web',
                'shipping_address' => $validated['address'] . "\nReference: " . ($validated['notes'] ?? ''),
            ]);

            // Create Items
            foreach ($cart->getContent() as $item) {
                // Here we should verify stock again ideally
                $sale->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            // Clear Cart
            $cart->clear();

            DB::commit();

            return redirect()->route('store.checkout.success', ['sale' => $sale->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar tu pedido. Por favor intenta de nuevo. ' . $e->getMessage());
        }
    }

    public function success(Sale $sale)
    {
        if ($sale->source !== 'web') {
            abort(404);
        }
        return view('store.success', compact('sale'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of the user's purchases.
     */
    public function myPurchases()
    {
        $sales = Sale::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.orders.index', compact('sales'));
    }

    /**
     * Display the specified purchase.
     */
    public function showPurchase(Sale $sale)
    {
        // Ensure the order belongs to the authenticated user
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }

        $sale->load(['items.product', 'items.product.brand']);

        return view('profile.orders.show', compact('sale'));
    }
}

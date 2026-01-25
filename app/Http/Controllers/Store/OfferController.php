<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        // Fetch all active offers
        $offers = Product::where('sale_price', '>', 0)
            ->whereRaw('sale_price < public_price') // Ensure it's a real discount
            ->orderBy('sale_deadline', 'asc') // Ending soonest first?
            ->paginate(12);

        return view('store.offers.index', compact('offers'));
    }
}

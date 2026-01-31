<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConstructionStoreController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::construction()
            ->orderBy('name')
            ->paginate(24);

        return view('construction.index', compact('products'));
    }
}

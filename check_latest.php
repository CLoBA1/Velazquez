<?php
use App\Models\Product;

$products = Product::latest()->take(3)->get();
foreach ($products as $p) {
    echo "ID: " . $p->id . "\n";
    echo "Name: " . $p->name . "\n";
    echo "Business Line: " . $p->business_line . "\n";
    echo "Category: " . ($p->category->name ?? 'None') . "\n";
    echo "Brand: " . ($p->brand->name ?? 'None') . "\n";
    echo "-------------------\n";
}

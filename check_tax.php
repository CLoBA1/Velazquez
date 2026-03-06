<?php

$families = App\Models\Family::with([
    'categories' => function ($q) {
        $q->withCount('products');
    }
])->withCount('categories')->get();

foreach ($families as $f) {
    echo strtoupper($f->name) . " (Cats: " . $f->categories_count . ")\n";
    foreach ($f->categories as $c) {
        echo "  - " . $c->name . " [" . $c->id . "] (" . $c->products_count . " products)\n";
    }
}

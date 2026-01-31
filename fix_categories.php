<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

$category = Category::where('name', 'Materiales de Construcción')->first();
$brand = Brand::where('name', 'Genérico')->first();

if (!$category || !$brand) {
    echo "Error: Default Category or Brand not found. Run db:seed first.\n";
    exit(1);
}

$count = Product::construction()->update([
    'category_id' => $category->id,
    'brand_id' => $brand->id
]);

echo "Updated $count construction products to CORRECT defaults.\n";

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;

// ── Public ────────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ── Protected (Bearer token via Sanctum) ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', function (Request $request) {
        $request->validate(['name' => 'required', 'username' => 'nullable|string']);
        $request->user()->update($request->only(['name', 'username']));
        return response()->json(['success' => true, 'data' => $request->user()->fresh()]);
    });

    // Products (with search & category/brand filter)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Offers — products with active sale_deadline
    Route::get('/offers', function (Request $request) {
        $products = Product::with(['brand', 'category', 'unit'])
            ->whereNotNull('sale_deadline')
            ->where('sale_deadline', '>', now())
            ->orderBy('name')
            ->paginate(20);
        return response()->json(['success' => true, 'data' => $products]);
    });

    // Categories
    Route::get('/categories', function () {
        $cats = Category::orderBy('name')->get(['id', 'name']);
        return response()->json(['success' => true, 'data' => $cats]);
    });

    // Brands
    Route::get('/brands', function () {
        $brands = Brand::orderBy('name')->get(['id', 'name', 'logo_path']);
        return response()->json(['success' => true, 'data' => $brands]);
    });

    // Orders (customer)
    Route::apiResource('orders', \App\Http\Controllers\Api\OrderController::class)
        ->only(['index', 'store']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show'])
        ->missing(fn() => response()->json(['success' => false, 'message' => 'Not found'], 404));

    // ── Admin routes (role:admin) ─────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Products CRUD
        Route::get('/products', function (Request $request) {
            $query = Product::with(['brand', 'category', 'unit'])->orderBy('name');
            if ($request->filled('search')) {
                $term = $request->search;
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('barcode', 'like', "%{$term}%")
                        ->orWhere('internal_code', 'like', "%{$term}%");
                });
            }
            return response()->json(['success' => true, 'data' => $query->paginate(20)]);
        });

        Route::post('/products', function (Request $request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'public_price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'barcode' => 'nullable|string',
                'sku' => 'nullable|string',
            ]);
            $product = Product::create($data);
            return response()->json(['success' => true, 'data' => $product], 201);
        });

        Route::put('/products/{id}', function (Request $request, $id) {
            $product = Product::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'public_price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);
            $product->update($data);
            return response()->json(['success' => true, 'data' => $product->fresh()]);
        });

        Route::delete('/products/{id}', function ($id) {
            Product::findOrFail($id)->delete();
            return response()->json(['success' => true, 'data' => null]);
        });

        // Orders — list all + change status
        Route::get('/orders', function (Request $request) {
            $orders = \App\Models\Sale::with(['items.product'])
                ->latest()
                ->paginate(20);
            return response()->json(['success' => true, 'data' => $orders]);
        });

        Route::put('/orders/{id}/status', function (Request $request, $id) {
            $request->validate(['status' => 'required|string']);
            $order = \App\Models\Sale::findOrFail($id);
            $order->update(['status' => $request->status]);
            return response()->json(['success' => true, 'data' => $order->fresh()]);
        });

        // Users list (read-only)
        Route::get('/users', function (Request $request) {
            $users = User::orderBy('name')->paginate(30);
            return response()->json(['success' => true, 'data' => $users]);
        });
    });
});

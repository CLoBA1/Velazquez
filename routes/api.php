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

    // Categories (optional ?family_id= filter)
    Route::get('/categories', function (Request $request) {
        $query = Category::orderBy('name');
        if ($request->filled('family_id')) {
            $query->where('family_id', $request->family_id);
        }
        $cats = $query->get(['id', 'name', 'family_id']);
        return response()->json(['success' => true, 'data' => $cats]);
    });

    // Families
    Route::get('/families', function () {
        $families = \App\Models\Family::withCount('products')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'code']);
        return response()->json(['success' => true, 'data' => $families]);
    });

    // Product Units / Presentations
    Route::get('/products/{id}/units', function ($id) {
        $units = \App\Models\ProductUnit::with('unit:id,name,symbol')
            ->where('product_id', $id)
            ->get();
        return response()->json(['success' => true, 'data' => $units]);
    });


    // ── Offline POS Sync (Web <-> Desktop) ───────────────────────────────────
    Route::post('/pos/sync-sales', [\App\Http\Controllers\Api\SyncController::class, 'syncSales']);
    Route::get('/pos/sync-catalog', [\App\Http\Controllers\Api\SyncController::class, 'getCatalog']);

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

        // ── Dashboard KPIs ─────────────────────────────────────────────────────
        Route::get('/dashboard', function () {
            $totalProducts = Product::count();
            $lowStock      = Product::whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0)->count();
            $inventoryValue = Product::sum(\Illuminate\Support\Facades\DB::raw('stock * cost_price'));
            $movementsToday = \App\Models\InventoryMovement::whereDate('created_at', today())->count();
            $salesToday     = \App\Models\Sale::whereDate('created_at', today())->sum('total');
            $salesWeek      = \App\Models\Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
            $pendingOrders  = \App\Models\Sale::where('status', 'pending')->count();
            // Recent products added this week
            $recentProducts = Product::with(['category:id,name', 'brand:id,name'])
                ->latest()->take(5)->get(['id','name','stock','public_price','category_id','brand_id']);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_products'  => $totalProducts,
                    'low_stock'       => $lowStock,
                    'inventory_value' => (float) $inventoryValue,
                    'movements_today' => $movementsToday,
                    'sales_today'     => (float) $salesToday,
                    'sales_week'      => (float) $salesWeek,
                    'pending_orders'  => $pendingOrders,
                    'recent_products' => $recentProducts,
                ],
            ]);
        });

        // ── Inventory Movements ────────────────────────────────────────────────
        Route::get('/inventory/movements', function (Request $request) {
            $query = \App\Models\InventoryMovement::with(['product:id,name', 'user:id,name'])->latest();
            if ($request->filled('product_id')) $query->where('product_id', $request->product_id);
            if ($request->filled('type')) $query->where('type', $request->type);
            return response()->json(['success' => true, 'data' => $query->paginate(30)]);
        });

        Route::post('/inventory/movement', function (Request $request) {
            $data = $request->validate([
                'product_id' => 'required|exists:products,id',
                'type'       => 'required|in:purchase,adjustment_add,adjustment_sub',
                'quantity'   => 'required|numeric|min:0.01',
                'notes'      => 'nullable|string|max:500',
            ]);
            $product = Product::findOrFail($data['product_id']);
            $previous = $product->stock;
            $new = in_array($data['type'], ['purchase', 'adjustment_add'])
                ? $previous + $data['quantity']
                : max(0, $previous - $data['quantity']);
            $product->update(['stock' => $new]);
            $movement = \App\Models\InventoryMovement::create([
                'product_id'     => $data['product_id'],
                'user_id'        => $request->user()->id,
                'type'           => $data['type'],
                'quantity'       => $data['quantity'],
                'previous_stock' => $previous,
                'new_stock'      => $new,
                'notes'          => $data['notes'] ?? null,
            ]);
            return response()->json(['success' => true, 'data' => $movement->load('product:id,name')], 201);
        });

        // ── Categories CRUD ────────────────────────────────────────────────────
        Route::get('/categories', function () {
            return response()->json(['success' => true, 'data' => Category::withCount('products')->orderBy('name')->get()]);
        });
        Route::post('/categories', function (Request $request) {
            $data = $request->validate(['name' => 'required|string|max:255']);
            return response()->json(['success' => true, 'data' => Category::create($data)], 201);
        });
        Route::put('/categories/{id}', function (Request $request, $id) {
            $cat = Category::findOrFail($id);
            $cat->update($request->validate(['name' => 'required|string|max:255']));
            return response()->json(['success' => true, 'data' => $cat->fresh()]);
        });
        Route::delete('/categories/{id}', function ($id) {
            Category::findOrFail($id)->delete();
            return response()->json(['success' => true, 'data' => null]);
        });

        // ── Brands CRUD ────────────────────────────────────────────────────────
        Route::get('/brands', function () {
            return response()->json(['success' => true, 'data' => Brand::withCount('products')->orderBy('name')->get()]);
        });
        Route::post('/brands', function (Request $request) {
            $data = $request->validate(['name' => 'required|string|max:255']);
            return response()->json(['success' => true, 'data' => Brand::create($data)], 201);
        });
        Route::put('/brands/{id}', function (Request $request, $id) {
            $brand = Brand::findOrFail($id);
            $brand->update($request->validate(['name' => 'required|string|max:255']));
            return response()->json(['success' => true, 'data' => $brand->fresh()]);
        });
        Route::delete('/brands/{id}', function ($id) {
            Brand::findOrFail($id)->delete();
            return response()->json(['success' => true, 'data' => null]);
        });

        // ── Reports Summary ────────────────────────────────────────────────────
        Route::get('/reports/summary', function () {
            // Top 10 critical stock
            $criticalStock = Product::with(['category:id,name'])
                ->whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0)
                ->orderBy('stock')->take(10)->get(['id','name','stock','min_stock','public_price','category_id']);
            // Sales last 7 days
            $last7 = collect(range(6, 0))->map(function ($daysAgo) {
                $date = now()->subDays($daysAgo)->format('Y-m-d');
                return [
                    'date'  => now()->subDays($daysAgo)->format('d/m'),
                    'total' => (float) \App\Models\Sale::whereDate('created_at', $date)->sum('total'),
                    'count' => \App\Models\Sale::whereDate('created_at', $date)->count(),
                ];
            });
            // Top 5 sold products (by sale items quantity)
            $topProducts = \App\Models\SaleItem::selectRaw('product_id, SUM(quantity) as sold')
                ->with('product:id,name,public_price')
                ->groupBy('product_id')->orderByDesc('sold')->take(5)->get();

            return response()->json(['success' => true, 'data' => [
                'critical_stock' => $criticalStock,
                'sales_last7'    => $last7,
                'top_products'   => $topProducts,
            ]]);
        });
    });
});

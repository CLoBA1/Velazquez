<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Models\Category;
use App\Models\Brand;

// ── Public ────────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ── Protected (Bearer token via Sanctum) ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Products (with search & filter)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

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

    // Orders
    Route::apiResource('orders', \App\Http\Controllers\Api\OrderController::class)
        ->only(['index', 'store']);
});


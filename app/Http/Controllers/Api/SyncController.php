<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Devuelve el catálogo + Clientes para el POS de escritorio.
     */
    public function getCatalog()
    {
        $products = Product::with('units')->get([
            'id', 'category_id', 'brand_id', 'unit_id', 'barcode', 'name', 
            'sale_price', 'public_price', 'mid_wholesale_price', 'wholesale_price', 
            'stock', 'min_stock'
        ]);

        $categories = \App\Models\Category::all(['id', 'name']);
        $brands = \App\Models\Brand::all(['id', 'name']);
        $units = \App\Models\Unit::all(['id', 'name', 'symbol']);

        // Clientes reales del sistema (modelo Client, no User)
        $clients = Client::select(['id','name','email','phone','rfc','address',
                                   'credit_limit','credit_used'])
                         ->orderBy('name')
                         ->get();
        
        return response()->json([
            'success' => true, 
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'units' => $units,
            'clients' => $clients
        ]);
    }

    /**
     * Recibe un paquete (JSON) con los tickets creados fuera de línea por el POS local.
     */
    public function syncSales(Request $request)
    {
        $sales = $request->input('sales'); 
        
        if (!$sales) return response()->json(['success' => false, 'message' => 'No se enviaron ventas en el payload'], 400);

        DB::beginTransaction();
        try {
            $syncedIds = [];
            foreach ($sales as $saleData) {
                $sale = Sale::create([
                    'total'      => $saleData['total'],
                    'status'     => 'completed',
                    'created_at' => $saleData['created_at'] ?? now(),
                    'user_id'    => 1 
                ]);

                foreach ($saleData['items'] as $item) {
                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['qty'],
                        'price'      => $item['price']
                    ]);

                    $product = Product::find($item['product_id']);
                    if ($product) {
                        // Descontar la cantidad real (ya viene calculada desde el POS)
                        $product->decrement('stock', $item['qty']);
                    }
                }
                $syncedIds[] = $saleData['local_id'];
            }
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Sincronización de tickets fuera de línea exitosa.',
                'synced_local_ids' => $syncedIds
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

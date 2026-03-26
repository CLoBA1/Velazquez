<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Devuelve el catálogo de la nube para que el POS de escritorio lo descargue localmente.
     */
    public function getCatalog()
    {
        $products = Product::all(['id', 'barcode', 'name', 'public_price as price', 'stock']);
        return response()->json(['success' => true, 'data' => $products]);
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
                // Registrar ticket maestro
                $sale = Sale::create([
                    'total' => $saleData['total'],
                    'status' => 'completed',
                    'created_at' => $saleData['created_at'] ?? now(),
                    // Asignamos a un usuario administrador por defecto o terminal virtual
                    'user_id' => 1 
                ]);

                // Registrar productos vendidos
                foreach ($saleData['items'] as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['qty'],
                        'price' => $item['price']
                    ]);

                    // Extra: Descontar ese stock físicamente de la nube para evitar sobreventas online
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
                // Guardar el ID local (SQLite) para responderle al Python que ya está procesado
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

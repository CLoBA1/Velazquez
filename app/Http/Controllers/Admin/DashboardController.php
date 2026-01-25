<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Existing Data
        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name', 'unit:id,name'])
            ->latest()
            ->take(6) // Adjusted to 6 for layout balance if needed
            ->get();

        // 2. KPIs
        $totalProducts = Product::count();

        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('min_stock', '>', 0) // Only count if min_stock is set
            ->count();

        // Approximate inventory value (cost * stock)
        $inventoryValue = Product::sum(DB::raw('stock * cost_price'));

        $movementsToday = InventoryMovement::whereDate('created_at', Carbon::today())->count();

        // 3. Chart Data (Last 7 Days Movements)
        // Group by Date and Type
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $movementsData = InventoryMovement::selectRaw('DATE(created_at) as date, type, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        // Format for Chart (e.g. { date: '2023-01-01', in: 5, out: 2 })
        // Simplified: Just Total Movements per day for now, or Split by In/Out
        // Let's do Total Movements per day
        $chartLabels = [];
        $chartValues = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $chartLabels[] = $startDate->copy()->addDays($i)->format('d/m');
            $count = $movementsData->where('date', $date)->sum('count');
            $chartValues[] = $count;
        }

        $kpis = [
            'total_products' => $totalProducts,
            'low_stock' => $lowStockCount,
            'inventory_value' => $inventoryValue,
            'movements_today' => $movementsToday,
        ];

        return view('admin.dashboard', compact(
            'products',
            'kpis',
            'chartLabels',
            'chartValues'
        ));
    }
}

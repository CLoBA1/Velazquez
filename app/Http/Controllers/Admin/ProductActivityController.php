<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProductActivityController extends Controller
{
    /**
     * Display a listing of the product activity log.
     */
    public function index(Request $request)
    {
        $q = Activity::query()
            ->whereIn('subject_type', ['App\Models\Product', 'App\Models\ProductUnit'])
            ->with([
                'causer',
                'subject' => function ($morphTo) {
                    $morphTo->morphWith([
                        \App\Models\ProductUnit::class => ['product', 'unit']
                    ]);
                }
            ]);

        // Filtro por Usuario Responsable
        if ($userId = $request->input('user_id')) {
            $q->where('causer_id', $userId);
        }

        // Filtro por Fechas
        if ($dateStart = $request->input('date_start')) {
            $q->whereDate('created_at', '>=', $dateStart);
        }
        if ($dateEnd = $request->input('date_end')) {
            $q->whereDate('created_at', '<=', $dateEnd);
        }

        // Búsqueda Textual (por Nombre del Producto)
        if ($search = $request->input('search')) {
            $q->whereHasMorph('subject', ['App\Models\Product', 'App\Models\ProductUnit'], function ($query, $type) use ($search) {
                if ($type === 'App\Models\Product') {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('internal_code', 'like', '%' . $search . '%');
                } elseif ($type === 'App\Models\ProductUnit') {
                    $query->whereHas('product', function ($qq) use ($search) {
                        $qq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('internal_code', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        $activities = $q->orderByDesc('created_at')->paginate(30)->withQueryString();

        // Obtener lista de usuarios para el filtro (dropdown)
        $users = User::orderBy('name')->get();

        return view('admin.reports.activity-log', compact('activities', 'users'));
    }
}

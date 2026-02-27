<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProductActivityController extends Controller
{
    /**
     * Display a listing of the product activity log.
     */
    public function index(Request $request)
    {
        $activities = Activity::whereIn('subject_type', ['App\Models\Product', 'App\Models\ProductUnit'])
            ->with(['causer', 'subject'])
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.reports.activity-log', compact('activities'));
    }
}

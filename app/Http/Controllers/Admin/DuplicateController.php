<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DuplicateController extends Controller
{
    /**
     * Display the duplicate finder tool.
     */
    public function index(Request $request)
    {
        $criteria = $request->input('criteria', 'name'); // Default to name
        $duplicates = [];

        if ($request->has('criteria')) {
            if ($criteria === 'name') {
                // Find names that appear more than once
                $duplicateNames = Product::select('name', DB::raw('count(*) as total'))
                    ->groupBy('name')
                    ->having('total', '>', 1) // Only strictly > 1, so 2 or more
                    ->pluck('name');

                // Fetch the actual product records for those names
                if ($duplicateNames->isNotEmpty()) {
                    $duplicates = Product::whereIn('name', $duplicateNames)
                        ->orderBy('name')
                        ->get()
                        ->groupBy('name');
                }

            } elseif ($criteria === 'code') {
                // Find codes that appear more than once
                // Note: unique validation usually prevents this, but useful for legacy data cleanup
                $duplicateCodes = Product::select('internal_code', DB::raw('count(*) as total'))
                    ->whereNotNull('internal_code')
                    ->where('internal_code', '!=', '')
                    ->groupBy('internal_code')
                    ->having('total', '>', 1)
                    ->pluck('internal_code');

                if ($duplicateCodes->isNotEmpty()) {
                    $duplicates = Product::whereIn('internal_code', $duplicateCodes)
                        ->orderBy('internal_code')
                        ->get()
                        ->groupBy('internal_code');
                }
            }
        }

        return view('admin.products.duplicates', compact('duplicates', 'criteria'));
    }
}

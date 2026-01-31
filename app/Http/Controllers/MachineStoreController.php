<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MachineStoreController extends Controller
{
    public function index()
    {
        $machines = \App\Models\Machine::query()
            ->where('status', '!=', 'maintenance')
            ->orderBy('name')
            ->paginate(12);

        return view('machinery.index', compact('machines'));
    }

    public function show(\App\Models\Machine $machine)
    {
        if ($machine->status === 'maintenance') {
            abort(404);
        }

        return view('machinery.show', compact('machine'));
    }
}

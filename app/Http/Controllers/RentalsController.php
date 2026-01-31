<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Machine;
use Illuminate\Support\Facades\Auth;

class RentalsController extends Controller
{
    /**
     * Display a listing of the user's rentals.
     */
    public function index()
    {
        $rentals = Rental::where('user_id', Auth::id())
            ->with(['machine'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.rentals.index', compact('rentals'));
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental)
    {
        // Ensure the rental belongs to the authenticated user
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        $rental->load(['machine']);

        return view('profile.rentals.show', compact('rental'));
    }

    /**
     * Store a new rental request.
     * This will likely be called from a Livewire component or form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $machine = Machine::findOrFail($validated['machine_id']);

        // Calculate days logic (simplified for controller, ideally shared service)
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start->diffInDays($end) + 1; // Inclusive

        $totalCost = $days * $machine->price_per_day;

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'machine_id' => $machine->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_cost' => $totalCost,
            'status' => 'pending', // Default status
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('rentals.show', $rental)->with('success', 'Solicitud de renta creada correctamente.');
    }
}

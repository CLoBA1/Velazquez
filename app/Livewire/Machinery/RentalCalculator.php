<?php

namespace App\Livewire\Machinery;

use Livewire\Component;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RentalCalculator extends Component
{
    public $machine;
    public $startDate;
    public $endDate;
    public $days = 0;
    public $total = 0;

    public function mount(Machine $machine)
    {
        $this->machine = $machine;
    }

    public function updatedStartDate()
    {
        $this->calculateTotal();
    }

    public function updatedEndDate()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);

            if ($end->gte($start)) {
                $this->days = $start->diffInDays($end) + 1;
                $this->total = $this->days * $this->machine->price_per_day;
            } else {
                $this->days = 0;
                $this->total = 0;
            }
        }
    }

    public function render()
    {
        return view('livewire.machinery.rental-calculator');
    }
}

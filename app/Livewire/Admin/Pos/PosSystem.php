<?php

namespace App\Livewire\Admin\Pos;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Client;

#[Layout('admin.layouts.app')]
class PosSystem extends Component
{
    public $search = '';
    public $cart = [];
    public $clients = [];
    public $selected_client_id = null;

    // Client Creation
    public $showCreateClientModal = false;
    public $newClient = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'rfc' => '',
        'address' => '',
        'credit_limit' => 0,
    ];

    // Calculated Totals
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;

    protected $rules = [
        'newClient.name' => 'required|min:3',
        'newClient.email' => 'nullable|email',
        'newClient.phone' => 'nullable',
        'newClient.rfc' => 'nullable',
        'newClient.address' => 'nullable',
        'newClient.credit_limit' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->refreshClients();
    }

    public function refreshClients()
    {
        $this->clients = Client::orderBy('name')->get();
    }

    public function updatedSearch()
    {
        // Search happens automatically in render
    }

    public function scanCode($inputCode = null)
    {
        // Use provided code or fall back to component state
        $code = $inputCode ?? $this->search;

        if (empty($code))
            return;

        // Try to find exact match by code first, then name
        $product = Product::with(['unit', 'units.unit'])
            ->where('internal_code', $code)
            ->orWhere('barcode', $code) // Assuming you have a barcode field, if not, use internal_code
            ->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = ''; // Clear search after successful scan
            $this->dispatch('search-cleared'); // Optional: for JS focus
        }
    }

    public $showUnitModal = false;
    public $pendingProduct = null;
    public $pendingUnits = [];

    public function addToCart($productId)
    {
        $product = Product::with(['unit', 'units.unit'])->find($productId);

        if (!$product)
            return;

        $isWeight = $product->unit && strtolower($product->unit->name) === 'kilo';
        
        if ($isWeight && count($product->units) > 0) {
            $this->pendingProduct = $product;
            $this->pendingUnits = collect($product->units)->map(function($u) {
                return [
                    'name' => $u->unit->name,
                    'price' => $u->public_price,
                    'conversion_factor' => $u->conversion_factor,
                ];
            })->toArray();
            
            // Add Base Unit (Kilo)
            $this->pendingUnits[] = [
                'name' => 'Kilo',
                'price' => $product->public_price,
                'conversion_factor' => 1,
            ];

            // Sort by conversion factor (Kilo -> Bulto -> Tonelada)
            usort($this->pendingUnits, fn($a, $b) => $a['conversion_factor'] <=> $b['conversion_factor']);

            $this->showUnitModal = true;
            return;
        }

        $this->finalizeAddToCart($product, 'base', $product->public_price, 1, $product->unit?->name ?? 'Pza');
    }

    public function addPendingUnitToCart($presentationIndex)
    {
        if (!$this->pendingProduct) return;
        
        $selectedUnit = $this->pendingUnits[$presentationIndex];
        $presentationId = strtolower($selectedUnit['name']); // e.g. 'kilo', 'bulto', 'tonelada'

        // If it's kilo natively, map it cleanly back to 'base'
        if ($presentationId === 'kilo' && strtolower($this->pendingProduct->unit->name ?? '') === 'kilo') {
            $presentationId = 'base';
        }

        $this->finalizeAddToCart(
            $this->pendingProduct, 
            $presentationId, 
            $selectedUnit['price'], 
            $selectedUnit['conversion_factor'], 
            $selectedUnit['name']
        );

        $this->showUnitModal = false;
        $this->pendingProduct = null;
        $this->search = ''; // clear search explicitly after selection
    }

    public function cancelUnitSelection()
    {
        $this->showUnitModal = false;
        $this->pendingProduct = null;
    }

    private function finalizeAddToCart($product, $presentationId, $price, $multiplier, $presentationName)
    {
        $cartKey = $product->id . '_' . $presentationId;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
        } else {
            $displayName = $product->name;
            if ($presentationId !== 'base' && $presentationId !== 'kilo') {
                $displayName .= " (x1 {$presentationName})";
            }

            $this->cart[$cartKey] = [
                'id' => $product->id,
                'key' => $cartKey,
                'name' => $displayName,
                'price' => $price,
                'quantity' => 1,
                'sku' => $product->internal_code,
                'stock' => $product->stock,
                'image' => $product->image_url,
                'presentation_id' => $presentationId,
                'multiplier' => $multiplier,
                'presentation_name' => $presentationName,
                'original_stock_display' => $product->stock,
            ];
        }

        $this->calculateTotals();
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        $this->calculateTotals();
    }

    public function updateQuantity($cartKey, $qty)
    {
        if ($qty > 0) {
            $this->cart[$cartKey]['quantity'] = $qty;
        } else {
            unset($this->cart[$cartKey]);
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;

        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }

        // Assuming prices are VAT inclusive for public display
        $this->total = $this->subtotal;
    }

    // Payment Handling
    public $showPaymentModal = false;
    public $payments = []; // Array of ['method', 'amount']
    public $paymentMethod = 'cash'; // Current selection
    public $amountPaid = 0; // Current input
    public $remaining = 0;
    public $change = 0;


    public function openPaymentModal()
    {
        if (empty($this->cart))
            return;

        $this->showPaymentModal = true;
        $this->payments = [];
        $this->paymentMethod = 'cash';
        $this->calculateTotals();
        $this->remaining = $this->total;
        $this->amountPaid = $this->total; // Default suggestion
        $this->change = 0;
    }

    public function updatedAmountPaid()
    {
        // Just for live update if needed, validation happens on add
    }

    public function addPayment()
    {
        $amount = floatval($this->amountPaid);

        if ($amount <= 0) {
            $this->addError('payment', 'El monto debe ser mayor a 0.');
            return;
        }

        if ($amount > $this->remaining && $this->paymentMethod !== 'cash') {
            $this->addError('payment', 'El monto excede el restante.');
            return;
        }

        // Credit Validation
        if ($this->paymentMethod === 'credit') {
            if (!$this->selected_client_id) {
                $this->addError('payment', 'Seleccione un cliente para crédito.');
                return;
            }
            $client = Client::find($this->selected_client_id);
            if ($client->available_credit < $amount) {
                $this->addError('payment', 'Crédito insuficiente.');
                return;
            }
        }

        $this->payments[] = [
            'method' => $this->paymentMethod,
            'amount' => $amount
        ];

        $this->calculateRemaining();
        $this->reset('amountPaid'); // Clear input

        // Auto-suggest remaining for next payment if any
        if ($this->remaining > 0) {
            $this->amountPaid = $this->remaining;
        } else {
            $this->amountPaid = 0;
        }
    }

    public function removePayment($index)
    {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments); // Re-index
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $totalPaid = collect($this->payments)->sum('amount');
        $this->remaining = max(0, $this->total - $totalPaid);

        // Calculate change (only relevant if total paid > total and cash involved, 
        // but for split payments "change" is usually just the excess of the last cash payment.
        // Simplified: Change is Total Paid - Total Sale (if positive)
        if ($totalPaid > $this->total) {
            $this->change = $totalPaid - $this->total;
        } else {
            $this->change = 0;
        }
    }

    public function openCreateClientModal()
    {
        $this->reset('newClient');
        $this->showCreateClientModal = true;
    }

    public function closeCreateClientModal()
    {
        $this->showCreateClientModal = false;
    }

    public function saveClient()
    {
        $this->validate();

        try {
            $client = Client::create([
                'name' => $this->newClient['name'],
                'email' => $this->newClient['email'],
                'phone' => $this->newClient['phone'],
                'rfc' => $this->newClient['rfc'],
                'address' => $this->newClient['address'],
                'credit_limit' => $this->newClient['credit_limit'] ?? 0,
                'credit_used' => 0,
            ]);

            $this->refreshClients();
            $this->selected_client_id = $client->id;
            $this->showCreateClientModal = false;

        } catch (\Exception $e) {
            $this->addError('newClient', 'Error al crear cliente: ' . $e->getMessage());
        }
    }

    public function finalizeSale()
    {
        if (empty($this->cart))
            return;

        // If no payments added but user clicks finalize (e.g. fast cash), try to add current input
        if (empty($this->payments) && $this->amountPaid > 0) {
            $this->addPayment();
        }

        $totalPaid = collect($this->payments)->sum('amount');
        if ($totalPaid < $this->total - 0.01) { // Float tolerance
            $this->addError('payment', 'Falta cubrir el total. Restante: $' . number_format($this->remaining, 2));
            return;
        }

        try {
            $saleId = \DB::transaction(function () {
                // Determine Main Payment Method (for compatibility)
                $mainMethod = count($this->payments) > 1 ? 'split' : $this->payments[0]['method'];

                $status = 'paid';

                // 1. Create Sale
                $sale = \App\Models\Sale::create([
                    'user_id' => auth()->id(),
                    'client_id' => $this->selected_client_id,
                    'type' => 'ticket',
                    'status' => $status,
                    'payment_method' => $mainMethod,
                    'total' => $this->total,
                ]);

                // 2. Create Items & Adjust Stock
                foreach ($this->cart as $item) {
                    \App\Models\SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['price'] * $item['quantity'],
                    ]);

                    $product = Product::find($item['id']);
                    if ($product) {
                        $deductionQuantity = $item['quantity'] * ($item['multiplier'] ?? 1);
                        $product->adjustStock(
                            $deductionQuantity,
                            'sale',
                            "Venta #{$sale->id}"
                        );
                    }
                }

                // 3. Process Payments
                foreach ($this->payments as $payment) {
                    // Create Payment Record
                    \App\Models\SalePayment::create([
                        'sale_id' => $sale->id,
                        'method' => $payment['method'],
                        'amount' => $payment['amount'],
                    ]);

                    // Handle Credit Deduction
                    if ($payment['method'] === 'credit') {
                        $client = Client::find($this->selected_client_id);
                        $client->increment('credit_used', $payment['amount']);
                    }
                }

                return $sale->id;
            });

            // 4. Reset & Redirect
            $this->cart = [];
            $this->calculateTotals();
            $this->selected_client_id = null;
            $this->showPaymentModal = false;

            return redirect()->route('admin.sales.print', $saleId);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar venta: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 1) { // Search after 2 chars
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('internal_code', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->take(12)
                ->get();
        }

        return view('livewire.admin.pos.pos-system', [
            'products' => $products
        ]);
    }
}

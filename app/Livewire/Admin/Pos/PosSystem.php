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
        $product = Product::where('internal_code', $code)
            ->orWhere('barcode', $code) // Assuming you have a barcode field, if not, use internal_code
            ->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = ''; // Clear search after successful scan
            $this->dispatch('search-cleared'); // Optional: for JS focus
        }
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product)
            return;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->public_price, // Default to public price
                'quantity' => 1,
                'sku' => $product->internal_code,
                'stock' => $product->stock,
                'image' => $product->image_url
            ];
        }

        $this->calculateTotals();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotals();
    }

    public function updateQuantity($productId, $qty)
    {
        if ($qty > 0) {
            $this->cart[$productId]['quantity'] = $qty;
        } else {
            unset($this->cart[$productId]);
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
    public $paymentMethod = 'cash'; // cash, card, transfer, credit
    public $amountPaid = 0;
    public $change = 0;

    public function openPaymentModal()
    {
        if (empty($this->cart))
            return;

        $this->showPaymentModal = true;
        $this->amountPaid = $this->total; // Default to exact amount
        $this->calculateChange();
    }

    public function updatedAmountPaid()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $paid = floatval($this->amountPaid);
        if ($paid >= $this->total) {
            $this->change = $paid - $this->total;
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

        // Validation for Credit
        if ($this->paymentMethod === 'credit') {
            if (!$this->selected_client_id) {
                $this->addError('payment', 'Debe seleccionar un cliente para venta a crédito.');
                return;
            }

            $client = Client::find($this->selected_client_id);
            if ($client->available_credit < $this->total) {
                $this->addError('payment', 'El cliente no tiene crédito suficiente. Disponible: $' . number_format($client->available_credit, 2));
                return;
            }
        }

        try {
            $saleId = \DB::transaction(function () {
                $status = ($this->paymentMethod === 'credit') ? 'pending' : 'paid';

                // 1. Create Sale
                $sale = \App\Models\Sale::create([
                    'user_id' => auth()->id(),
                    'client_id' => $this->selected_client_id,
                    'type' => 'ticket',
                    'status' => $status,
                    'payment_method' => $this->paymentMethod,
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
                        $product->adjustStock(
                            $item['quantity'],
                            'sale',
                            "Venta #{$sale->id}"
                        );
                    }
                }

                // 3. Update Credit Used
                if ($this->paymentMethod === 'credit') {
                    $client = Client::find($this->selected_client_id);
                    $client->increment('credit_used', $this->total);
                }

                return $sale->id;
            });

            // 4. Reset & Redirect
            $this->cart = [];
            $this->calculateTotals();
            $this->selected_client_id = null;
            $this->showPaymentModal = false;

            return redirect()->route('admin.sales.pdf', $saleId);

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

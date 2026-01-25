<?php

namespace App\Livewire\Admin\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class ClientIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Edit/Create Modal
    public $showModal = false;
    public $form = [
        'id' => null,
        'name' => '',
        'email' => '',
        'phone' => '',
        'rfc' => '',
        'address' => '',
        'credit_limit' => 0,
    ];

    // Payment Modal
    public $showPaymentModal = false;
    public $paymentForm = [
        'client_id' => null,
        'client_name' => '',
        'amount' => '',
        'reference' => '',
        'notes' => '',
    ];

    // History Modal
    public $showHistoryModal = false;
    public $historyClient = null;
    public $historyTransactions = [];

    public function render()
    {
        $clients = Client::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('rfc', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.client.client-index', [
            'clients' => $clients
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';
        $this->sortField = $field;
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->showModal = true;

        if ($id) {
            $client = Client::find($id);
            $this->form = [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'rfc' => $client->rfc,
                'address' => $client->address,
                'credit_limit' => $client->credit_limit,
            ];
        } else {
            $this->form = [
                'id' => null,
                'name' => '',
                'email' => '',
                'phone' => '',
                'rfc' => '',
                'address' => '',
                'credit_limit' => 0,
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|min:3',
            'form.email' => 'nullable|email',
            'form.phone' => 'nullable',
            'form.rfc' => 'nullable',
            'form.address' => 'nullable',
            'form.credit_limit' => 'required|numeric|min:0',
        ]);

        if ($this->form['id']) {
            $client = Client::find($this->form['id']);
            $client->update($this->form);
        } else {
            Client::create($this->form);
        }

        $this->showModal = false;
        // session()->flash('message', 'Cliente guardado correctamente.');
    }

    public function delete($id)
    {
        Client::find($id)->delete();
    }

    // Payment Methods
    public function openPaymentModal($id, $name)
    {
        $this->resetValidation();
        $this->showPaymentModal = true;
        $this->paymentForm = [
            'client_id' => $id,
            'client_name' => $name,
            'amount' => '',
            'reference' => '',
            'notes' => '',
        ];
    }

    public function savePayment()
    {
        $this->validate([
            'paymentForm.amount' => 'required|numeric|min:0.01',
            'paymentForm.reference' => 'nullable|string',
            'paymentForm.notes' => 'nullable|string',
        ]);

        $client = Client::findOrFail($this->paymentForm['client_id']);

        // Create Payment Record
        $client->payments()->create([
            'amount' => $this->paymentForm['amount'],
            'reference' => $this->paymentForm['reference'],
            'notes' => $this->paymentForm['notes'],
            'user_id' => auth()->id(),
        ]);

        // Update Credit Used (Reduce debt)
        $client->credit_used = max(0, $client->credit_used - $this->paymentForm['amount']);
        $client->save();

        $this->showPaymentModal = false;
    }

    // History Methods
    public function openHistoryModal($id)
    {
        $this->historyClient = Client::with([
            'payments' => function ($q) {
                $q->latest();
            }
        ])->find($id);

        $this->showHistoryModal = true;
    }
}

<?php

namespace App\Livewire\Customer\Billing;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Auth;

class PaymentManagement extends Component
{
    use WithPagination;

    #[On('refresh-invoice-list')]
    public function render()
    {
        $invoices = Auth::user()->invoices()
        ->orderBy('periode', 'DESC')
       ->paginate(5);

       $activePaymentGateway = PaymentGateway::whereIsActive(true)->get();
        return view('livewire.customer.billing.payment-management', compact('invoices', 'activePaymentGateway'));
    }
}

<?php

namespace App\Livewire\Customer\Billing\Modal;

use App\Models\Billings\Order;
use Livewire\Component;
use Livewire\Attributes\On;

class ViewDetailOrderPaid extends Component
{

    public $viewOrderPaidModal = false;
    public $order;

    #[On('show-detail-order-paid-modal')]
    public function showDetailOrderPaidModal(Order $order)
    {
        $this->viewOrderPaidModal = true;
        $this->order = $order;
    }
    public function render()
    {
        return view('livewire.customer.billing.modal.view-detail-order-paid');
    }
}

<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Traits\NotificationTrait;
use App\Models\Customers\CustomerPaket;
use App\Services\Billings\BillingService;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditActivation extends Component
{
    use NotificationTrait;
    public $editActivationCustomerPaketModal = false;
    public $customerPaket;
    public $input = [];

    #[On('edit-activation-customer-paket-modal')]
    public function showEditActivationCustomerPaketModal(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->editActivationCustomerPaketModal = true;
        $this->customerPaket = $customerPaket;
        $this->input['activation_date'] = Carbon::parse($this->customerPaket->activation_date)->format('Y-m-d');
    }

    public function edit_activation_paket(CustomerPaketService $customerPaketService)
    {
        $customerPaketService->editActivationDate($this->customerPaket, $this->input['activation_date']);

        $this->dispatch('refresh-customer-paket-list');
        $this->editActivationCustomerPaketModal = false;
        $this->success_notification('Success', trans('customer.paket.alert.edit-activation-paket-succesfully'));
    }
    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.edit-activation');
    }
}

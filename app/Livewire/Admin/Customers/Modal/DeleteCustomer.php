<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use App\Traits\NotificationTrait;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerPaketService;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\CustomerService;
use App\Services\Mikrotiks\MikrotikPppService;

class DeleteCustomer extends Component
{
    use NotificationTrait;

    public $deleteCustomerModal = false;
    public $user;
    public $input = [];
    //private CustomerPaketService $customerPaketService;
    //public function boot(CustomerPaketService $customerPaketService)
    //{
    //    $this->customerPaketService = $customerPaketService;
    //}

    #[On('delete-customer-modal')]
    public function showDeleteCustomerModal(User $user)
    {
        $this->deleteCustomerModal = true;
        $this->user = $user;
        $this->input['deleteOnMikrotik'] = false;
    }

    public function deleteCustomer(
        CurrentPasswordRequest $currentPasswordRequest,
        CustomerService $customerService
    ) {
        $this->resetErrorBag();
        $userName = $this->user->full_name;
        $currentPasswordRequest->validate($this->input);
        $deleteCustomer = $customerService->deleteCustomer($this->user, $this->input['deleteOnMikrotik']);
        if ($deleteCustomer['success']) {
            $message = trans('customer.alert.customer-delete-detail', ['customer' => $userName]);
            $this->notification(trans('customer.alert.success'), $message, 'success');
        } else {
            $this->notification(trans('customer.alert.failed'), $deleteCustomer['message'], 'error');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->deleteCustomerModal = false;
        $this->dispatch('refresh-customer-list');
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.delete-customer');
    }
}

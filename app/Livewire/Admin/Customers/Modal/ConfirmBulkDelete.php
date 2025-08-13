<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CustomerService;
use App\Traits\NotificationTrait;
use App\Services\CustomerPaketService;
use App\Http\Requests\CurrentPasswordRequest;

class ConfirmBulkDelete extends Component
{
    use NotificationTrait;
    public $bulkDeleteCustomerModal = false;
    public $users = [];
    public $input = [];

   // private CustomerPaketService $customerPaketService;
   // public function boot(CustomerPaketService $customerPaketService)
    //{
     //   $this->customerPaketService = $customerPaketService;
   // }

    #[On('bulk-delete-customer-modal')]
    public function showBulkDeleteCustomerModal($userSelected)
    {
        $this->bulkDeleteCustomerModal = true;
        $users = User::query()
            ->whereIn('id', $userSelected)
            ->get();
        $this->users = $users;
        $userSelected = [];
    }

    public function bulkDeleteCustomer(CurrentPasswordRequest $currentPasswordRequest, CustomerPaketService $customerPaketService, CustomerService $customerService)
    {
        $this->resetErrorBag();
        $currentPasswordRequest->validate($this->input);

        $successDelete = 0;
        $failedDelete = 0;
        foreach ($this->users as $user) {
            try {
                $customerService->deleteCustomer($user, $this->input['deleteOnMikrotik']);
                $successDelete++;
            } catch (\Exception $e) {
                $failedDelete++;
            }
        }

        $this->notification(trans('customer.alert.delete'), trans('customer.alert.customer-bulk-delete-detail', ['countSuccess' => $successDelete, 'countFailed' => $failedDelete]), 'success');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->bulkDeleteCustomerModal = false;
        $this->dispatch('refresh-customer-list');
        $this->dispatch('refresh-selected-users');
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.confirm-bulk-delete');
    }
}

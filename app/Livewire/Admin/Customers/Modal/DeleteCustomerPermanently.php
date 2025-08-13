<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use App\Traits\NotificationTrait;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteCustomerPermanently extends Component
{
    use NotificationTrait;

    public $deleteCustomerPermanentlyModal = false;
    public $user;
    public $input = [];


    #[On('delete-customer-permanently-modal')]
    public function showDeleteCustomerPermanentlyModal($userId)
    {
        $this->user = User::withTrashed()->findOrFail($userId);
        $this->deleteCustomerPermanentlyModal = true;
    }

    public function deleteCustomerPermanently(CurrentPasswordRequest $request, GeneralLogServices $generalLogServices, CustomerPaketService $customerPaketService)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        DB::beginTransaction();
        try {
            $customerName = $this->user->full_name;
           $this->user->forceDelete();
            //Add log
            $generalLogServices->admin_action($generalLogServices::DELETE_CUSTOMER, "Delete permanently " . $customerName, Auth::user()->full_name);
            DB::commit();
            $this->success_notification(trans('customer.alert.success'), trans('customer.alert.customer-delete-detail', ['customer' => $customerName]));
        } catch (\Exception $e) {
             DB::rollBack();
             $this->error_notification(trans('customer.alert.failed'), $e->getMessage());
        }
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->deleteCustomerPermanentlyModal = false;
        $this->dispatch('refresh-deleted-customer-list');
    }


    public function render()
    {
        return view('livewire.admin.customers.modal.delete-customer-permanently');
    }
}

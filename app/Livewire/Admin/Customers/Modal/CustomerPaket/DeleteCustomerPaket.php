<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use App\Traits\NotificationTrait;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Auth;
use App\Services\CustomerPaketService;
use App\Models\Customers\CustomerPaket;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteCustomerPaket extends Component
{
    use NotificationTrait;
    public $deleteCustomerPaketModal = false;
    public $customerPaket;
    public $input = [];

    private $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }


    #[On('delete-customer-paket-modal')]
    public function showDeleteCustomerModal(CustomerPaket $customerPaket)
    {
        $this->deleteCustomerPaketModal = true;
        $this->input['deleteOnMikrotik'] = false;
        $this->customerPaket = $customerPaket;
    }

    public function deleteCustomerPaket(CurrentPasswordRequest $request, GeneralLogServices $generalLogServices)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        DB::beginTransaction();
        try {
            $paketName = $this->customerPaket->paket->name;
            $fullName = $this->customerPaket->user->full_name;
            $this->customerPaketService->deleteCustomerPaket($this->customerPaket, $this->input['deleteOnMikrotik']);
            $generalLogServices->admin_action($generalLogServices::DELETE_CUSTOMER_PAKET, "Delete " . $fullName, Auth::user()->full_name);

            DB::commit();

            $title = trans('customer.paket.alert.delete-paket-success');
            $message = trans('customer.paket.alert.customer-paket-delete-detail', ['customer' => $fullName, 'paket' => $paketName]);
            $this->notification($title, $message, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $title = trans('customer.paket.alert.delete-paket-failed');
             $this->notification($title, $e->getMessage(), 'error');
        }
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->deleteCustomerPaketModal = false;
        $this->dispatch('refresh-customer-paket-list');
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.delete-customer-paket');
    }
}

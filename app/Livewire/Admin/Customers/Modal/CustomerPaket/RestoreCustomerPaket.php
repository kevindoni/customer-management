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
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class RestoreCustomerPaket extends Component
{
    use NotificationTrait;
    public $restoreCustomerPaketModal = false;
    public $customerPaket;
    public $input = [];

    #[On('restore-customer-paket-modal')]
    public function showRestoreCustomerPaketModal($customerPaketId)
    {
        $this->reset();
        $this->input['restoreOnMikrotik'] = false;
        $this->customerPaket = CustomerPaket::withTrashed()->findOrFail($customerPaketId);

        $this->restoreCustomerPaketModal = true;
    }

    public function restoredCustomerPaket(CustomerPaketService $customerPaketService, GeneralLogServices $generalLogServices)
    {
        DB::beginTransaction();
        try {
            // $customerPaketService->disableCustomerPaketOnMikrotik($this->customerPaket, 'false');
           /// $this->customerPaket->restore();
           // $generalLogServices->admin_action($generalLogServices::RESTORE_CUSTOMER_PAKET, "Restore customer paket " .$this->customerPaket->paket->name.'-'.$this->customerPaket->paket->mikrotik->name.' '. $this->customerPaket->user->full_name, Auth::user()->full_name);
            $customerPaketService->restore_deleted_customer_paket($this->customerPaket, $this->input['restoreOnMikrotik']);
           DB::commit();

           $this->success_notification('Restore Success', 'Restore customer paket successfully');
            $this->dispatch('refresh-deleted-customer-paket-list');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error_notification('Restore Failed!', $e->getMessage());
        }
        $this->restoreCustomerPaketModal = false;
    }


    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.restore-customer-paket');
    }
}

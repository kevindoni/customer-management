<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Auth;
use App\Models\Customers\CustomerPaket;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteCustomerPaketPermanently extends Component
{

    public $deletePermanentlyCustomerPaketModal = false;
    public $customerPaket;
    public $input = [];



    #[On('delete-permanently-customer-paket-modal')]
    public function showDeleteCustomerModal($customerPaketId)
    {
        $this->customerPaket = CustomerPaket::withTrashed()->findOrFail($customerPaketId);
        $this->deletePermanentlyCustomerPaketModal = true;
    }

    public function deletePermanentlyCustomerPaket(CurrentPasswordRequest $request, GeneralLogServices $generalLogServices, CustomerPaketService $customerPaketService)
    {
        $this->resetErrorBag();
        $request->validate($this->input);

        DB::beginTransaction();
        try {
            $paketName = $this->customerPaket->paket->name;
            $paketServer = $this->customerPaket->paket->mikrotik->name;
            $customerName = $this->customerPaket->user->full_name;
            $message = "Delete permanently paket " . $paketName . '-' . $paketServer . ' on ' . $customerName;



            $this->customerPaket->forceDelete();
            $generalLogServices->admin_action($generalLogServices::DELETE_CUSTOMER_PAKET, $message, Auth::user()->full_name);
            DB::commit();
            $this->notification(trans('customer.alert.success'), $message, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notification(trans('customer.alert.failed'), $e->getMessage(), 'error');
        }
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->deletePermanentlyCustomerPaketModal = false;
        $this->dispatch('refresh-deleted-customer-paket-list');
    }

    public function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.delete-customer-paket-permanently');
    }
}

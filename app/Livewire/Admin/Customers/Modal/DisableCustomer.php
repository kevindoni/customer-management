<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerPaketService;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DisableCustomer extends Component
{
    public $disableCustomerModal = false;
    public $user;
    public $input = [];
    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }


    #[On('disable-customer-modal')]
    public function showDisableCustomerModal(User $user)
    {
        $this->disableCustomerModal = true;
        $this->user = $user;
    }

    /**
     * Disable Customer after validate user
     */
    public function disableCustomer(CurrentPasswordRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
      // dd($this->user->disabled);
        if ($this->user->disabled) {
            $this->user->forceFill([
                'disabled' => false
            ])->save();
             $this->notification(trans('customer.alert.enable-successfully'),  trans('customer.alert.customer-enable-detail', ['customer' => $this->user->full_name]), 'success');
             $this->closeModal();
        } else {

            $disabled['success'] = true;
            foreach ($this->user->customer_pakets as $customer_paket) {
                //dd($customer_paket);
                DB::beginTransaction();
                $this->customerPaketService->updateStatusCustomerPaket($customer_paket, 'suspended');
                DB::commit();
            }

            if ($disabled['success']) {
                $this->user->forceFill([
                    'disabled' => true
                ])->save();
                $this->notification(trans('customer.alert.disable-successfully'), trans('customer.alert.customer-disable-detail', ['customer' => $this->user->full_name]), 'warning');
                $this->closeModal();
            } else {
                $this->notification('Failed!', $disabled['message'], 'error');
            }

        }

    }


    public function closeModal()
    {
        $this->disableCustomerModal = false;
        $this->dispatch('refresh-customer-list');
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
        return view('livewire.admin.customers.modal.disable-customer');
    }
}

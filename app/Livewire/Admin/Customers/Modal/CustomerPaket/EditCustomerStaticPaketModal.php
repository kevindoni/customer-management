<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;


use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CustomerPaketService;
use App\Services\Mikrotiks\MikrotikService;
use App\Models\Customers\CustomerStaticPaket;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Customers\EditIpStaticPaketRequest;


class EditCustomerStaticPaketModal extends Component
{

    public $editCustomerStaticPaketModal = false;
    public $input = [];
    public $customerStaticPaket;
    public $mikrotik_interfaces;
    // public Mikrotik $mikrotik;
    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }

    #[On('edit-customer-static-paket-modal')]
    public function showIpStaticPaketModal(CustomerStaticPaket $customerStaticPaket)
    {

        $this->editCustomerStaticPaketModal = true;
        $this->input = array_merge([
            'ip_address' => $customerStaticPaket->ip_address,
            'selectedMikrotikInterface' => $customerStaticPaket->interface
        ],  $customerStaticPaket->withoutRelations()->toArray());

        // $this->mikrotik = $this->customerPaket->paket->ppp_profile->mikrotik;
        $this->customerStaticPaket = $customerStaticPaket;
        try {
            $this->mikrotik_interfaces = (new MikrotikService())->getInterfaces($customerStaticPaket->customer_paket->paket->mikrotik);
        } catch (\Exception $e) {
            $this->input['selectedMikrotikInterface'] = $customerStaticPaket->interface;
            $this->dispatch('notify', [
                'status' => 'error',
                'title' => "Error!",
                'message' =>  $e->getMessage()
            ]);
            $this->mikrotik_interfaces = null;
        }
    }

    public function editIpStaticPaket(EditIpStaticPaketRequest $request)
    {
        $this->resetErrorBag();
        $request->validate(
            $this->input,
            $this->customerStaticPaket,
            $this->mikrotik_interfaces
        );


        $res = $this->customerPaketService->update_ip_static(
            $this->customerStaticPaket,
            $this->input
        );

        if ($res['success']) {
            $title = trans('customer.alert.edit-customer-paket', ['customer' =>  $this->customerStaticPaket->customer_paket->user->full_name]);
            $message = trans('customer.alert.edit-customer-paket-successfully', ['customer' =>  $this->customerStaticPaket->customer_paket->user->full_name, 'paket' =>   $this->customerStaticPaket->customer_paket->paket->name]);
            $this->notification($title, $message, 'success');
            $this->closeModal();
        } else {

            $title =trans('customer.alert.edit-customer-paket', ['customer' =>  $this->customerStaticPaket->customer_paket->user->full_name]);
            $message = $res['message'];
            $this->notification($title, $message, 'error');
        }
    }

    public function closeModal()
    {
        $this->dispatch('refresh-customer-list');
        $this->editCustomerStaticPaketModal = false;
        $this->reset();
    }

    public function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.edit-customer-static-paket-modal');
    }
}

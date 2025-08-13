<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;


use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Customers\CustomerPppPaket;
use App\Services\CustomerPaketService;
use App\Http\Requests\Customers\EditPppPaketRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditCustomerPppPaketModal extends Component
{

    public $editCustomerPppPaketModal = false;
    public $input = [];
    public $customerPppPaket;

    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }


    #[On('edit-customer-ppp-paket-modal')]
    public function showPppPaketModal(CustomerPppPaket $customerPppPaket)
    {
        //  dd($ipStaticPaket);
        $this->resetErrorBag();
        $this->editCustomerPppPaketModal = true;
        $this->input = array_merge([
            'username' => $customerPppPaket->username,

        ],  $customerPppPaket->withoutRelations()->toArray());
        $this->input['selectedPppService'] = $customerPppPaket->ppp_type_id;
        $this->customerPppPaket = $customerPppPaket;
    }

    public function editPppPaket(EditPppPaketRequest $request)
    {
        $this->resetErrorBag();
        $request->validate(
            $this->input,
            $this->customerPppPaket
        );

        //add customer paket

        $res = $this->customerPaketService->update_ppp_paket(
            $this->customerPppPaket,
            $this->input
        );
        if ($res['success']) {
            $this->notification(trans('customer.alert.edit-customer-paket', ['customer' =>  $this->customerPppPaket->customer_paket->user->full_name]),trans('customer.alert.edit-customer-paket-successfully', ['customer' => $this->customerPppPaket->customer_paket->user->full_name, 'paket' =>   $this->customerPppPaket->customer_paket->paket->name]),'success');
            $this->closeModal();
        } else {
            $this->notification(trans('customer.alert.edit-customer-paket', ['customer' =>  $this->customerPppPaket->customer_paket->user->full_name]),$res['message'],'error');
        }
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
    public function closeModal()
    {
        $this->dispatch('refresh-customer-list');
        $this->editCustomerPppPaketModal = false;
        $this->reset();
    }



    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.edit-customer-ppp-paket-modal');
    }
}

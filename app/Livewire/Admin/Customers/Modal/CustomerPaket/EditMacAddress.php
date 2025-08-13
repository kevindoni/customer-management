<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Customers\CustomerPaket;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditMacAddress extends Component
{
    public $editMacAddressModal = false;
    public $customerPaket;
    public $input = [];

    #[On('edit-mac-address-modal')]
    public function showEditMAcAddressModal(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->editMacAddressModal = true;
        $this->customerPaket = $customerPaket;
    }

    public function edit_mac_address()
    {
        Validator::make($this->input, [
            'mac_address' => ['required', 'mac_address'],
        ])->validate();
        $this->customerPaket->customer_static_paket->forceFill([
            'mac_address' => $this->input['mac_address']
        ])->save();
        $this->notification('Success', 'Update MAC address successfully.', 'success');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->editMacAddressModal = false;
        $this->dispatch('refresh-customer-list');
        $this->reset();
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
        return view('livewire.admin.customers.modal.customer-paket.edit-mac-address');
    }
}

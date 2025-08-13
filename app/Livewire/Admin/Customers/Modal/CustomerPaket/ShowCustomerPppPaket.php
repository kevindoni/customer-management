<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Customers\CustomerPaket;
use App\Models\Customers\CustomerPppPaket;

class ShowCustomerPppPaket extends Component
{
    public $showCustomerPppPaketModal = false;
    //public $customerPppPaket;
    public $usernamePpp;
    public $passwordPpp;

    #[On('show-customer-ppp-paket-modal')]
    public function showCustomerPppPaketModal(CustomerPppPaket $customerPppPaket)
    {
        $this->resetErrorBag();
        $this->showCustomerPppPaketModal = true;
        //$this->customerPppPaket = $customerPppPaket;
        $this->usernamePpp = $customerPppPaket->username;
        $this->passwordPpp = $customerPppPaket->password_ppp;
    }
    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.show-customer-ppp-paket');
    }
}

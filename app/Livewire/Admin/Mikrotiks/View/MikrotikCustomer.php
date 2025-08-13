<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use Livewire\Component;
use App\Models\Servers\Mikrotik;
use App\Support\CollectionPagination;

class MikrotikCustomer extends Component
{
    public Mikrotik $mikrotik;
    public  $perPage = 25;

    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function render()
    {

        $customerPakets = $this->mikrotik->customer_pakets;
        $customerPakets = (new CollectionPagination($customerPakets))->collectionPaginate($this->perPage);
        return view('livewire.admin.mikrotiks.view.mikrotik-customer', compact('customerPakets'));
    }
}

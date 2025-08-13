<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class ShowCustomer extends Component
{
    public $user;
   public $input = [];
   public string $alert1;
    public function mount(User $user)
    {
        $this->user = $user;

    }

    #[On('refresh-customer-list')]
    #[On('refresh-customer-paket-list')]
    public function render()
    {
        return view('livewire.admin.customers.show-customer');
    }
}

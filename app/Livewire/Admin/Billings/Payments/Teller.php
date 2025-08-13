<?php

namespace App\Livewire\Admin\Billings\Payments;

use Livewire\Component;
use App\Models\User;

class Teller extends Component
{
    public function render()
    {
        $users = User::join('user_teller_payments', 'users.id', 'user_teller_payments.user_id')
        ->get();
        
        dd($users);
        return view('livewire.admin.billings.payments.teller');
    }
}

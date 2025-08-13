<?php

namespace App\Livewire\Admin\Settings\Banks;

use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class BankManagement extends Component
{
    use WithPagination;

    #[On('refresh-bank-list')]
    public function render()
    {
        $banks = Bank::paginate(10);
        return view('livewire.admin.settings.banks.bank-management',[
            'banks' => $banks
        ]);
    }
}

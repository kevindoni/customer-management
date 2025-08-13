<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\NotificationTrait;
use App\Services\CustomerPaketService;
use App\Models\Customers\CustomerPaket;
use App\Http\Requests\CurrentPasswordRequest;

class ResetNextBill extends Component
{

    use NotificationTrait;

    public $showResetNextBillModal = false;
    public $input = [];


    #[On('show-reset-next-bill-modal')]
    public function show_reset_next_bill_modal()
    {
        $this->showResetNextBillModal = true;
    }

    public function reset_next_bill(CurrentPasswordRequest $currentPasswordRequest, CustomerPaketService $customerPaketService)
    {
         $currentPasswordRequest->validate($this->input);
         $customerPakets = CustomerPaket::all();
         foreach($customerPakets as $customerPaket){
            $customerPaketService->syncronize_next_billed_at($customerPaket);
         }

         $this->closeModal();

    }

    public function closeModal()
    {
        $this->showResetNextBillModal = false;
        $this->success_notification('Success', 'Reset next bill successfully.');
    }
    public function render()
    {
        return view('livewire.admin.billings.modal.reset-next-bill');
    }
}

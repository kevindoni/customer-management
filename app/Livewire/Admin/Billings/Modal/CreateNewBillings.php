<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Jobs\ManualProcessBillingJob;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreateNewBillings extends Component
{
    public $input = [];
    public $createNewBillingsModal = false;

    #[On('create-new-billings-modal')]
    public function showCreateNewBillingsModal()
    {
        $this->createNewBillingsModal = true;
        if ($this->createNewBillingsModal) {
            dispatch(new ManualProcessBillingJob())->onQueue('default');
            $message =  trans('billing.alert.create-billing-succesfully');
            $this->notification(trans('billing.alert.success'), $message, 'success');
            $this->dispatch('refresh-billing-paket');
        }
        $this->createNewBillingsModal = false;
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
        return view('livewire.admin.billings.modal.create-new-billings');
    }
}

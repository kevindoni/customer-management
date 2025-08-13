<?php

namespace App\Livewire\Admin\Customers\Component;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Models\Customers\CustomerPaket;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ChangePaket extends Component
{
    public CustomerPaket $customerPaket;

    public string $field;
    public string $alert1;
    public $paketSelect;
    public $input = [];

    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }
    public function mount()
    {
        $this->input['changePaket'] = $this->customerPaket->paket_id;
    }

    public function updatedInputChangePaket($value)
    {
        $this->paketSelect = Paket::whereId($value)->first();
        // dd( $this->paketSelect->id );
        Flux::modal('confirm-change-paket-' . $this->customerPaket->id)->show();
        $this->alert1 = trans('customer.alert.are-you-sure-change-paket', ['paket' => $this->customerPaket->paket->name, 'new_paket' => $this->paketSelect->name]);
        //  $this->customerPaket->forceFill([
        //      'paket_id' => $value
        //   ])->save();
    }

    #[On('update-customer-paket')]
    public function updatedPaket(CustomerPaket $customerPaket)
    {
        if ($this->customerPaket->id == $customerPaket->id) {
            $changePaket = $this->customerPaketService->update_customer_paket($customerPaket, $this->paketSelect);
            Flux::modal('confirm-change-paket-' . $this->customerPaket->id)->close();
            if ($changePaket['success']) {
                $title = trans('customer.alert.updating-success');
                $message =trans('customer.alert.message-update-customer-paket-success', ['paket' => $customerPaket->paket->name]);
                $this->notification($title, $message, 'success');
            } else {
                $title = trans('customer.alert.header-update-failed');
                $message =$changePaket['message'];
                $this->notification($title, $message, 'error');
            }
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

    public function render()
    {
        return view('livewire.admin.customers.component.change-paket');
    }
}

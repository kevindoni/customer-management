<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Customers\CustomerPaket;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DisableCustomerPaket extends Component
{
    public $disableCustomerPaketModal = false;
    public $customerPaket;

    public $input = [];
    public $button;

    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }

    #[On('disable-customer-paket-modal')]
    public function showAddCustomerPaketModal(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->disableCustomerPaketModal = true;
        $this->customerPaket = $customerPaket;
        $this->input['status'] = $this->button = $customerPaket->status;
    }

    public function disable_paket()
    {
        DB::beginTransaction();
        $disabled = $this->customerPaketService->updateStatusCustomerPaket($this->customerPaket, $this->input['status']);
        DB::commit();

        if ($disabled['success']) {
            $title =  trans('customer.paket.alert.'.$disabled['status']);
            $message = trans('customer.paket.alert.detail-customer-paket-'.$disabled['status'], ['customer' => $this->customerPaket->user->full_name, 'paket' => $this->customerPaket->paket->name]);
            $this->notification($title, $message, 'success');
            $this->closeModal();
        } else {
            $this->notification('Failed!', $disabled['message'], 'error');
        }

    }

    public function closeModal()
    {
        $this->disableCustomerPaketModal = false;
        $this->dispatch('refresh-customer-list');
        $this->reset();
    }

    public function updatedInputStatus($value)
    {
        $this->button = $value;
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
        return view('livewire.admin.customers.modal.customer-paket.disable-customer-paket');
    }
}

<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\CustomerPaketService;
use App\Models\Customers\CustomerPaket;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ActivationPaket extends Component
{
    public $activationCustomerPaketModal = false;
    public $customerPaket;

    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }

    #[On('activation-customer-paket-modal')]
    public function showActivationCustomerPaketModal(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->activationCustomerPaketModal = true;
        $this->customerPaket = $customerPaket;
    }

    public function activation_paket()
    {
        DB::beginTransaction();
        try {
            $activation = $this->customerPaketService->activationCustomerPaket($this->customerPaket);

            if ($activation['success']) {
                DB::commit();
                $this->notification('Success', $activation['message'], 'success');
                $this->closeModal();
            } else {
                DB::rollBack();
                $this->notification('Failed!', $activation['message'], 'error');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notification('Failed!', $e->getMessage(), 'error');
        }

    }

    public function closeModal()
    {
        $this->dispatch('refresh-customer-list');
        $this->activationCustomerPaketModal = false;
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
        return view('livewire.admin.customers.modal.customer-paket.activation-paket');
    }
}

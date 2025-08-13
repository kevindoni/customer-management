<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Customers\CustomerPaket;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DisableWaNotificationInstallationAddress extends Component
{

    public $disableWaInstallationAddressModal = false;
    public $customerPaket;

    private CustomerPaketService $customerPaketService;
    public function boot(CustomerPaketService $customerPaketService)
    {
        $this->customerPaketService = $customerPaketService;
    }

    #[On('disable-wa-notification-installation-address-modal')]
    public function showDisableWaNotification(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->disableWaInstallationAddressModal = true;
        $this->customerPaket = $customerPaket;
    }

    public function disable_wa_notification_installation_address()
    {
        DB::beginTransaction();
        $disabled = $this->customerPaketService->disableWaNotificationInstallationAddress($this->customerPaket);
        DB::commit();

        if ($disabled['success']) {
            $this->notification('Success', $disabled['message'], 'success');
            $this->closeModal();
        } else {
            $this->notification('Failed!', $disabled['message'], 'error');
        }

    }

    public function closeModal()
    {
        $this->disableWaInstallationAddressModal = true;
        $this->dispatch('refresh-customer-list');
        $this->reset();
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
        return view('livewire.admin.customers.modal.customer-paket.disable-wa-notification-installation-address');
    }
}

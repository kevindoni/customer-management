<?php

namespace App\Livewire\Admin\Customers\Modal;

use Livewire\Component;
use App\Models\UserAddress;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DisableWaNotification extends Component
{
    public $disableWaNotificationModal = false;
    public $input = [];
    public $userAddress;

    #[On('disable-wa-notification-modal')]
    public function showDisableWaNotificationModal(UserAddress $userAddress)
    {
        //  dd($ipStaticPaket);
        $this->resetErrorBag();
        $this->disableWaNotificationModal = true;
        $this->input = array_merge([
            'checkbox_wa_notification' => $userAddress->wa_notification ? true : false,
        ],  $userAddress->withoutRelations()->toArray());
        $this->userAddress = $userAddress;
    }

    public function disableWaNotification()
    {
        $this->userAddress->forceFill([
            'wa_notification' => $this->input['checkbox_wa_notification'] ?? false
        ])->save();
        $this->dispatch('refresh-customer-list');
        LivewireAlert::title(trans('user.alert.success'))
            ->text(trans('user.alert.update-wa-notification-successfully'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();

        $this->disableWaNotificationModal = false;
    }


    public function render()
    {
        return view('livewire.admin.customers.modal.disable-wa-notification');
    }
}

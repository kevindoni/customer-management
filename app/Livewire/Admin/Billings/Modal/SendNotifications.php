<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Jobs\SendInvoiceReminderJob;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class SendNotifications extends Component
{
    public $sendNotificationsModal = false;

    #[On('send-notification-modal')]
    public function showCreateNewBillingsModal()
    {
        $this->sendNotificationsModal = true;
        if ($this->sendNotificationsModal) {
            dispatch(new SendInvoiceReminderJob())->onQueue('default');
            $message =  trans('billing.alert.send-notification-succesfully');
            $this->notification(trans('billing.alert.success'), $message, 'success');
            $this->dispatch('refresh-billing-paket');
        }
        $this->sendNotificationsModal = false;
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
        return view('livewire.admin.billings.modal.send-notifications');
    }
}

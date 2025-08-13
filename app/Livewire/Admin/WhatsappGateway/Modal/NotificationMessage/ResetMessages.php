<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal\NotificationMessage;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Artisan;
use App\Models\WhatsappGateway\WhatsappNotificationMessage;

class ResetMessages extends Component
{
    public $resetNotificationMessageModal = false;
    public $input = [];

     #[On('show-reset-notification-message-modal')]
    public function showResetNotificationMessageModal()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->resetNotificationMessageModal = true;
    }


    public function resetNotificationMessage(CurrentPasswordRequest $request)
    {
        // dd($this->input['parent']);
        $request->validate($this->input);
        WhatsappNotificationMessage::query()->delete();
        Artisan::call('db:seed --class=WhatsappNotificationMessageTableSeeder');
        $this->dispatch('refresh-notification-message-list');
        $this->notification(trans('Success'), trans('Reset boot messages successfully.'), 'success');
        $this->resetNotificationMessageModal = false;
    }

    public function notification($title, $msg, $status)
    {
        LivewireAlert::title($title)
            ->text($msg)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }


    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.notification-message.reset-messages');
    }
}

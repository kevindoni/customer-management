<?php

namespace App\Livewire\Admin\WhatsappGateway;

use App\Models\WhatsappGateway\WhatsappNotificationMessage;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class NotificationMessageController extends Component
{
    use WithPagination;

    //public $perPage = 8;

    #[On('refresh-notification-message-list')]
    public function render()
    {
        $notificationMessages = WhatsappNotificationMessage::paginate(10);
        return view('livewire.admin.whatsapp-gateway.notification-message-controller', compact('notificationMessages'));
    }
}

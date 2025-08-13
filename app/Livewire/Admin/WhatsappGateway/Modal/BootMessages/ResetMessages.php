<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal\BootMessages;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Artisan;
use App\Models\WhatsappGateway\WhatsappBootMessage;

class ResetMessages extends Component
{

    public $resetBootMessageModal = false;
    public $input = [];

     #[On('show-reset-boot-message-modal')]
    public function showResetBootMessageModal()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->resetBootMessageModal = true;
    }


    public function resetBootMessage(CurrentPasswordRequest $request)
    {
        // dd($this->input['parent']);
        $request->validate($this->input);
        WhatsappBootMessage::query()->delete();
        Artisan::call('db:seed --class=WhatsappBootMessageTableSeeder');
        $this->dispatch('refresh-message-wa-list');
        $this->notification(trans('Success'), trans('Reset boot messages successfully.'), 'success');
        $this->resetBootMessageModal = false;
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
        return view('livewire.admin.whatsapp-gateway.modal.boot-messages.reset-messages');
    }
}

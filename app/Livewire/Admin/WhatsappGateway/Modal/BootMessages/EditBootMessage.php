<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal\BootMessages;


use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Actions\WhatsappGateways\WhatsappBootMessageAction;
use App\Models\WhatsappGateway\WhatsappBootMessage;
use App\Http\Requests\WhatsappGateway\UpdateBootMessageRequest;

class EditBootMessage extends Component
{
    public $editBootMessageModal = false;
    public $whatsappBootMessage;
    public $input = [];

    #[On('show-edit-boot-message-modal')]
    public function showEditBootMessageModal(WhatsappBootMessage $whatsappBootMessage)
    {
        $this->reset();
        $this->resetErrorBag();
        $this->editBootMessageModal = true;
        if ($whatsappBootMessage->exists) {
            $this->input = array_merge([
                'checkbox_end_message' => $whatsappBootMessage->end_message ? true : false,
                'checkbox_hidden_message' => $whatsappBootMessage->hidden_message ? true : false,
                'parent' => $whatsappBootMessage->whatsapp_boot_message_id,
            ], $whatsappBootMessage->withoutRelations()->toArray());
            $this->whatsappBootMessage = $whatsappBootMessage;
        } else {
            $this->whatsappBootMessage = new WhatsappBootMessage();
        }
    }


    public function updateMessage(UpdateBootMessageRequest $request)
    {
        // dd($this->input['parent']);
        $request->validate($this->whatsappBootMessage, $this->input);
        (new WhatsappBootMessageAction())->update($this->whatsappBootMessage, $this->input);
        $this->dispatch('refresh-message-wa-list');
        $this->dispatch('notify', [
            'status' => 'success',
            'title' =>  trans('wa-gateway.alert.success'),
            'message' =>  trans('wa-gateway.alert.update-message-successfully')
        ]);
        $this->editBootMessageModal = false;
    }


    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.boot-messages.edit-boot-message');
    }
}

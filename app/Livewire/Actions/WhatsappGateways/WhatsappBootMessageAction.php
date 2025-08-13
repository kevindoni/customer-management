<?php

namespace App\Livewire\Actions\WhatsappGateways;

use App\Models\WhatsappGateway\WhatsappBootMessage;

class WhatsappBootMessageAction
{
    
    public function update(WhatsappBootMessage $whatsappBootMessage, array $input)
    {
         $whatsappBootMessage->forceFill([
            'name' => $input['name'],
            'whatsapp_boot_message_id' => $input['parent'] ?? null,
            'command_number' => $input['command_number'] ?? null,
            'action_message' => $input['action_message'] ?? null,
            'message' => $input['message'],
            'end_message' => $input['checkbox_end_message'] ?? false,
            'hidden_message' => $input['checkbox_hidden_message'] ?? false,
            'description' => $input['description'] ?? null,
        ])->save();

        return $whatsappBootMessage;
    }
    
}
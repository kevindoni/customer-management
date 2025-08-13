<?php
namespace App\Livewire\Actions\Customers;

use App\Models\Customers\UserBootMessage;

class UserBootMessageAction
{

    public function add($userID, $waMessageID)
    {
        return UserBootMessage::create([
            'user_id' => $userID,
            'whatsapp_boot_message_id' => $waMessageID,
        ]);
    }

    public function update(UserBootMessage $userBootMessage, $userID, $waMessageID)
    {
        return $userBootMessage->forceFill([
            'user_id' => $userID,
            'whatsapp_boot_message_id' => $waMessageID,
        ])->save();
    }

    public function delete(UserBootMessage $userMessage)
    {
        $userMessage->delete();
    }
}

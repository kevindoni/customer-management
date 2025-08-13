<?php

namespace App\Http\Requests\WhatsappGateway;

use Illuminate\Validation\Rule;
use App\Models\WhatsappGateway\WhatsappBootMessage;
use Illuminate\Support\Facades\Validator;

class UpdateBootMessageRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(WhatsappBootMessage $waMessage, array $input): array
    {
        $welcomeMesage = WhatsappBootMessage::whereNull('whatsapp_boot_message_id')->get();
        if (count($welcomeMesage)) {
            if ($welcomeMesage->first()->id == $waMessage->id) {
                $required = 'nullable';
            } else {
                $required = 'required';
            }
        } else {
            $required = 'nullable';
        }
        Validator::make($input, [
            'name'          => ['required', Rule::unique('whatsapp_boot_messages')->ignore($waMessage->id)],
            'message'        => ['required'],
            'parent' => [$required],
            'command_number'        => ['unique:whatsapp_boot_messages,command_number,' . $waMessage->id . ',id,whatsapp_boot_message_id,' . $input['parent']],
            'action_message'        => ['unique:whatsapp_boot_messages,action_message,' . $waMessage->id . ',id,whatsapp_boot_message_id,' . $input['parent']],
        ], [
            'name.required' => __('Name is required.'),
        ])->validate();

        return $input;
    }
}

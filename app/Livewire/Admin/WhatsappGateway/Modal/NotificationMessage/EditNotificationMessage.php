<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal\NotificationMessage;

use App\Http\Controllers\API\WhatsappGateway;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\WhatsappGateway\WhatsappNotificationMessage;

class EditNotificationMessage extends Component
{
    // use WhatsappGatewayTrait;
    public $editNotificationMessageModal = false;
    public $message;
    public $input = [];

    #[On('show-edit-notification-message-modal')]
    public function editMessageModal(WhatsappNotificationMessage $message)
    {
        //dd($message);
        $this->reset();
        // $response = (new GatewayApiService())->showRequest(WhatsappGateway::NOTIFICATION_MESSAGE, $messageID);

        //  if ($response['result']['success']) {

        $this->editNotificationMessageModal = true;
        // $message = $response['result']['data']['message'];
        $this->input = array_merge([
            // 'name' => $message->name,
            // 'message' => $message->message,
            //'description' => $message->description,
        ], $message->withoutRelations()->toArray());
        $this->message = $message;
        // } else {
        //    $this->notification($response['result']['message'], $response['data'], 'error');
        //}
    }

    public function update()
    {
        Validator::make(
            $this->input,
            [
                'message' => ['required', 'string', 'min:10'],
                'description' => ['nullable', 'string', 'min:10', 'max:255'],
            ]
        )->validate();

        $this->message->forceFill([
            'message' => $this->input['message'],
            'description' => $this->input['description']
        ])->save();

        // $response = (new GatewayApiService())->updateRequest(WhatsappGateway::NOTIFICATION_MESSAGE, $this->message, $this->input);

        //  if ($response['result']['success']) {

        //  Cache::flush();


        $this->notification('Success', 'Update message successfully.', 'success');
        $this->closeModal();
        // } else {
        //  if ($response['status_code'] == 400) {
        //dd($response['result']['data']);
        //    $msg = implode(' ', array_map(function ($entry) { return ($entry[key($entry)]);}, $response['result']['data']));
        //  $this->notification($response['result']['message'], $msg, 'error');
        // } else {
        // $this->notification('Failed', $response['result']['message'], 'error');
        // }
        // }
    }

    public function closeModal()
    {
        $this->editNotificationMessageModal = false;
        $this->dispatch('refresh-notification-message-list');
    }

    public function notification($title, $content, $status)
    {
        LivewireAlert::title($title)
            ->text($content)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.notification-message.edit-notification-message');
    }
}

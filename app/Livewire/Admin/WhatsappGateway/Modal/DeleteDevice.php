<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteDevice extends Component
{
    public $deleteDeviceModal = false;
    public $number;
    public $input = [];

    #[On('show-delete-device-modal')]
    public function deleteDeviceModal($number)
    {
        $this->deleteDeviceModal = true;
        $this->number = $number;
    }

    public function deleteDevice(CurrentPasswordRequest $request )
    {
        $request->validate($this->input);
        $response = (new GatewayApiService())->deleteRequest(WhatsappGateway::DEVICES, $this->number, $this->input);
       $statusCode = $response->getStatusCode();
        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['success']) {
            //Cache::forget('whatsapp-gateway-device');
            Cache::flush();
            $this->notification('Success', $response['message'], 'success');
            $this->closeModal();
        } else {
            if ($statusCode == 400) {
                $msg = implode(' ', array_map(function ($entry) { return ($entry[key($entry)]);}, $response['data']));
                $this->notification($response['message'], $msg, 'error');
            } else {
                $this->notification('Failed', $response['message'], 'error');
            }
        }
    }

    public function closeModal()
    {
        $this->deleteDeviceModal = false;
        $this->dispatch('refresh-devices-list');
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
        return view('livewire.admin.whatsapp-gateway.modal.delete-device');
    }
}

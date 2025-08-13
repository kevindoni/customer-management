<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use App\Http\Controllers\API\WhatsappGateway;
use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WhatsappGatewayTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditDevice extends Component
{
    //use WhatsappGatewayTrait;

    private $url;

    //protected const ROUTE_DEVICE = 'api/devices';

    public $editDeviceModal = false;
    public $number;
    public $input = [];
    public $device;


    #[On('show-edit-device-modal')]
    public function editDeviceModal($number)
    {
        $this->reset();
        $response = (new GatewayApiService())->showRequest(WhatsappGateway::DEVICES, $number);
        if ($response['result']['success']) {
            $this->editDeviceModal = true;
            $this->input = array_merge([
                'number' => $response['result']['data']['device']['body'],
            ], $response['result']['data']['device']);
            $this->device = $response['result']['data']['device'];
        } else {
            $this->notification('Failed', $response['result']['message'], 'error');
        }
    }

    public function updateNumber()
    {
        Validator::make(
            $this->input,
            [
                'device_name' => 'required',
                'description' => ['nullable', 'string', 'min:10', 'max:255'],
            ]
        )->validate();
  

        if ($this->device['description'] != $this->input['description'] || $this->device['device_name'] != $this->input['device_name']) {
            $response = (new GatewayApiService())->updateRequest(WhatsappGateway::DEVICES, $this->device['body'], $this->input);

            if ($response['result']['success']) {
               // Cache::forget('whatsapp-gateway-device');
               Cache::flush();
                $this->notification('Success', $response['result']['message'], 'success');
            } else {
                if ($response['status_code'] == 400) {
                    $msg = implode(' ', array_map(function ($entry) { return ($entry[key($entry)]);}, $response['result']['data']));
                    $this->notification($response['result']['message'], $msg, 'error');
                } else {
                    $this->notification('Failed', $response['result']['message'], 'error');
                }
            }
        }
        $this->closeModal();
    }


    public function closeModal()
    {
        $this->editDeviceModal = false;
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
        return view('livewire.admin.whatsapp-gateway.modal.edit-device');
    }
}

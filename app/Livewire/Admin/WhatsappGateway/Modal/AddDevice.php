<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AddDevice extends Component
{
    public $addDeviceModal = false;
    public $number;
    public $input = [];

    #[On('show-add-device-modal')]
    public function addDeviceModal()
    {
        $this->reset();
        $this->addDeviceModal = true;
    }

    public function addNumber()
    {
        Validator::make(
            $this->input,
            [
                'device_name' => 'required',
                'body' => 'required|numeric',
                'description' => ['nullable', 'string', 'min:10', 'max:255'],
            ],
            [
                'body.required' => 'The number is required',
                'body.numeric' => 'The number must be a number.',
            ]
        )->validate();
        $response = (new GatewayApiService())->addRequest(WhatsappGateway::DEVICES, $this->input);

        if ($response['result']['success']) {
            //Cache::forget('whatsapp-gateway-device');
            Cache::flush();
            $this->closeModal();
            $this->notification('Success', $response['result']['message'], 'success');
        } else {
            switch ($response['status_code']) {
                case 400:
                    $msg = implode(' ', array_map(function ($entry) {
                        return ($entry[key($entry)]);
                    }, $response['result']['data']));
                    $this->notification($response['result']['message'], $msg, 'error');
                    break;
                default:
                    $this->notification('Error', $response['result']['message'], 'error');
            }
        }
    }


    public function closeModal()
    {
        $this->addDeviceModal = false;
        $this->dispatch('refresh-devices-list');
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
        return view('livewire.admin.whatsapp-gateway.modal.add-device');
    }
}

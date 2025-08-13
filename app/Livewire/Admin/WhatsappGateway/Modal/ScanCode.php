<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ScanCode extends Component
{
    public $scanCodeModal = false;
    public $number;

    #[On('show-scan-code-modal')]
    public function showScanBarcodeModal($number)
    {
        $this->scanCodeModal = true;
        $this->number = $number;
        $response = (new GatewayApiService())->showRequest(WhatsappGateway::DEVICES, $number);

        if ($response['result']['success']) {
            $this->dispatch('get-whatsapp-code', $response['result']['data']['device'], [
                'url' => 'https://' . config('wa-griyanet.server_url') . ':' . config('wa-griyanet.server_port'),
                //'privatekey' => env('API_CLIENT_MESSAGE')
                'user-name' => env('API_USERNAME')
            ]);
        } else {
            $str_json = json_encode($response['result']['data'] ?? 'Model not found');
            LivewireAlert::title($str_json)
                ->text($response['result']['message'] ?? 'Unknow error, please contact administrator.')
                ->position('top-end')
                ->toast()
                ->error()
                ->show();
        }

    }

    #[On('close-scan-code-modal')]
    public function closeModal()
    {
        Cache::flush();
        $this->scanCodeModal = false;
        $this->dispatch('refresh-device-list');
    }

    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.scan-code');
    }
}

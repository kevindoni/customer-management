<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ScanBarcode extends Component
{
    public $scanBarcodeModal = false;
    public $number;

    #[On('show-scan-barcode-modal')]
    public function showScanBarcodeModal($number)
    {
        $this->scanBarcodeModal = true;
        $this->number = $number;
        $response = (new GatewayApiService())->showRequest(WhatsappGateway::DEVICES, $number);

        if ($response['result']['success']) {
            $this->dispatch('get-whatsapp-barcode', $response['result']['data']['device'], [
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

    #[On('close-scan-barcode-modal')]
    public function closeModal()
    {
        $this->scanBarcodeModal = false;
        // Cache::forget('whatsapp-gateway-device');
        Cache::flush();
        $this->dispatch('refresh-devices-list');
    }

    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.scan-barcode');
    }
}

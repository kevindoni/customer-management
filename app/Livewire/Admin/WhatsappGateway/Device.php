<?php

namespace App\Livewire\Admin\WhatsappGateway;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Support\CollectionPagination;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class Device extends Component
{
    use WithPagination;
    public function testSendMessage($number)
    {
        $data = [
            'sender' => $number,
            'number' => $number,
            'message' => 'Congrulation, whatsapp gateway successfully connected.'
        ];
        $response = (new GatewayApiService)->sendMessage(WhatsappGateway::SEND_MESSAGE, $data);

        if ($response['result']['success']) {
            //Cache::pull('whatsapp-gateway-device', $response);
            Cache::flush();
            $this->notification('Success', $response['result']['message'], 'success');
        } else {
            $this->notification('Failed', $response['result']['message'], 'error');
        }
    }

    private function notification($title, $msg, $status)
    {
        LivewireAlert::title($title)
            ->text($msg ?? 'Unknow error, please contact administrator.')
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    //public $perPage = 8;
    #[On('refresh-devices-list')]
    public function render()
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $devices = [];
        $total = 1;
        $perPage = 10;

        try {
            $response = (new GatewayApiService())->getRequest(WhatsappGateway::DEVICES);
            $response = $response['result'];
            if ($response['success']) {
                $total =  $response['data']['total'];
                $perPage = $response['data']['per_page'];
                $devices = $response['data']['devices'];
            } else {
                $this->notification('Error', $response['message'] ?? 'Unknow error, please contact administrator.', 'error');
            }
        } catch (\Exception $e) {
            $devices = [];
            $this->notification('Error', $e->getMessage(), 'error');
        }

        $devices = new LengthAwarePaginator($devices, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        return view('livewire.admin.whatsapp-gateway.device', compact('devices'));
    }
}

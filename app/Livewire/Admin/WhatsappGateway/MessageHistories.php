<?php

namespace App\Livewire\Admin\WhatsappGateway;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\CollectionPagination;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class MessageHistories extends Component
{
    use WithPagination;
    private function notification($title, $msg, $status)
    {
        LivewireAlert::title($title)
            ->text($msg ?? 'Unknow error, please contact administrator.')
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        $perPage = 1;
        $total = 10;
        $messageHistories = [];
        $page = LengthAwarePaginator::resolveCurrentPage();

        try {
            $response = (new GatewayApiService())->getRequest(WhatsappGateway::MESSAGE_HISTORIES);
            $response = $response['result'];

            if ($response['success']) {
                $perPage = $response['data']['per_page'];
                $total = $response['data']['total'];
                $messageHistories = $response['data']['message_histories'];
            } else {
                //  Cache::forget('whatsapp-gateway-message-histories');
                $this->notification('Error', $response['result']['message'] ?? 'Unknow error, please contact administrator.', 'error');
            }
        } catch (\Exception $e) {
            $messageHistories = [];
            $this->notification('Error', $e->getMessage(), 'error');
        }
        $messageHistories = new LengthAwarePaginator($messageHistories, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        return view('livewire.admin.whatsapp-gateway.message-histories', [
            'messageHistories' => $messageHistories
        ]);
    }
}

<?php

namespace App\Livewire\Admin\WhatsappGateway;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Support\CollectionPagination;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class InvoiceManagement extends Component
{
    private function notification($title, $msg, $status)
    {
        LivewireAlert::title($title)
            ->text($msg ?? 'Unknow error, please contact administrator.')
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    #[On('refresh-invoice-list')]
    public function render()
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $total = 1;
        $invoices = [];

        try {
            $res = (new GatewayApiService())->getRequest(WhatsappGateway::INVOICE);
            $response = $res['result'];

            if ($response['success']) {
                $perPage = $response['data']['per_page'];
                $total = $response['data']['total'];
                $invoices = $response['data']['invoices'];
            } else {
                $this->notification('Error', $response['message'] ?? 'Unknow error, please contact administrator.', 'error');
            }
        } catch (\Exception $e) {
            $invoices = [];
            $this->notification('Error', $e->getMessage(), 'error');
        }
        // $invoices = (new CollectionPagination($invoices))->collectionPaginate(20);
        $invoices = new LengthAwarePaginator($invoices, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);


        return view('livewire.admin.whatsapp-gateway.invoice-management', compact('invoices'));
    }
}

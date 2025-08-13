<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Payment extends Component
{
    public $paymentModal = false;
    public $input = [];
    public $paymentMethods = [];
    public $invoice, $order, $company, $instructions, $invoice_id;

    public $currentStep = 1;

    #[On('show-payment-modal')]
    public function showPaymentModal($invoiceID)
    {
        $this->reset();

        $getInvoice = (new GatewayApiService())->showRequest(WhatsappGateway::INVOICE, $invoiceID);
        $result = $getInvoice['result'];
        if ($result['success']) {
            $this->paymentModal = true;
            $this->order = $result['data']['order'];
            if ($this->order) {
                $this->currentStep = 2;
            }
            $this->company = $result['data']['company'];
            $this->invoice = $result['data']['invoice'];
            $this->paymentMethods = $result['data']['payment_methods'];
            $this->invoice_id = $invoiceID;
        } else {
            $this->notification('Error', $result['message'], 'error');
        }
    }

    public function processOrder()
    {
        Validator::make($this->input, [
            'payment_method' => ['required'],
        ])->validate();
        $response = (new GatewayApiService())->updateRequest(WhatsappGateway::INVOICE, $this->invoice_id, $this->input);
        if ($response['result']['success']) {
            $this->currentStep = 2;
            $this->input['paymentCode'] = $response['result']['data']['order']['pay_code'];
            $this->order = $response['result']['data']['order'];
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

    public function paymentInstructions()
    {
        $this->currentStep = 3;
        $this->instructions = json_decode($this->order['instructions'], true);
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function closeModal()
    {
        $this->paymentModal = false;
        $this->dispatch('refresh-invoice-list');
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
        return view('livewire.admin.whatsapp-gateway.modal.payment');
    }
}

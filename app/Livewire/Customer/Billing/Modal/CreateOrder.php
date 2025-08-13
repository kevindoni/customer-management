<?php

namespace App\Livewire\Customer\Billing\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Billings\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\Payments\PaymentGatewayService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreateOrder extends Component
{
    public $createOrderModal = false;
    public $input = [];
    public $invoice;
    public $paymentChanels = [];
    public $order;
    public $currentStep = 0;
    public  $inctructions;
    private $paymentGatewayService;

    public function __construct()
    {
        $this->paymentGatewayService = new PaymentGatewayService;
    }

    #[On('show-create-order-modal')]
    public function showCreateOrderModal(Invoice $invoice)
    {
        // dd($invoice);
        if ($invoice->order && $invoice->order->status == 'pending' && Carbon::parse($invoice->order->expired_time)->gte(Carbon::now())) {
            $this->createOrderModal = true;
            $this->invoice = $invoice;
            $this->order = $invoice->order;
            $this->orderDetail();
        } else {
            $this->paymentChanels = $this->paymentGatewayService->requestPaymentChanel();

             if ($this->paymentChanels['payment_chanels']['success']) {
                $this->currentStep = 1;
                $this->createOrderModal = true;
                $this->invoice = $invoice;
              //  if ($invoice->status == 'proccess') {
              //  } else {
                    $this->paymentChanels = $this->paymentChanels['payment_chanels']['data'];
              //  }
            } else {
                Log::info('Tripay: '.$this->paymentChanels['payment_chanels']['message']);
                $title = 'Failed';
                $message =  'Maaf saat ini sedang mengalami gangguan teknis. Silahkan coba lagi nanti!';
                $this->notification($title, $message, 'error');
            }
        }
    }

    public function processOrder()
    {
        Validator::make($this->input, [
            'method' => ['required'],
        ])->validate();
        //if ($this->invoice->order) $this->invoice->order->delete();

        $res = $this->paymentGatewayService->createTransaction($this->invoice, $this->input['method']);
        if ($res['success'] && $res['data']['status'] == 'UNPAID') {

           if ($this->invoice->orders->count()) $this->invoice->orders()->forceDelete();
            $this->invoice->forceFill(['status' => 'process'])->save();
            $res['data']['status'] = 'pending';
            unset($res['data']['pay_url']);
            unset($res['data']['checkout_url']);
            unset($res['data']['callback_url']);
            unset($res['data']['return_url']);
            $res['data']['expired_time'] = Carbon::parse($res['data']['expired_time'])->setTimezone('Asia/Jakarta');
            $res['data']['order_items'] = json_encode($res['data']['order_items']);
            $res['data']['instructions'] = json_encode($res['data']['instructions']);
            $res['data']['payment_gateway_channel'] = PaymentGateway::whereIsActive(true)->first()->value;

            $this->order = new Order();
            $this->order = $this->invoice->order()->save($this->order);
            $this->order->forceFill($res['data'])->save();
            $this->orderDetail();
        } else {
            $title = trans('Gagal');
            $message = $res['message'] ?? trans('payment-gateway.message.failed-status');
            $this->notification($title, $message, 'error');
        }
    }

    public function changeOrderModal(Invoice $invoice)
    {

        $this->paymentChanels = $this->paymentGatewayService->requestPaymentChanel();


        if ($this->paymentChanels['success']) {
            if ($this->paymentChanels['payment_chanels']['success']) {
                $this->currentStep = 1;
                $this->createOrderModal = true;
                $this->invoice = $invoice;
              //  if ($invoice->status == 'proccess') {
              //  } else {
                    $this->paymentChanels = $this->paymentChanels['payment_chanels']['data'];
               // }
            } else {
                Log::info('Tripay: '.$this->paymentChanels['payment_chanels']['message']);
                $title = 'Failed';
                $message =  'Maaf saat ini sedang mengalami gangguan teknis. Silahkan coba lagi nanti!';
                $this->notification($title, $message, 'error');
            }
        } else {
            $title = 'Failed';
            $message =  $this->paymentChanels['message'];
            $this->notification($title, $message, 'error');
        }
    }
    private function orderDetail()
    {
        $this->input['paymentCode'] = $this->order->pay_code;
        $this->currentStep = 2;
    }

    public function instructions()
    {
        $this->inctructions = json_decode($this->order->instructions, true);
        $this->currentStep = 3;
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }


    public function closeModal()
    {
        $this->createOrderModal = false;
        $this->dispatch('refresh-invoice-list');
    }
    private function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        return view('livewire.customer.billing.modal.create-order');
    }
}

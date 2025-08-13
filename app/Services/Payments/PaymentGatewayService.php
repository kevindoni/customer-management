<?php

namespace App\Services\Payments;

use ZerosDev\TriPay\Merchant;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use ZerosDev\TriPay\Transaction;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Client as TripayClient;


class PaymentGatewayService
{

    protected TripayClient $tripayClient;

    protected array $order_items = [];

    public function __construct()
    {
        $paymentGateway = $this->paymentGateway();
        if ($paymentGateway['success']){
            if ($paymentGateway['payment_gateway'] === 'tripay'){
               // dd($paymentGateway);
                $this->tripayClient = new TripayClient($paymentGateway['config']);
            }

        }

    }

    private function paymentGateway()
    {
        $paymentGateway = PaymentGateway::whereIsActive(true)->first();

        if ($paymentGateway) {
            switch ($paymentGateway->value) {
                case 'tripay':
                   // dd($paymentGateway->value);
                    if ($paymentGateway->mode == 'development') $config = [
                        'mode' => 'development',
                        'merchant_code' => $paymentGateway->development_merchant_code,
                        'api_key' => $paymentGateway->development_api_key,
                        'private_key' => $paymentGateway->development_secret_key,
                        'guzzle_options' => []
                    ];
                    if ($paymentGateway->mode == 'production') $config = [
                        'mode' => 'production',
                        'merchant_code' => $paymentGateway->merchant_code,
                        'api_key' => $paymentGateway->production_api_key,
                        'private_key' => $paymentGateway->production_secret_key,
                        'guzzle_options' => []
                    ];
                    return [
                        'success' => true,
                        'config' => $config,
                        'payment_gateway' => 'tripay'
                    ];
                case 'midtrans':
                    return [
                        'success' => true,
                        'config' => '',
                        'payment_gateway' => 'midtrans'
                    ];
            }
        }

        return [
            'success' => false,
            'message' => 'Saat ini anda tidak dapat menggunakan methode pembayaran ini. Silahkan hubungi administrator kami!'
        ];
    }
    public function requestPaymentChanel()
    {
        $paymentGateway = $this->paymentGateway();
        if (!$paymentGateway['success']) return $paymentGateway;
        if ($paymentGateway['payment_gateway'] == 'tripay') {
            $client = $this->tripayClient;
            $merchant = new Merchant($client);
            $result = $merchant->paymentChannels();
        }
        return [
            'success' => true,
            //  'payment_gateway' => $paymentGateway['payment_gateway'],
            'payment_chanels' => json_decode($result->getBody()->getContents(), true)
        ];
    }


    public function createTransaction($invoice, $method)
    {
        $paymentGateway = $this->paymentGateway();
        if (!$paymentGateway['success']) return $paymentGateway;
        if ($paymentGateway['payment_gateway'] == 'tripay') {
            $client = $this->tripayClient;
            $transaction = new Transaction($client);

            $totalPaid = $invoice->payments->sum('amount');
            $totalRefunded = $invoice->payments->sum('refunded_amount');
            $netPaid = $totalPaid - $totalRefunded;
           // $paid = $invoice->amount - $netPaid;
            $paid = ($invoice->amount - $invoice->discount) + $invoice->tax - $netPaid;
            $result = $transaction
                ->addOrderItem($invoice->customer_paket->paket->name . ' - ' . Carbon::parse($invoice->periode)->format('F Y').' Cicilan Pembayaran: '.$netPaid, $paid, 1)
                // ->addOrderItem('Kopi', 5000, 3)
                ->create([
                    'method' => $method,
                    'merchant_ref' => $invoice->invoice_number,
                    'customer_name' => $invoice->user->full_name,
                    'customer_email' => $invoice->user->email,
                    'customer_phone' => $invoice->user_address->phone,
                    'return_url' => 'https://example.com/return',
                    'expired_time' => Helper::makeTimestamp('1 DAY')
                ]);
            return json_decode($result->getBody()->getContents(), true);
        }
    }
}

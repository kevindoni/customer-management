<?php

namespace App\Jobs\Payments;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Payments\PartialPaymentService;

class PaymentProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice, $paymentMethode, $amount, $bank, $paylaterDate;
    public function __construct($invoice, $amount, $paymentMethode, $bank, $paylaterDate)
    {
        $this->paymentMethode = $paymentMethode;
        $this->invoice = $invoice;
        $this->bank = $bank;
        $this->amount = $amount;
        $this->paylaterDate = $paylaterDate;
    }
    /**
     * Execute the job.
     */
    public function handle(PartialPaymentService $partialPaymentService, $teller): void
    {
        Log::info('Payment process');
       $partialPaymentService->processPartialPayment(
            $this->invoice,
            $this->amount,
            $this->paymentMethode,
            $this->bank,
            $this->input['paylaterDate'] ?? null,
            $teller
        );
    }
}

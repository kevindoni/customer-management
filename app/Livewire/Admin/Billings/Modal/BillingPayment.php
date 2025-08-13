<?php

namespace App\Livewire\Admin\Billings\Modal;

use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Billings\PaymentRequest;
use App\Services\Payments\PartialPaymentService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class BillingPayment extends Component
{
    public $input = [];
    public Invoice $invoice;
    public $paymentModal = false;
    public $account_bank;

    #[On('billing-payment-modal')]
    public function showPaymentModal(Invoice $invoice)
    {
        $this->reset();
        $this->invoice = $invoice;
        $this->paymentModal = true;
    }

    public function updatedInputSelectedPaymentMethode($paymentMethode)
    {
        $paymentMethode ? $this->dispatch('input-amount', open: true) : $this->dispatch('input-amount', open: false);

        if ($paymentMethode === 'paylater') {
            $this->input['amount'] = 0;
            $this->dispatch('select-paylater-date', open: true);
        } else {
            $this->dispatch('select-paylater-date', open: false);
            $this->input['amount'] = ($this->invoice->amount - $this->invoice->discount + $this->invoice->tax) - $this->invoice->payments()->where('reconciliation_status', 'partial')->whereNull('refund_status')->sum('amount');
        }

        if ($paymentMethode === 'bank_transfer') {
            $this->dispatch('select-methode-transfer', open: true);
        } else {
            $this->dispatch('select-methode-transfer', open: false);
        }
    }


    public function payment(PaymentRequest $request, PartialPaymentService $partialPaymentService)
    {
        $request->validate($this->invoice, $this->input);
        $bank = null;
        if ($this->input['selectedPaymentMethode'] === 'bank_transfer'){
            $bank = Bank::whereSlug($this->input['selectedBankTransfer'])->first();
            $bank = $bank->bank_name.' - '.$bank->account_name.' - '.$bank->account_number;
        }


        $paymentResult = $partialPaymentService->processPartialPayment(
            $this->invoice,
            $this->input['amount'] == '' ? 0 : $this->input['amount'],
            $this->input['selectedPaymentMethode'],
            $bank,
            $this->input['paylaterDate'] ?? null,
            Auth::user()->full_name
        );

        if ($paymentResult['success']) {
            $this->paymentModal = false;
            $this->dispatch('refresh-billing-paket');
            $message = trans('billing.alert.payment-success-message', ['periode' => Carbon::parse($this->invoice->periode)->format('F Y'), 'paket' => $this->invoice->customer_paket->paket->name, 'customer' =>  $this->invoice->customer_paket->user->first_name]);
            $this->notification(trans('billing.alert.payment-success'), $message, 'success');
        } else {
            $this->notification(trans('billing.alert.payment-failed'), $paymentResult['message'], 'error');
        }
    }

    public function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    public function render()
    {
        return view('livewire.admin.billings.modal.billing-payment');
    }
}

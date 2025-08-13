<?php

namespace App\Livewire\Admin\Billings\Modal;

use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Billings\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\Payments\PartialPaymentService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class BulkPayment extends Component
{

    public $bulkPaymentModal = false;
    public $input = [];
    public $invoices;
    #[On('bulk-payment-modal')]
    public function showBulkPaymentModal($invoiceSelected)
    {
        $this->reset();
        $this->bulkPaymentModal = true;
        $invoices = Invoice::query()
            ->whereIn('id', $invoiceSelected)
            ->get();
        $this->invoices = $invoices;
        $invoiceSelected = [];
    }

    public function bulkPayment(PartialPaymentService $partialPaymentService)
    {
        Validator::make($this->input, [
            'selectedPaymentMethode' => ['required'],
            'selectedBankTransfer' => ['required_if:selectedPaymentMethode,==,bank_transfer', 'nullable']

        ], [
            'selectedPaymentMethode.required' => __('billing.validation-error-message.payment-methode-required'),
            'selectedBankTransfer.required_if' => __('billing.validation-error-message.selected-bank-required'),
        ])->validate();

        $bank = null;
        if ($this->input['selectedPaymentMethode'] === 'bank_transfer') {
            $bank = Bank::whereSlug($this->input['selectedBankTransfer'])->first();
            $bank = $bank->bank_name . ' - ' . $bank->account_name . ' - ' . $bank->account_number;
        }

        $countSuccess = 0;
        $countFailed = 0;
        foreach ($this->invoices as $invoice) {
            $response = $partialPaymentService->processPartialPayment(
                $invoice,
                $invoice->amount,
                $this->input['selectedPaymentMethode'],
                $bank,
                null,
                Auth::user()->full_name
            );
            if($response['success']){
                $countSuccess++;
            } else {
                $countFailed++;
                Log::error($response['message']);
            }
        }

        $this->dispatch('refresh-billing-paket');
        $this->bulkPaymentModal = false;
        if ($countFailed > 0 && $countSuccess == 0){
            $status = 'error';
            $title = 'Error';
        } else {
            $status = 'success';
            $title = 'Success';
        }


        $message = trans('billing.alert.bulk-payment-success-message', ['countSuccess' => $countSuccess, 'countFailed'=> $countFailed]);
         LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function updatedInputSelectedPaymentMethode($paymentMethode)
    {

        //  $paymentMethode ? $this->dispatch('input-amount', open: true) : $this->dispatch('input-amount', open: false);
        if ($paymentMethode == 'bank_transfer') {
            $this->dispatch('select-methode-transfer', open: true);
        } else {
            $this->dispatch('select-methode-transfer', open: false);
        }
    }

    public function render()
    {
        return view('livewire.admin.billings.modal.bulk-payment');
    }
}

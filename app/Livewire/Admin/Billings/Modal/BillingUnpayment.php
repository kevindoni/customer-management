<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Billings\Invoice;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Services\Payments\CancelPartialPaymentService;

class BillingUnpayment extends Component
{
    // public $modal = 'billing-modal-payment';
    public $input = [];
    public Invoice $invoice;
    public $unpaymentModal = false;

    #[On('billing-unpayment-modal')]
    public function showBillingUnpaymentModal(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->unpaymentModal = true;
    }


    public function unpayment(CurrentPasswordRequest $request, CancelPartialPaymentService $cancelPartialPaymentService)
    {
        $request->validate($this->input);
        $lastPaymentInvoice = $this->invoice->payments()->latest()->first();
        $response = $cancelPartialPaymentService->processCancelPayment(
            $lastPaymentInvoice,
            $lastPaymentInvoice->amount,
        );
       // $response = $billingService->handleCancelPayment($this->invoice);
        $this->dispatch('refresh-billing-paket');
        $this->unpaymentModal = false;
        if ($response['success']) {
          //  (new WhatsappMessageNotificationService())->payment_paket($this->billing, 'unpayment');
            if ($this->invoice->status == 'paylater') {
                $message = trans('billing.alert.cancel-paylater-success-message', ['paket' => $this->invoice->customer_paket->paket->name, 'customer' =>  $this->invoice->customer_paket->user->full_name]);
                $this->notification(trans('billing.alert.cancel-paylater-success-title'), $message, 'success');
            } else {
                $message = trans('billing.alert.unpayment-success-message', ['paket' => $this->invoice->customer_paket->paket->name, 'customer' =>  $this->invoice->customer_paket->user->full_name]);
                $this->notification(trans('billing.alert.unpayment-success-title'), $message, 'success');
            }
        } else {
            $message =  $response['message'];
            $this->notification(trans('billing.alert.unpayment-failed-title'), $message, 'error');
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
        return view('livewire.admin.billings.modal.billing-unpayment');
    }
}

<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Billings\Invoice;
use App\Livewire\Actions\Billings\InvoiceAction;
use App\Http\Requests\Billings\AddDiscountRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class AddDiscount extends Component
{
    // public $modal = 'billing-modal-payment';
    public $input = [];
    public Invoice $invoice;
    public $addDiscountModal = false;


    #[On('add-discount-modal')]
    public function showAddDiscountModal(Invoice $invoice)
    {
        $this->invoice = $invoice;
        //$this->input = array_merge([
        //    'discount' => $invoice->discount
        //], $invoice->withoutRelations()->toArray());
        $this->addDiscountModal = true;
    }


    public function add_discount(AddDiscountRequest $request, InvoiceAction $invoiceAction)
    {
        $request->validate($this->input);
        $addDiscount = $invoiceAction->add_discount(
            $this->invoice,
            $this->input['discount']
        );

        $this->addDiscountModal = false;
        $this->dispatch('refresh-billing-paket');
        if ($addDiscount['success']) {
            $title = trans('billing.alert.payment-success-title');
            $message = trans('billing.alert.payment-success-message', ['paket' => $this->invoice->customer_paket->paket->name, 'customer' =>  $this->invoice->customer_paket->user->full_name]);
            $this->notification($title, $message, 'success');
        } else {
            $this->notification('Failed', $addDiscount['message'], 'error');
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
        return view('livewire.admin.billings.modal.add-discount');
    }
}

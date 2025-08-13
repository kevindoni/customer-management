<?php

namespace App\Livewire\Admin\Billings\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteInvoice extends Component
{

    public $showDeleteInvoiceModal = false;
    public $invoice;
    public $input = [];


    #[On('show-delete-invoice-modal')]
    public function showDeleteInvoice(Invoice $invoice)
    {
        $this->showDeleteInvoiceModal = true;
        $this->invoice = $invoice;
    }

    public function deleteInvoice(CurrentPasswordRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        DB::beginTransaction();
        try {
            //Last Invoice
            $customerPaket = $this->invoice->customer_paket;
            $latestInvoice = $customerPaket->invoices()->latest('periode')->first();
            $renewalPeriod = $customerPaket->getRenewalPeriod();

            if ($latestInvoice) {
                $expiredDate = $latestInvoice->start_periode;
                $startDate = Carbon::parse($expiredDate)->sub($renewalPeriod);
            } else {
                $startDate = $customerPaket->start_date;
                $expiredDate = Carbon::parse($startDate)->add($renewalPeriod);
            }
            $previouslyBilledAt = Carbon::parse($customerPaket->next_billed_at)->sub($renewalPeriod);
            $customerPaket->forceFill([
                'start_date' => $startDate,
                'expired_date' => $expiredDate,
                'next_billed_at' => $previouslyBilledAt,
            ])->save();

            $this->invoice->delete();


            $title = trans('Success');
            $message = trans('Delete invoice successfully.');
            $status = 'success';
            DB::commit();

            $this->notification($title, $message, $status);
        } catch (\Exception $e) {
            DB::rollBack();
            $title = trans('Failed!');
            $this->notification($title, $e->getMessage(), 'error');
        }
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showDeleteInvoiceModal = false;
        $this->dispatch('refresh-billing-paket');
    }

    public function notification($title, $message, $status = 'success')
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
        return view('livewire.admin.billings.modal.delete-invoice');
    }
}

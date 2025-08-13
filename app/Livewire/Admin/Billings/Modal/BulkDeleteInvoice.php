<?php

namespace App\Livewire\Admin\Billings\Modal;

use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\Payments\PartialPaymentService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class BulkDeleteInvoice extends Component
{

    use NotificationTrait;

    public $bulkDeleteInvoiceModal = false;
    public $input = [];
    public $invoices;
    #[On('bulk-delete-invoice-modal')]
    public function showBulkPaymentModal($invoiceSelected)
    {
        $this->bulkDeleteInvoiceModal = true;
        $invoices = Invoice::query()
            ->whereIn('id', $invoiceSelected)
            ->get();
        $this->invoices = $invoices;
        $invoiceSelected = [];
    }

    public function deleteSelectedInvoice(CurrentPasswordRequest $currentPasswordRequest)
    {
        $this->resetErrorBag();
        $currentPasswordRequest->validate($this->input);
        DB::beginTransaction();
        try {
            foreach ($this->invoices as $invoice) {
                //Last Invoice
                $customerPaket = $invoice->customer_paket;
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

                $invoice->delete();
            }

            DB::commit();
            $title = trans('Success');
            $message = trans('Delete invoice successfully.');
            $status = 'success';
            $this->notification($title, $message, $status);
        } catch (\Exception $e) {
            DB::rollBack();
            $title = trans('Failed!');
            $this->notification($title, $e->getMessage(), 'error');
        }

        $this->bulkDeleteInvoiceModal = false;
        $this->dispatch('refresh-billing-paket');
    }

    public function render()
    {
        return view('livewire.admin.billings.modal.bulk-delete-invoice');
    }
}

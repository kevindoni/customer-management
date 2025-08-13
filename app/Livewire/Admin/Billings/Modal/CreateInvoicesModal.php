<?php

namespace App\Livewire\Admin\Billings\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Billings\Invoice;
use App\Services\Billings\ExportInvoiceService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreateInvoicesModal extends Component
{
    public $createInvoicesModal = false;
    public $selectedBillings;
    public $search_address = '';
    public $search_with_periode = 'all-periode';
    public $sortField = 'billing_address';
    public $fileExist = false;
    public $invoicesFile;
    public $invoiceCount = 0;

    #[On('show-create-invoices-modal')]
    public function showCreateInvoicesModal()
    {
        $this->createInvoicesModal = true;
    }

    public $invoice;
    public function exportInvoices(ExportInvoiceService $exportInvoiceService)
    {
        $this->invoicesFile = $exportInvoiceService->create_invoices_file(
            $this->get_users_billing(),
           'invoices_'.$this->search_with_periode
        );
        $this->fileExist = $exportInvoiceService->ceckFile($this->invoicesFile);
    }


    public function download_invoices(ExportInvoiceService $exportInvoiceService)
    {
        $response = $exportInvoiceService->download($this->invoicesFile);
        if (!$response) {
            $this->notification(trans('billing.alert.download-failed'), trans('billing.alert.file-not-found'), 'error');
        } else {
            $this->createInvoicesModal = false;
            return $response;
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


    public function get_users_billing()
    {
        $usersWithBilling = User::with(['invoices' => function ($builder) {
                $builder->where('invoices.status','!=', 'paid');
                $builder->when($this->search_with_periode, function ($builder) {
                    $builder->where(function ($builder) {
                        if ($this->search_with_periode != "all-periode") {
                            $builder->where('periode', $this->search_with_periode);
                        }
                    });
                });
                $builder->orderBy('periode')->orderBy('due_date');
            }])
            // ->whereHas('invoices')
            ->whereHas('invoices', function ($builder) {

                $builder->where('invoices.status','!=', 'paid');

                $builder->when($this->search_with_periode, function ($builder) {
                    $builder->where(function ($builder) {
                        if ($this->search_with_periode != "all-periode") {
                            $builder->where('periode', $this->search_with_periode);
                        }
                    });
                });
            })
            ->with('user_address')
            ->whereHas('user_address', function ($builder) {
                $builder->when($this->search_address, function ($builder) {
                    $builder->where('address', 'like', '%' . $this->search_address . '%')
                        ->orWhere('phone', 'like', '%' . $this->search_address . '%');
                });
            })
            ->get();
            $this->invoiceCount = $usersWithBilling->count();
        return $usersWithBilling;
    }

    public function render()
    {
        $this->get_users_billing();
        $periodes = Invoice::select('periode')
            ->distinct('periode')
            ->orderBy('periode', 'asc')
            ->get();

        return view('livewire.admin.billings.modal.create-invoices-modal', [
            'periodes' => $periodes
        ]);
    }
}

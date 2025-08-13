<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Billings\Invoice;
use App\Services\Billings\ExportInvoiceService;
use Livewire\Attributes\On;

class ShowBillingCustomer extends Component
{
    public $user;
    public function mount(User $user)
    {
        $this->user = $user;

    }

    public function download_customer_invoice(Invoice $invoice, ExportInvoiceService $exportInvoiceService)
    {
        $address = $invoice->customer_paket->user->user_address->address;
        $address = $address === null ? '_' : '_' . $address;
        // $fileName = Str::replace('.', '_', 'invoice_' . $invoice->customer_paket->user->full_name . '_' . Str::replace(' ', $address === null ? '_' : '_', $address));
        $fileName =  Str::slug('invoice_' . $invoice->customer_paket->user->full_name . $address . '_' . $invoice->periode, '_');
        $invoicesFile = $exportInvoiceService->create_invoice_file(
            $invoice,
            $fileName
        );
        $response = $exportInvoiceService->download($invoicesFile);
        if ($response) {
            return $response;
        }
    }

    public function download_customer_invoices(User $user, ExportInvoiceService $exportInvoiceService)
    {
        $address = $user->user_address->address;
        $address = $address === null ? '_' : '_' . $address;
        $fileName =  Str::slug('invoice_' . $user->full_name . $address, '_');

        $invoicesFile = $exportInvoiceService->create_invoices_file(
            collect([$user]),
            $fileName
        );

        $response = $exportInvoiceService->download($invoicesFile);
        if ($response) {
            return $response;
        }
    }


    #[On('refresh-billing-paket')]
    public function render()
    {
        return view('livewire.admin.customers.show-billing-customer');
    }
}

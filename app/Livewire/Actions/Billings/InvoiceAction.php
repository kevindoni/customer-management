<?php

namespace App\Livewire\Actions\Billings;

use App\Traits\Billing;
use App\Models\Websystem;
use Illuminate\Support\Str;
use App\Traits\GenerateName;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use App\Traits\CustomerPaketTrait;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Auth;
use App\Services\Billings\TaxService;
use App\Models\Customers\CustomerPaket;
use Illuminate\Support\Facades\Storage;
use App\Services\Billings\PricingService;
use App\Services\Billings\DeadlineService;




class InvoiceAction
{
    use Billing;
    use GenerateName;
    use CustomerPaketTrait;

    protected $pricingService;
    protected $deadlineService;
    protected $generalLogServices;
    public function __construct(PricingService $pricingService = null, DeadlineService $deadlineService = null, GeneralLogServices $generalLogServices = null)
    {
        $this->pricingService = $pricingService ?? new PricingService();
        $this->deadlineService = $deadlineService ?? new DeadlineService();
        $this->generalLogServices = $generalLogServices ?? new GeneralLogServices();
    }

    /**
     * Add customer paket
     *
     * @param  array<string, string>  $input
     */
    public function create_invoice(CustomerPaket $customerPaket, $invoiceNumber): Invoice
    {
        $webSystem = Websystem::first();
        $subscriptionMode = $webSystem->subscription_mode;


        $renewalPeriod = $this->getRenewalPeriod($customerPaket->renewal_period);
        $latestInvoices = $customerPaket->invoices()->latest()->first();

        if ($latestInvoices) {
            $customerPaket->update([
                'start_date' => $latestInvoices->start_periode,
                'expired_date' => $latestInvoices->end_periode
            ]);

            $startInvoicePeriod = Carbon::parse($latestInvoices->end_periode);
            $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();
        } else {
            $intervalInvoiceDay = Websystem::first()->different_day_create_billing;
            $startInvoicePeriod = $customerPaket->activation_date ?? Carbon::parse($customerPaket->next_billed_at)->addDays($intervalInvoiceDay);
            $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();
        }
        $endInvoicePeriod = Carbon::parse($startInvoicePeriod)->add($renewalPeriod);
        $nextBill = Carbon::parse($endInvoicePeriod)->subDays($webSystem->different_day_create_billing);

        //Delete invoice if exist on soft delete
        $deletedInvoice = Invoice::onlyTrashed()->where('customer_paket_id', $customerPaket->id)->where('periode', $invoicePeriod)->first();
        if ($deletedInvoice) {
            $deletedInvoice->forceDelete();
        }

        // dd($nextBill);

        $invoice = Invoice::create([
            'user_customer_id' => $customerPaket->user->user_customer->id,
            'customer_paket_id' => $customerPaket->id,
            'periode' => $invoicePeriod,
            'invoice_number' => $invoiceNumber,
            'issue_date' => now(),
            'due_date' => $subscriptionMode === 'pascabayar' ?  $endInvoicePeriod : $startInvoicePeriod,
            'start_periode' => $startInvoicePeriod,
            'end_periode' =>  $endInvoicePeriod,
            'status' => 'pending',
        ]);

        $customerPaket->forceFill([
            'next_billed_at' => $nextBill->startOfDay()
        ])->save();
        $this->generalLogServices->create_invoice($customerPaket, $invoicePeriod, Auth::user() ? Auth::user()->full_name : 'system');

        return  $invoice;
    }

    public function add_discount(Invoice $invoice, $discount)
    {
        try {
            $discount = $invoice->discount + $discount;
            $tax = (new TaxService)->calculateTax($invoice->amount - $discount);

            $invoice->update([
                'discount' => $discount,
                'tax' => $tax
            ]);
            return [
                'success' => true,
                'data' => $invoice
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete_billing(Invoice $invoice)
    {
        try {
            $directoryInvoice = 'invoices';
            if (Storage::disk('local')->exists($directoryInvoice . '/' . $invoice->invoice_path)) {
                Storage::disk('local')->delete($directoryInvoice . '/' . $invoice->invoice_path);
            }

            $invoice->forceDelete();
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

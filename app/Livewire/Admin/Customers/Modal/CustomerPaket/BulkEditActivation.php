<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\NotificationTrait;
use App\Services\CustomerPaketService;
use App\Models\Customers\CustomerPaket;

class BulkEditActivation extends Component
{
    use NotificationTrait;
    public $bulkEditActivationModal = false;
    public $customerPakets;
    public $input = [];

    #[On('bulk-edit-activation-paket-modal')]
    public function showBulkEditActivationModal($customerPaketSelected)
    {
        $this->reset();
        $this->bulkEditActivationModal = true;
        $customerPakets = CustomerPaket::query()
            ->whereIn('id', $customerPaketSelected)
            ->get();
        $this->customerPakets = $customerPakets;
        $customerPaketSelected = [];
    }

    public function edit_bulk_activation_paket(CustomerPaketService $customerPaketService)
    {

       // $subscribtionMode = Websystem::first()->subscription_mode;
       // $activationDate = Carbon::parse($this->input['activation_date'])->format('d');
        foreach ($this->customerPakets as $customerPaket) {
            $customerPaketService->editActivationDate($customerPaket, $this->input['activation_date']);
            /*
            $expiredDate = Carbon::now()->setDay((int)$activationDate)->add($customerPaket->getRenewalPeriod())->startOfDay();
            $startDate = Carbon::parse($expiredDate)->sub($customerPaket->getRenewalPeriod());

            switch ($subscribtionMode) {
                case 'pascabayar':
                    $nextBill = Carbon::now()->setDay((int)$activationDate)->startOfDay();
                    break;
                default:
                    $intervalDateInvoice = 7;

                    $lastPeriodeInvoice = $customerPaket->invoices()->orderBy('periode', 'desc')->first();
                    $lastPeriodeInvoice = Carbon::parse($lastPeriodeInvoice->periode);
                    $nextPeriodeInvoice = $lastPeriodeInvoice->add($customerPaket->getRenewalPeriod())->setDay((int)$activationDate)->startOfDay();
                    if ($nextPeriodeInvoice->isPast()) {
                        //Invoice on this periode not found
                        $nextBill =  $lastPeriodeInvoice->sub($customerPaket->getRenewalPeriod())->setDay((int)$activationDate)->startOfDay();
                    } else {
                        //Invoice on this periode found
                        $nextBill =  $lastPeriodeInvoice->sub($customerPaket->getRenewalPeriod())->setDay((int)$activationDate)->subDay()->startOfDay();
                    }
                    $nextBill = $nextBill->subDays($intervalDateInvoice);
            }


            $customerPaket->forceFill([
                'start_date' => $startDate,
                'activation_date' => $this->input['activation_date'] ?? $customerPaket->activation_date,
                'expired_date' => $expiredDate->subDay(),
                'next_billed_at' => $nextBill
            ])->save();

            $renewalPeriod = $this->customerPaket->getRenewalPeriod();
            $nextBilledAt = Carbon::parse($this->customerPaket->next_billed_at)->add($renewalPeriod);
        $invoicePeriod = Carbon::parse($nextBilledAt)->add($renewalPeriod)->startOfMonth();
            if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
                //$billingService->generateInvoice($customerPaket);
                $billingService->generateInvoice($this->customerPaket);
            } */
        }

        $this->dispatch('refresh-customer-paket-list');
        $this->dispatch('refresh-selected-customer-pakets');
        $this->bulkEditActivationModal = false;

        $this->success_notification('Success', trans('customer.paket.alert.edit-activation-paket-succesfully'));
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.bulk-edit-activation');
    }
}

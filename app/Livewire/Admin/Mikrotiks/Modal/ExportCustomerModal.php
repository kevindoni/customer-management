<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use App\Services\PaketService;
use App\Models\Servers\Mikrotik;
use App\Services\CustomerService;
use App\Support\CollectionPagination;
use Illuminate\Support\Facades\Validator;
use App\Services\Mikrotiks\MikrotikService;
use App\Jobs\Customers\ExportProfileToMikrotikJob;
use App\Jobs\Customers\ExportCustomerToMikrotikJob;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
//use App\Services\Mikrotiks\MikrotikPppProfilesService;
//use App\Services\Mikrotiks\MikrotikPppUserSecretService;


class ExportCustomerModal extends Component
{
    public $exportCustomerModal = false;
    public $input = [];
    public $mikrotiks;
    public $fromMikrotik;
    // public $toMikrotik;

    //private MikrotikPppUserSecretService $mikrotikPppUserSecretService;
    // private MikrotikPppProfilesService $pppProfileService;
    private PaketService $paketService;
    private CustomerService $customerService;
    private MikrotikService $mikrotikService;

    public $maxSecret = 0;
    public $countDifferentUserSecret = 0;

    public $toMikrotik;

    public function __construct()
    {
        $this->mikrotikService = new MikrotikService;
        // $this->mikrotikPppUserSecretService = new MikrotikPppUserSecretService;
        // $this->pppProfileService = new MikrotikPppProfilesService;
        $this->customerService = new CustomerService();
        $this->paketService = new PaketService();
    }

    #[On('show-export-customer-modal')]
    public function showExportCustomerModal(Mikrotik $mikrotik)
    {
        $this->reset();
        $this->exportCustomerModal  = true;
        $this->fromMikrotik = $mikrotik;
        $this->mikrotiks = Mikrotik::whereDisabled('false')->orderBy('name')->get();
    }

    public function updatedInputSelectedServer($mikrotikId)
    {
        $this->toMikrotik = Mikrotik::find($mikrotikId);

        if ($this->toMikrotik) {
            $neededExportCustomers = $this->customerService->neededExportCustomers($this->fromMikrotik, $this->toMikrotik);
            $this->countDifferentUserSecret = count($neededExportCustomers);

            if (env('QUEUE_CONNECTION') == 'sync') {
                $neededExportCustomers =   (new CollectionPagination($neededExportCustomers))->collectionPaginate(Websystem::first()->max_process);
            }
            $this->maxSecret = count($neededExportCustomers);

        } else {
            $this->countDifferentUserSecret = 0;
            $this->maxSecret = 0;
        }
    }

    public function exportCustomer()
    {
        Validator::make($this->input, [
            'selectedServer' => ['required'],
        ])->validate();

        try {
            $this->customerService->exportCustomersToMikrotik($this->fromMikrotik, $this->toMikrotik);

            $message = trans('customer.alert.export-customer-in-progress', ['mikrotik' => $this->toMikrotik->name]);
            $this->notification(trans('customer.alert.export-customer'), $message, 'success');
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
        }
        $this->exportCustomerModal  = false;
    }

    public function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.export-customer-modal');
    }
}

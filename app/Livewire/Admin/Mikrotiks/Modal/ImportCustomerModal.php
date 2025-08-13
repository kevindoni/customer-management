<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\Servers\Mikrotik;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;
use App\Services\Mikrotiks\MikrotikService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
//use App\Services\Mikrotiks\MikrotikPppProfilesService;
//use App\Services\Mikrotiks\MikrotikPppUserSecretService;

class ImportCustomerModal extends Component
{
    public $importCustomerModal = false;
    public $input = [];
    // public $mikrotiks;
    // public $toMikrotik;
    public $mikrotik;

    public $differentUserSecret;

    //  private MikrotikService $mikrotikService;
    //private MikrotikPppUserSecretService $mikrotikPppUserSecretService;
    private CustomerService $customerService;

    public $maxSecret = 0;
    public $countDifferentUserSecret = 0;

    public function __construct()
    {
        // Initialize Product
        // $this->mikrotikPppUserSecretService = new MikrotikPppUserSecretService;
        $this->customerService = new CustomerService;
    }

    #[On('show-import-customer-modal')]
    public function showImportCustomerModal(Mikrotik $mikrotik)
    {
        $this->reset();
        $this->importCustomerModal  = true;
        $this->input['activation_date'] = Carbon::now();
        $this->input['renewal_period'] = 'monthly';
        $this->mikrotik = $mikrotik;
        try {
            $differentUserSecret = $this->customerService->neededUserSecrets($mikrotik);
            $this->countDifferentUserSecret = count($differentUserSecret);
            //dd($this->countDifferentUserSecret);
            $maxProcess = Websystem::first()->max_process;
            env('QUEUE_CONNECTION') == 'sync' ? (count($differentUserSecret) > $maxProcess ? $this->maxSecret = $maxProcess : $this->maxSecret = count($differentUserSecret)) : $this->maxSecret = count($differentUserSecret);
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
        }
    }

    /*
    public function updatedInputSelectedServer($value)
    {
        $this->fromMikrotik = Mikrotik::find($value);
        if ($this->fromMikrotik) {
           $differentUserSecret = $this->customerService->neededUserSecrets($this->fromMikrotik, $this->toMikrotik);
           $this->countDifferentUserSecret = count($differentUserSecret);
            //dd($this->countDifferentUserSecret);
            $maxProcess = Websystem::first()->max_process;
           env('QUEUE_CONNECTION') == 'sync' ? (count($differentUserSecret) > $maxProcess ? $this->maxSecret = $maxProcess : $this->maxSecret = count($differentUserSecret)) : $this->maxSecret = count($differentUserSecret);

        } else {
            $this->countDifferentUserSecret = 0;
            $this->maxSecret = 0;
        }
    }
*/
    public function importCustomer()
    {
        try {
            $importCustomerStatus = $this->customerService->importCustomersFromMikrotik($this->mikrotik, $this->input);
            if ($importCustomerStatus['success']) {
                $status = 'success';
                $message = trans('mikrotik.alert.import-customers-in-progress', ['countCustomer' => $this->maxSecret]);
            } else {
                $status = 'error';
                $message = $importCustomerStatus['message'];
            }

            $this->notification(trans('mikrotik.alert.import-customers'), $message, $status);
            $this->importCustomerModal  = false;
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
            $this->importCustomerModal  = false;
        }
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
        return view('livewire.admin.mikrotiks.modal.import-customer-modal');
    }
}

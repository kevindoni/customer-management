<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use App\Services\CustomerService;
use App\Support\CollectionPagination;
use Illuminate\Support\Facades\Cache;

use App\Services\Mikrotiks\MikrotikService;
use App\Services\Mikrotiks\MikrotikPppService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
//use App\Services\Mikrotiks\MikrotikPppUserSecretService;

class MikrotikUserSecrets extends Component
{
    use WithPagination;

    public Mikrotik $mikrotik;
    public  $perPage = 20;
    public $online;
    public $countUserSecretNonCustomer = 0;
    public $countProfileNonPaket = 0;
    //public $userSecrets;

    public $searchByImported;

    private MikrotikService $mikrotikService;
    private MikrotikPppService $mikrotikPppService;
    //private MikrotikPppUserSecretService $mikrotikPppUserSecretService;
    private customerService $customerService;


    public function __construct()
    {
        $this->mikrotikService = new MikrotikService;
        $this->mikrotikPppService = new MikrotikPppService;
        //$this->mikrotikPppUserSecretService = new MikrotikPppUserSecretService;
        $this->customerService = new CustomerService;
    }

    public function mount(Mikrotik $mikrotik)
    {
        // dd($this->searchByImported);
        $this->mikrotik = $mikrotik;
    }

    public function updatedSearchByImported($value) {}

    public function render()
    {
        $userSecrets = collect([]);
        try {
            /* $userSecrets = $this->mikrotikService->getAllUserSecrets($this->mikrotik);
            if ($this->searchByImported == 'imported') {
                $userSecrets = $this->mikrotikPppUserSecretService->getDifferentUsersSecretInCustomerManagement($this->mikrotik, true);
            } else if ($this->searchByImported == 'not-imported') {
                $userSecrets = $this->mikrotikPppUserSecretService->getDifferentUsersSecretInCustomerManagement($this->mikrotik);
            }*/
            $userSecrets = $this->mikrotikService->getAllUserSecrets($this->mikrotik);
            if ($this->searchByImported == 'imported') {
                $userSecrets = $this->customerService->neededUserSecrets($this->mikrotik, true);
            } else if ($this->searchByImported == 'not-imported') {
                $userSecrets = $this->customerService->neededUserSecrets($this->mikrotik);
            }
            $this->online = true;
        } catch (\Exception $e) {
            $this->online = false;
            LivewireAlert::title('Failed')
                ->text($e->getMessage())
                ->position('center')
                // ->toast()
                ->status('error')
                ->show();
        }

        $secrets = (new CollectionPagination($userSecrets))->collectionPaginate($this->perPage);
        return view('livewire.admin.mikrotiks.view.mikrotik-user-secrets', compact('secrets'));
    }
}

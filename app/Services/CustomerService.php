<?php

namespace App\Services;

use App\Models\User;
use App\Models\Websystem;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use App\Models\Pakets\Paket;
use App\Models\Pakets\PppType;
use App\Services\PaketService;
use Illuminate\Support\Carbon;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\DB;
use App\Models\Pakets\PaketProfile;
use Illuminate\Support\Facades\Log;
use App\Support\CollectionPagination;
use App\Models\Customers\UserCustomer;
use App\Services\Billings\BillingService;
use App\Livewire\Actions\Users\UserAction;
use App\Models\Customers\CustomerPppPaket;
use App\Services\Mikrotiks\MikrotikService;
use App\Livewire\Actions\Pakets\PaketAction;
use App\Jobs\Pakets\ExportPaketToMikrotikJob;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Jobs\Pakets\ImportPaketFromMikrotikJob;
use App\Http\Resources\Mikrotik\ProfileResource;
use App\Jobs\Customers\ExportCustomerToMikrotikJob;
use App\Jobs\Customers\ImportCustomerFromMikrotikJob;
use App\Livewire\Actions\Customers\CustomerPaketAction;

class CustomerService
{
    private MikrotikService $mikrotikService;
    private PaketService $paketService;
    private CustomerPaketService $customerPaketService;
    private MikrotikPppService $mikrotikPppService;
    private BillingService $billingService;
    private UserAction $userAction;
    private CustomerPaketAction $customerPaketAction;

    public function __construct()
    {
        // Initialize Product
        $this->mikrotikService = new MikrotikService;
        $this->billingService = new BillingService;
        $this->mikrotikPppService = new MikrotikPppService;
        $this->userAction = new UserAction;
        $this->customerPaketAction = new CustomerPaketAction;
        $this->paketService = new PaketService();
        $this->customerPaketService = new CustomerPaketService;
    }

    public function neededUserSecrets(Mikrotik $mikrotik, $inCustomerManagement = false)
    {
        $mikrotikSecrets = $this->mikrotikService->getAllUserSecrets($mikrotik);
        $customerPppPakets = CustomerPppPaket::whereHas(
            'customer_paket',
            function ($builder) use ($mikrotik) {
                $builder->whereHas(
                    'paket',
                    function ($builder) use ($mikrotik) {
                        $builder->where('mikrotik_id', $mikrotik->id);
                    }
                );
            }
        )->pluck('username');

        return $inCustomerManagement ? collect($mikrotikSecrets)->whereIn('name', $customerPppPakets) : collect($mikrotikSecrets)->whereNotIn('name', $customerPppPakets);
    }

    public function importCustomersFromMikrotik(Mikrotik $mikrotik, $input)
    {
        $neededUserSecrets = $this->neededUserSecrets($mikrotik);
        $secretProfiles = collect($neededUserSecrets)->unique('profile');

        //Create paket if profile doesnt have paket and needed to import customer
        // $paketProfileDontHavePaket = PaketProfile::whereDoesntHave('pakets')->pluck('profile_name');
        $paketProfileDontHavePaket  = PaketProfile::whereDoesntHave(
            'pakets',
            function ($pakets) use ($mikrotik) {
                $pakets->where('mikrotik_id', $mikrotik->id);
            }
        )->pluck('profile_name');
        $secretProfileNeededPakets = $secretProfiles->whereIn('profile', $paketProfileDontHavePaket)->pluck('profile');

        $mikrotikProfiles = $this->mikrotikService->getPppProfiles($mikrotik);
        $profileNeededPakets = collect($mikrotikProfiles)->whereIn('name', $secretProfileNeededPakets);

        foreach ($profileNeededPakets as $profileNeededPaket) {
            $profileNeededPaket = new ProfileResource($profileNeededPaket);
            $paketProfile = PaketProfile::whereProfileName($profileNeededPaket['name'])->first();
            $paket = (new PaketAction())->importPaket($mikrotik->id, $paketProfile, $profileNeededPaket['comment'] ?? 0);
            $paket->forceFill([
                'mikrotik_ppp_profile_id' => $profileNeededPaket['.id']
            ])->save();
        }

        //Import Profile needed before import customer
        $paketProfiles = PaketProfile::pluck('profile_name');
        $neededCreateProfiles = $secretProfiles->whereNotIn('profile', $paketProfiles)->pluck('profile');
        $neededCreateProfiles = collect($mikrotikProfiles)->whereIn('name', $neededCreateProfiles);

        foreach ($neededCreateProfiles as $neededCreateProfile) {
            dispatch(new ImportPaketFromMikrotikJob($mikrotik, $neededCreateProfile))->onQueue('default');
        }

        //Import secrets from mikrotik to customer management
        //To reduce server work processes, secret imports are limited according to settings if the queue connection is in sync mode.
        if (env('QUEUE_CONNECTION') == 'sync') {
            $neededUserSecrets =   (new CollectionPagination($neededUserSecrets))->collectionPaginate(Websystem::first()->max_process);
        }
        // dd($neededUserSecrets);
        foreach ($neededUserSecrets as $neededUserSecret) {
            dispatch(new ImportCustomerFromMikrotikJob($mikrotik, $neededUserSecret, $input))->onQueue('default');
        }

        return [
            'success' => true
        ];
    }

    public function neededExportCustomers($fromMikrotik, Mikrotik $toMikrotik, $inMikrotik = false)
    {
        $mikrotikSecrets = $this->mikrotikService->getAllUserSecrets($toMikrotik);
        $mikrotikSecrets = collect($mikrotikSecrets)->pluck('name');
        $customerPppPakets = CustomerPppPaket::whereHas(
            'customer_paket',
            function ($builder) use ($fromMikrotik) {
                $builder->whereHas(
                    'paket',
                    function ($builder) use ($fromMikrotik) {
                        $builder->where('mikrotik_id', $fromMikrotik->id);
                    }
                );
            }
        )->get();

        return $inMikrotik ? collect($customerPppPakets)->whereIn('username', $mikrotikSecrets) : collect($customerPppPakets)->whereNotIn('username', $mikrotikSecrets);
    }



    public function exportCustomersToMikrotik($fromMikrotik, Mikrotik $toMikrotik)
    {

        //export profile the required profile secret
        $neededExportProfiles = $this->paketService->neededExportProfiles($fromMikrotik, $toMikrotik);
        $neededExportCustomers = $this->neededExportCustomers($fromMikrotik, $toMikrotik);
        $pakets = Paket::whereMikrotikId($fromMikrotik->id)
            ->whereHas('customer_ppp_pakets')
            ->pluck('paket_profile_id');

        $neededExportProfiles = $neededExportProfiles->whereIn('id', $pakets);


        if (env('QUEUE_CONNECTION') == 'sync') {
            $neededExportProfiles =   (new CollectionPagination($neededExportProfiles))->collectionPaginate(Websystem::first()->max_process);
        }
        foreach ($neededExportProfiles as $neededExportProfile) {
            dispatch(new ExportPaketToMikrotikJob($toMikrotik, $neededExportProfile))->onQueue('default');
        }

        //Export Customer from Customer Management to mikrotik
        $neededExportCustomers = $this->neededExportCustomers($fromMikrotik, $toMikrotik);
        //To reduce server work processes, secret imports are limited according to settings if the queue connection is in sync mode.
        if (env('QUEUE_CONNECTION') == 'sync') {
            $neededExportCustomers =   (new CollectionPagination($neededExportCustomers))->collectionPaginate(Websystem::first()->max_process);
        }
        foreach ($neededExportCustomers as $neededExportCustomer) {
            dispatch(new ExportCustomerToMikrotikJob($toMikrotik, $neededExportCustomer))->onQueue('default');
        }
    }

    public function deleteCustomer(User $user, $deleteOnMikrotik)
    {
       // $customerPakets = $user->customer_pakets()->whereNotNull('activation_date')->withTrashed()->get();
       $customerPakets = $user->customer_pakets()->whereNotNull('activation_date')->get();
       DB::beginTransaction();
        try {
            foreach ($customerPakets as $customerPaket) {
                if ($deleteOnMikrotik) {
                    $this->customerPaketService->delete_user_on_mikrotik($customerPaket);
                } else {
                    $this->customerPaketService->disableCustomerPaketOnMikrotik($customerPaket, 'true');
                }
            }
            (new UserAction())->delete($user);
            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            try {
                foreach ($customerPakets as $customerPaket) {
                    $this->customerPaketService->restoreCustomerPaketOnMikrotik($customerPaket, $deleteOnMikrotik);
                }
            } catch (\Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function restoreCustomer(User $user, $restoreOnMikrotik = false)
    {
       // dd($user);
        DB::beginTransaction();
        $user->restore();
        $customerPakets = $user->customer_pakets()->whereNotNull('activation_date')->get();

        try {
            foreach ($customerPakets as $customerPaket) {
                $this->customerPaketService->restoreCustomerPaketOnMikrotik($customerPaket, $restoreOnMikrotik);
            }
            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            try {
                foreach ($customerPakets as $customerPaket) {
                    if ($restoreOnMikrotik) {
                        $this->customerPaketService->delete_user_on_mikrotik($customerPaket);
                    } else {
                         $this->customerPaketService->disableCustomerPaketOnMikrotik($customerPaket, 'true');
                    }
                }
            } catch (\Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

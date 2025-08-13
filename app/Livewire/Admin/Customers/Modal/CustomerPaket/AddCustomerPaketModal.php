<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Models\Pakets\PppType;
use App\Models\Servers\Mikrotik;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Pakets\InternetService;
use App\Services\CustomerPaketService;
use App\Services\Mikrotiks\MikrotikService;
use App\Http\Requests\Customers\AddCustomerPaketRequest;
use App\Http\Requests\Customers\AddAddressCustomerPaketRequest;

class AddCustomerPaketModal extends Component
{

    use NotificationTrait;

    public $addCustomerPaketModal = false;
    public $input = [];
    public $user;
    public $currentStep = 1;

    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $address = false;
    public $checkbox_installation_address = false;
    public $checkbox_billing_address = false;

    public $internet_services, $ppp_services, $mikrotik_interfaces;
    public $selectedServer = null, $selectedPaket = null, $selectedPppService = null, $selectedMikrotikInterface = null, $selectedInternetService = null;


    #[On('add-customer-paket-modal')]
    public function showAddCustomerPaketModal(User $user)
    {
        $this->resetErrorBag();
        $this->addCustomerPaketModal = true;
        $this->user = $user;
        $this->internet_services = collect();
        $this->ppp_services = collect();
        $this->mikrotik_interfaces = collect();
    }

    public function addressInstallation(AddCustomerPaketRequest $request)
    {
        $this->input['selectedServer'] = $this->selectedServer;
        $request->validate(
            $this->input
        );
        $this->currentStep = 2;
    }

    public function addressBilling(AddAddressCustomerPaketRequest $addressRequest)
    {
        if ($this->checkbox_installation_address) {
            $addressRequest->validate(
                $this->input
            );
            $this->input['installation_country'] = $this->input['country'];
            $this->input['installation_province'] = $this->input['province'];
            $this->input['installation_city'] = $this->input['city'];
            $this->input['installation_district'] = $this->input['district'];
            $this->input['installation_subdistrict'] = $this->input['subdistrict'];
            $this->input['installation_address'] = $this->input['address'];
            $this->input['installation_phone'] = $this->input['phone'] ?? null;
        } else {
            $this->input['installation_country'] = $this->user->user_address->country;
            $this->input['installation_province'] = $this->user->user_address->province;
            $this->input['installation_city'] = $this->user->user_address->city;
            $this->input['installation_district'] = $this->user->user_address->district;
            $this->input['installation_subdistrict'] = $this->user->user_address->subdistrict;
            $this->input['installation_address'] = $this->user->user_address->address;
            $this->input['installation_phone'] = $this->user->user_address->phone;
        }
        $this->currentStep = 3;
    }

    public function addCustomerPaket(AddAddressCustomerPaketRequest $addressRequest, CustomerPaketService $customerPaketService)
    {
        $this->resetErrorBag();
        if ($this->checkbox_billing_address) {
            $addressRequest->validate(
                $this->input
            );
            $this->input['billing_country'] = $this->input['country'];
            $this->input['billing_province'] = $this->input['province'];
            $this->input['billing_city'] = $this->input['city'];
            $this->input['billing_district'] = $this->input['district'];
            $this->input['billing_subdistrict'] = $this->input['subdistrict'];
            $this->input['billing_address'] = $this->input['address'];
            $this->input['billing_phone'] = $this->input['phone'] ?? null;
        } else {
            $this->input['billing_country'] = $this->user->user_address->country;
            $this->input['billing_province'] = $this->user->user_address->province;
            $this->input['billing_city'] = $this->user->user_address->city;
            $this->input['billing_district'] = $this->user->user_address->district;
            $this->input['billing_subdistrict'] = $this->user->user_address->subdistrict;
            $this->input['billing_address'] = $this->user->user_address->address;
            $this->input['billing_phone'] = $this->user->user_address->phone;
        }
        DB::beginTransaction();
        try {
            $customerPaket = $customerPaketService->addCustomerPaket($this->user, $this->input);
            DB::commit();

            $this->dispatch('refresh-customer-list');
            $this->closeAddCustomerPaketModal();
            $this->success_notification(trans('customer.paket.alert.success'), trans('customer.paket.alert.add-customer-paket-successfully', ['customer' => $this->user->full_name, 'paket' => $customerPaket->paket->name]));
            $this->reset();
        } catch (Exception $e) {
            DB::rollBack();
            $this->error_notification(trans('customer.paket.alert.failed'), $e->getMessage());
        }
    }

    public function closeAddCustomerPaketModal()
    {
        $this->addCustomerPaketModal = false;
    }
    public function back($step)
    {
        $this->currentStep = $step;
    }
    /**
     * Update dropdown
     */
    public function updatedSelectedServer($server)
    {
        $this->resetErrorBag();
        $this->input['selectedPaket'] = '';
        $this->input['selectedInternetService'] = '';
        $this->input['selectedPppService'] = '';
        $this->selectedInternetService = null;
        $this->selectedPaket = null;
    }


    public function updatedInputSelectedPaket($paket)
    {
        if (!is_null($paket)) {
            $this->resetErrorBag('selectedPaket');
            $this->selectedPaket = Paket::where('id', $paket)->first();
            $this->internet_services = InternetService::all();
        }
    }

    public function updatedInputSelectedInternetService($internet_service_value)
    {
        $this->input['ip_address'] = '';
        $this->resetErrorBag();
        if ($internet_service_value) {
            $this->resetErrorBag('selectedInternetService');
            $this->selectedInternetService = $internet_service_value;
            if ($this->selectedInternetService == 'ppp') {
                $this->ppp_services = PppType::all();
            } else if ($this->selectedInternetService == 'ip_static') {
                //get interface mikrotik
                $mikrotik = Mikrotik::where('id', $this->selectedServer)->first();
                try {
                    $this->mikrotik_interfaces = (new MikrotikService())->mikrotikEtherInterface($mikrotik);
                } catch (\Exception $e) {
                    $this->mikrotik_interfaces = null;
                }
            }
        } else {
            $this->selectedInternetService = '';
        }
    }


    public function updatedInputSelectedPppService($pppService)
    {
        if (!is_null($pppService)) {
            $this->resetErrorBag('selectedPppService');
        }
    }



    public function updatedInputCountry($country)
    {
        if ($country == 'id') {
            $this->provinces =  Cache::remember('provinces-' . $country, now()->addMonth(), function () {
                return  $this->getAddress('https://alamat.thecloudalert.com/api/provinsi/get/');
            });
        }
    }

    public function updatedInputProvince($province)
    {
        $province = json_decode($province, true);
        $this->cities = Cache::remember('cities-' . $province['id'], now()->addMonth(), function () use ($province) {
            return  $this->getAddress('https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=' . $province['id']);
        });
        $this->districts = null;
        $this->subDistricts = null;
    }

    public function updatedInputCity($city)
    {
        $city = json_decode($city, true);
        $this->districts =  Cache::remember('districts-' . $city['id'], now()->addMonth(), function () use ($city) {
            return  $this->getAddress('https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=' . $city['id']);
        });
        $this->subDistricts = null;
    }

    public function updatedInputDistrict($district)
    {
        $district = json_decode($district, true);
        $this->subDistricts =  Cache::remember('subdistricts-' . $district['id'], now()->addMonth(), function () use ($district) {
            return   $this->getAddress('https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=' . $district['id']);
        });
    }

    public function getAddress($url)
    {
        $data =  file_get_contents($url);
        return json_decode($data, true);
    }
    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.add-customer-paket-modal');
    }
}

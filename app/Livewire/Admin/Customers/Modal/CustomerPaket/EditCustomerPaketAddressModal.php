<?php

namespace App\Livewire\Admin\Customers\Modal\CustomerPaket;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Customers\CustomerPaket;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Livewire\Actions\Customers\CustomerPaketAddressAction;
use App\Http\Requests\Customers\AddAddressCustomerPaketRequest;
use App\Http\Requests\Customers\UpdateAddressCustomerPaketRequest;


class EditCustomerPaketAddressModal extends Component
{

    public $editCustomerPaketAddressModal = false;
    public $input = [];
    public $customerPaket;
    public $currentStep = 1;
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $address = false;


    // public $checkbox_address_installation = false;
    // public $checkbox_address_billing = false;


    #[On('edit-customer-paket-address-modal')]
    public function showEditCustomerPaketModal(CustomerPaket $customerPaket)
    {
        $this->resetErrorBag();
        $this->editCustomerPaketAddressModal = true;
        $this->customerPaket = $customerPaket;

        if ($this->customerPaket->customer_installation_address->country) {
            $this->updatedInputCountry($this->input['country'] = $this->customerPaket->customer_installation_address->country);
            $this->updatedInputProvince($this->input['province'] = $this->customerPaket->customer_installation_address->province);
            $this->updatedInputCity($this->input['city'] = $this->customerPaket->customer_installation_address->city);
            $this->updatedInputDistrict($this->input['district'] = $this->customerPaket->customer_installation_address->district);
            $this->input['subdistrict'] = $this->customerPaket->customer_installation_address->subdistrict;
            $this->input['address'] = $this->customerPaket->customer_installation_address->address;
            $this->input['phone'] = $this->customerPaket->customer_installation_address->phone;
        } else {
            $this->updatedInputCountry($this->input['country'] = $this->customerPaket->user->user_address->country);
            if ($this->customerPaket->user->user_address->province)
                $this->updatedInputProvince($this->input['province'] = $this->customerPaket->user->user_address->province);
            if ($this->customerPaket->user->user_address->city)
                $this->updatedInputCity($this->input['city'] = $this->customerPaket->user->user_address->city);
            if ($this->customerPaket->user->user_address->district)
                $this->updatedInputDistrict($this->input['district'] = $this->customerPaket->user->user_address->district);
            if ($this->customerPaket->user->user_address->subdistrict)
                $this->input['subdistrict'] = $this->customerPaket->user->user_address->subdistrict;
            $this->input['address'] = $this->customerPaket->user->user_address->address;
            $this->input['phone'] = $this->customerPaket->user->user_address->phone;
        }
        $this->currentStep = 1;
    }

    public function updateInstallationAddress(UpdateAddressCustomerPaketRequest $updateAddressRequest)
    {

        $updateAddressRequest->validate(
            $this->customerPaket,
            $this->input
        );
        $this->input['installation_country'] = $this->input['country'];
        $this->input['installation_province'] = $this->input['province'];
        $this->input['installation_city'] = $this->input['city'];
        $this->input['installation_district'] = $this->input['district'];
        $this->input['installation_subdistrict'] = $this->input['subdistrict'];
        $this->input['installation_address'] = $this->input['address'];
        $this->input['installation_phone'] = $this->input['phone'];

        if ($this->customerPaket->customer_installation_address->country) {
            $this->updatedInputCountry($this->input['country'] = $this->customerPaket->customer_billing_address->country);
            $this->updatedInputProvince($this->input['province'] = $this->customerPaket->customer_billing_address->province);
            $this->updatedInputCity($this->input['city'] = $this->customerPaket->customer_billing_address->city);
            $this->updatedInputDistrict($this->input['district'] = $this->customerPaket->customer_billing_address->district);
            $this->input['subdistrict'] = $this->customerPaket->customer_billing_address->subdistrict;
            $this->input['address'] = $this->customerPaket->customer_billing_address->address;
            $this->input['phone'] = $this->customerPaket->customer_billing_address->phone;
        } else {
            $this->updatedInputCountry($this->input['country'] = $this->customerPaket->user->user_address->country);
            if ($this->customerPaket->user->user_address->province)
                $this->updatedInputProvince($this->input['province'] = $this->customerPaket->user->user_address->province);
            if ($this->customerPaket->user->user_address->city)
                $this->updatedInputCity($this->input['city'] = $this->customerPaket->user->user_address->city);
            if ($this->customerPaket->user->user_address->district)
                $this->updatedInputDistrict($this->input['district'] = $this->customerPaket->user->user_address->district);
            if ($this->customerPaket->user->user_address->subdistrict)
                $this->input['subdistrict'] = $this->customerPaket->user->user_address->subdistrict;
            $this->input['address'] = $this->customerPaket->user->user_address->address;
            $this->input['phone'] = $this->customerPaket->user->user_address->phone;
        }



        $this->currentStep = 2;
    }

    public function updateCustomerPaketAddress(UpdateAddressCustomerPaketRequest $updateAddressRequest)
    {
        $updateAddressRequest->validate(
            $this->customerPaket,
            $this->input
        );
        $this->input['billing_country'] = $this->input['country'];
        $this->input['billing_province'] = $this->input['province'];
        $this->input['billing_city'] = $this->input['city'];
        $this->input['billing_district'] = $this->input['district'];
        $this->input['billing_subdistrict'] = $this->input['subdistrict'];
        $this->input['billing_address'] = $this->input['address'];
        $this->input['billing_phone'] = $this->input['phone'];

        DB::beginTransaction();
        (new CustomerPaketAddressAction())->updateInstallationAddress($this->customerPaket->customer_installation_address, $this->input);
        (new CustomerPaketAddressAction())->updateBillingAddress($this->customerPaket->customer_billing_address, $this->input);
        DB::commit();
        $this->closeModal();
        LivewireAlert::title(trans('customer.alert.success'))
            ->text(trans('customer.alert.message-update-success'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
    }

    public function closeModal()
    {
        $this->editCustomerPaketAddressModal = false;
        $this->reset();
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
        //  $this->zipCode = $this->getAddress('https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=1&d_kecamatan_id=' . $district['id']);
    }

    public function getAddress($url)
    {
        $data =  file_get_contents($url);
        return json_decode($data, true);
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.customer-paket.edit-customer-paket-address-modal');
    }
}

<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Customers\UpdateUserStepTwoRequest;
use App\Http\Requests\Customers\UpdateUserStepThreeRequest;
use App\Http\Requests\Customers\CreateCustomerStepOneRequest;

class EditCustomerModal extends Component
{
    public $editCustomerModal = false;

    public $user;

    public $input = [];
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $address = false;
    public $currentStep = 1;
    public $changeInstallationAddress = false;
    public $changeBillingAddress = false;

    #[On('edit-customer-modal')]
    public function showDisableCustomerModal(User $user)
    {
         $this->currentStep = 0;
         $this->reset();
        $this->editCustomerModal = true;
        $this->user = $user;
        $this->input['first_name'] = $this->user->first_name;
        $this->input['last_name'] = $this->user->last_name;
        $this->input['gender'] = $this->user->user_customer->gender ?? '';
        $this->input['dob'] = $this->user->user_customer->dob ? Carbon::parse($this->user->user_customer->dob)->format('Y-m-d') : null;
    }

    public function firstStepSubmit()
    {
       // dd($this->input['dob']);
        (new CreateCustomerStepOneRequest())->validate($this->input);
        $this->currentStep = 2;
        if ($this->user->user_address->country) {
            $this->updatedInputCountry($this->input['country'] = $this->user->user_address->country);
            $this->updatedInputProvince($this->input['province'] = $this->user->user_address->province);
            $this->updatedInputCity($this->input['city'] = $this->user->user_address->city);
            $this->updatedInputDistrict($this->input['district'] = $this->user->user_address->district);
            $this->input['subdistrict'] = $this->user->user_address->subdistrict;
            $this->input['address'] = $this->user->user_address->address;
            $this->input['phone'] = $this->user->user_address->phone;
        }
    }

    public function secondStepSubmit()
    {
        // $this->input['phone'] =  $this->internationalPhoneNumberFormat($this->input['phone']);
        (new UpdateUserStepTwoRequest())->validate($this->user->user_address, $this->input);
        $this->input['role'] = 'customer';
        $this->input['email'] = $this->user->email;
        $this->currentStep = 3;
    }

    public function updateCustomerInformation()
    {
       // dd('test');
       // (new CreateCustomerStepOneRequest())->validate($this->input);
      //  (new UpdateUserStepTwoRequest())->validate($this->user->user_address, $this->input);
        (new UpdateUserStepThreeRequest())->validate($this->user, $this->input);
        DB::beginTransaction();
        (new UserService())->updateUserCustomer($this->user, $this->input, $this->changeInstallationAddress, $this->changeBillingAddress);
        DB::commit();

        $this->dispatch('refresh-customer-list');
        LivewireAlert::title('Update Success!')
            ->text('Update customer successfully')
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
        $this->closeModal();
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function closeModal()
    {
        $this->editCustomerModal = false;
        $this->dispatch('refresh-customer-list');
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
       // dd($province);
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
        return view('livewire.admin.customers.modal.edit-customer-modal');
    }
}

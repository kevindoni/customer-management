<?php

namespace App\Livewire\Admin\Customers\Modal;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Customers\CreateCustomerStepOneRequest;
use App\Http\Requests\Customers\CreateCustomerStepTwoRequest;
use App\Http\Requests\Customers\CreateCustomerStepThreeRequest;

class CreateNewCustomerModal extends Component
{
   // use StandardPhoneNumber;
    public $input = [];
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $address = false;
    public $currentStep = 1;

    /**
     * Add or Edit User Modal
     */
   // #[On('add-customer-modal')]
  //  public function showAddCustomerModal()
  //  {
   //     $this->addCustomerModal = true;
   // }

    public function firstStepSubmit()
    {
        (new CreateCustomerStepOneRequest())->validate($this->input);
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
     //  $this->input['phone'] =  $this->internationalPhoneNumberFormat($this->input['phone']);
        $this->input['role']= 'customer';
       // dd($this->input);
        (new CreateCustomerStepTwoRequest())->validate($this->input);
        $this->currentStep = 3;
    }

    public function addCustomer()
    {
        (new CreateCustomerStepThreeRequest())->validate($this->input);
        DB::beginTransaction();
        $user =  (new UserService())->addUserCustomer($this->input);
        DB::commit();

        $this->dispatch('refresh-customer-list');
        LivewireAlert::title(trans('customer.alert.customer-created'))
            ->text(trans('customer.alert.customer-created-successfully', ['user' => $user->first_name]))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
        $this->closeAddCustomerModal();
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }
    public function updatedInputCountry($country)
    {
        $this->provinces =  Cache::remember('provinces-' . $country, now()->addMonth(), function () {
            return  $this->getAddress('https://alamat.thecloudalert.com/api/provinsi/get/');
        });
    }

    public function updatedInputProvince($province)
    {
        $province = json_decode($province, true);
        $this->cities = Cache::remember('cities-' . $province['id'], now()->addMonth(), function () use ($province) {
            return  $this->getAddress('https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=' . $province['id']);
        });
    }

    public function updatedInputCity($city)
    {
        $city = json_decode($city, true);
        $this->districts =  Cache::remember('districts-' . $city['id'],now()->addMonth(), function () use ($city) {
            return  $this->getAddress('https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=' . $city['id']);
        });
    }

    public function updatedInputDistrict($district)
    {
        $district = json_decode($district, true);
        $this->subDistricts =  Cache::remember('subdistricts-' . $district['id'],now()->addMonth(), function () use ($district) {
            return   $this->getAddress('https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=' . $district['id']);
        });
    }

    public function updatedInputSubdistrict()
    {
        $this->address = true;
    }

    public function getAddress($url)
    {
        $data =  file_get_contents($url);
        return json_decode($data, true);
    }


    /**
     * Close add user modal
     */
    #[On('close-add-customer-modal')]
    public function closeAddCustomerModal()
    {
        $this->currentStep = 0;
        Flux::modal('add-customer-modal')->close();
     //   $this->addCustomerModal = false;
        $this->reset();
    }


    public function render()
    {
        return view('livewire.admin.customers.modal.create-new-customer-modal');
    }
}

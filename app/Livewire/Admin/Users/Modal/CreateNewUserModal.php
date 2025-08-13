<?php

namespace App\Livewire\Admin\Users\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Livewire\Actions\UserAction;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Customers\CreateCustomerStepOneRequest;
use App\Http\Requests\Customers\CreateCustomerStepTwoRequest;
use App\Http\Requests\Customers\CreateCustomerStepThreeRequest;

class CreateNewUserModal extends Component
{
    public $addUserModal = false;
    public $userSelect;
    public $input = [];
    // public string $firstname;
    // public string $lastname;
    // public string $address;
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $address = false;
    public $currentStep = 1;

    /**
     * Add or Edit User Modal
     */
    #[On('add-user-modal')]
    public function showAddUserModal()
    {
        $this->addUserModal = true;
    }

    public function firstStepSubmit()
    {
        (new CreateCustomerStepOneRequest())->validate($this->input);
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $this->input['phone'] =  $this->input['phone'] ?? null;
        (new CreateCustomerStepTwoRequest())->validate($this->input);
        $this->currentStep = 3;
    }

    public function addUser()
    {
        (new CreateCustomerStepThreeRequest())->validate($this->input);
        DB::beginTransaction();
       $user = (new UserService())->addUserAdmin($this->input);
        DB::commit();

        LivewireAlert::title(trans('user.alert.user-created'))
        ->text(trans('user.alert.user-created-successfully', ['user' => $user->full_name]))
        ->position('top-end')
        ->toast()
        ->status('success')
        ->show();
        $this->closeAddUserModal();
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
        $this->districts =  Cache::remember('districts-' . $city['id'], now()->addMonth(), function () use ($city) {
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
    #[On('close-add-user-modal')]
    public function closeAddUserModal()
    {
        $this->dispatch('refresh-user-list');
        $this->currentStep = 0;
        $this->addUserModal = false;
        $this->reset();
    }


    public function render()
    {
        return view('livewire.admin.users.modal.create-new-user-modal');
    }
}

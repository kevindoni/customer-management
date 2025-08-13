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
use App\Http\Requests\Customers\UpdateUserStepTwoRequest;
use App\Http\Requests\Customers\UpdateUserStepThreeRequest;
use App\Http\Requests\Customers\CreateCustomerStepOneRequest;

class EditUserModal extends Component
{
    public $editUserModal = false;
    public $userSelect;
    public $input = [];
    // public string $firstname;
    // public string $lastname;
    // public string $address;
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    // public $address = false;
    public $currentStep = 1;

    /**
     * Add or Edit User Modal
     */
    #[On('edit-user-modal')]
    public function showEditUserModal(User $user)
    {
        $this->editUserModal = true;
        $this->input['first_name'] = $user->first_name;
        $this->input['last_name'] = $user->last_name;
        $this->input['gender'] = $user->user_admin->gender;
        $this->input['dob'] =  Carbon::parse($user->user_admin->dob)->format('Y-m-d');
        $this->userSelect = $user;
    }

    public function firstStepSubmit()
    {
        (new CreateCustomerStepOneRequest())->validate($this->input);
        $this->currentStep = 2;
        if ($this->userSelect->user_address->country) {
            $this->updatedInputCountry($this->input['country'] = $this->userSelect->user_address->country);
        }
        if ($this->userSelect->user_address->province) {
            $this->updatedInputProvince($this->input['province'] = $this->userSelect->user_address->province);
            $this->updatedInputCity($this->input['city'] = $this->userSelect->user_address->city);
            $this->updatedInputDistrict($this->input['district'] = $this->userSelect->user_address->district);
        }

        $this->input['subdistrict'] = $this->userSelect->user_address->subdistrict;
        $this->input['address'] = $this->userSelect->user_address->address;
        $this->input['phone'] = $this->userSelect->user_address->phone;
    }

    public function secondStepSubmit()
    {
        (new UpdateUserStepTwoRequest())->validate($this->userSelect->user_address, $this->input);
        $this->input['email'] = $this->userSelect->email;
        $this->input['role'] = $this->userSelect->getRoleNames()->first();
        $this->currentStep = 3;
    }

    public function updateUserInformation()
    {

        (new UpdateUserStepThreeRequest())->validate($this->userSelect, $this->input);

        $userRoles = $this->userSelect->getRoleNames();

        $thisUserAdmin = false;
        foreach($userRoles as $userRole){
            if ($userRole === "admin"){
                $thisUserAdmin = true;
            }
        }

        $countAdminSafe = 1;
        $adminCount = User::with('roles')->get()->filter(
            fn($user) => $user->roles->where('name', 'admin')->toArray()
        )->count();

      //  $users = User::role('admin')->get();


        if ($adminCount <=  $countAdminSafe && $this->input['role'] != 'admin' && $thisUserAdmin) {
            $title = trans('user.alert.failed');
            $text = trans('user.alert.text-failed-change-admin', ['count' =>  $countAdminSafe]);
            $status = 'error';
        } else {
            DB::beginTransaction();
            (new UserService())->updateUserAdmin($this->userSelect, $this->input);
            DB::commit();
            $this->dispatch('refresh-user-list');
            $title = trans('user.alert.user-updated');
            $text = trans('user.alert.user-updated-successfully', ['user' => $this->userSelect->full_name]);
            $status = 'success';
        }
        $this->notification($title, $text, $status);
        $this->closeEditUserModal();
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
    public function back($step)
    {
        $this->currentStep = $step;
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


    /**
     * Close add user modal
     */
    #[On('close-edit-user-modal')]
    public function closeEditUserModal()
    {
        $this->dispatch('refresh-user-list');
        $this->currentStep = 0;
        $this->editUserModal = false;
        $this->reset();
    }


    public function render()
    {
        return view('livewire.admin.users.modal.edit-user-modal');
    }
}

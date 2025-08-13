<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Actions\Users\UserAddressAction;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Customers\BulkUpdateCustomerAddressRequest;

class BulkEditCustomerModal extends Component
{
    public $bulkEditCustomerModal = false;
    public $users;
    public $input = [];
    public $provinces = null;
    public $cities = null;
    public $districts = null;
    public $subDistricts = null;
    public $changeInstallationAddress = false;
    public $changeBillingAddress = false;

    #[On('bulk-edit-customer-modal')]
    public function showBulkEditCustomerModal($userSelected)
    {
        $this->reset();
        $this->bulkEditCustomerModal = true;
        $users = User::query()
            ->whereIn('id', $userSelected)
            ->get();
        $this->users = $users;

        $userSelected = [];
    }

    public function updateBulkCustomerInformation()
    {
        $this->resetErrorBag();
        (new BulkUpdateCustomerAddressRequest())->validate($this->input);

        $successUpdate = 0;
        $failedUpdate = 0;
        foreach ($this->users as $user) {
            try {
                (new UserAddressAction())->updateUserAddress($user, $this->input, $this->changeInstallationAddress, $this->changeBillingAddress);
                $successUpdate++;
            } catch (\Exception $e) {
                $failedUpdate++;
            }
        }

        $this->notification('Success', trans('customer.alert.bulk-edit-customer-detail', ['countSuccess' => $successUpdate, 'countFailed' => $failedUpdate]), 'success');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->bulkEditCustomerModal = false;
        $this->dispatch('refresh-customer-list');
        $this->dispatch('refresh-selected-users');
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
        return view('livewire.admin.customers.modal.bulk-edit-customer-modal');
    }
}

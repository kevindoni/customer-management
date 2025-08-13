<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Livewire\Actions\UserAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Livewire\Actions\Customers\RegisterCustomerAction;
use App\Http\Requests\Customers\CreateCustomerStepOneRequest;
use App\Http\Requests\Customers\CreateCustomerStepTwoRequest;
use App\Http\Requests\Customers\CreateCustomerStepThreeRequest;

#[Layout('components.layouts.auth')]
class Register extends Component
{
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
     * Handle an incoming registration request.
     */
    //step 1
    public function firstStepSubmit()
    {
        // dd($this->input);
        (new CreateCustomerStepOneRequest())->validate($this->input);
        // $this->firstname = $this->input['first_name'];
        //$this->lastname = $this->input['last_name'] ?? null;
        // $this->address = $this->input['address'];
        $this->currentStep = 2;
    }

    //step 2
    public function secondStepSubmit()
    {
        // dd($this->input);
        $this->input['phone'] =  $this->input['phone'] ?? null;
        (new CreateCustomerStepTwoRequest())->validate($this->input);

        $this->currentStep = 3;
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }
    public function register(): void
    {
        (new CreateCustomerStepThreeRequest())->validate($this->input);
        // $this->input['username'] = $this->generateUsername($this->input['first_name']);
        // event(new Registered(($user = (new RegisterCustomerAction())->handle($this->input))));
        DB::beginTransaction();
        event(new Registered(($user = (new UserAction())->addUser($this->input))));
        DB::commit();
        Auth::login($user);
        if (Auth::user()->hasRole('admin')){
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('customer.dashboard', absolute: false), navigate: true);
        }
        //$this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Drop down process
     */

    public function updatedInputCountry($value)
    {
        $this->provinces = $this->getAddress('https://alamat.thecloudalert.com/api/provinsi/get/');
    }

    public function updatedInputProvince($province)
    {
        // dd($province);
        $province = json_decode($province, true);
        $this->cities = $this->getAddress('https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=' . $province['id']);
    }

    public function updatedInputCity($city)
    {
        $city = json_decode($city, true);
        $this->districts = $this->getAddress('https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=' . $city['id']);
    }

    public function updatedInputDistrict($district)
    {
        $district = json_decode($district, true);
        $this->subDistricts = $this->getAddress('https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=' . $district['id']);
        //  $this->zipCode = $this->getAddress('https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=1&d_kecamatan_id=' . $district['id']);
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
}

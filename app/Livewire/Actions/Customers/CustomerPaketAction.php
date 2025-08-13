<?php

namespace App\Livewire\Actions\Customers;

use Exception;
use App\Models\User;
use App\Models\Websystem;
use App\Traits\UsernamePpp;
use App\Models\Pakets\Paket;
use Illuminate\Support\Carbon;
use App\Traits\CustomerPaketTrait;
use App\Models\Pakets\InternetService;
use App\Models\Customers\CustomerPaket;
use App\Models\Customers\CustomerPppPaket;
use App\Services\Billings\DeadlineService;
use App\Models\Customers\CustomerStaticPaket;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Livewire\Actions\Customers\CustomerPaketAddressAction;

class CustomerPaketAction
{
    // use UsernamePpp;
    use CustomerPaketTrait;

    private MikrotikPppService $pppService;
    private DeadlineService $deadlineService;

    // private CustomerPaketService $customerPaketService;
    public function __construct()
    {
        $this->pppService = new MikrotikPppService;
        $this->deadlineService =  new DeadlineService;
    }

    public function importCustomerPaket(User $user, array $input): CustomerPaket
    {
        $paket = Paket::find($input['selectedPaket']);
        $startDate = Carbon::parse($input['activation_date']) ?? Carbon::now();
        $expiredDate = $this->deadlineService->convertSubscription($input['renewal_period'], $startDate);
        return $this->createCustomerPaket($user, $paket, $startDate, $expiredDate, $input);
    }

    /**
     * Summary of createCustomerPaket
     * @param \App\Models\User $user
     * @param mixed $paket
     * @param mixed $startDate
     * @param mixed $expiredDate
     * @param array $input
     * @return CustomerPaket
     */
    public function createCustomerPaket(User $user, $paket, $startDate, $expiredDate, array $input): CustomerPaket
    {
        $internetServiceId = InternetService::where('value', $input['selectedInternetService'])->first()->id;
        $itemQuantity = $this->getQuantity($input['renewal_period'] ?? 'monthly');

        //Create customer paket with quantity from renewal period
        $customerPaket = CustomerPaket::create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'internet_service_id' => $internetServiceId,
            'price' => $paket->price * $itemQuantity,
            'discount' => $input['discount'] ?? 0,
            'renewal_period' => $input['renewal_period'] ?? 'monthly',
            'start_date' => $startDate,
            'expired_date' => $expiredDate,
            'activation_date' => $startDate,
        ]);

        (new CustomerPaketAddressAction())->addInstallationAddress($customerPaket, $input);
        (new CustomerPaketAddressAction())->addBillingAddress($customerPaket, $input);

        if ($input['selectedInternetService'] == 'ppp') {
            $this->createPppPaket($customerPaket, $input);
        } else if ($input['selectedInternetService'] == 'ip_static') {
            $this->createStaticPaket($customerPaket, $input);
        }
        return $customerPaket;
    }



    public function createPppPaket(CustomerPaket $customerPaket, array $input): CustomerPppPaket
    {
        return $customerPaket->customer_ppp_paket()->save(new CustomerPppPaket([
            'username' => $input['usernamePpp'] ?? $this->generateUsername($customerPaket->user->first_name),
            'password_ppp' => $input['passwordPpp'] ?? $this->generatePassword(),
            'ppp_type_id' => $input['selectedPppService'],
            'secret_id' => $input['secret_id'] ?? null,
        ]));
    }

    public function updatePppPaket(CustomerPppPaket $customerPppPaket, array $input)
    {
        $customerPppPaket->forceFill([
            'username' =>  $input['username'],
            'password_ppp' =>  $input['password_ppp'],
            'ppp_type_id' => $input['selectedPppService'] ?? $customerPppPaket->ppp_type_id,
        ])->save();
    }

    public function createStaticPaket(CustomerPaket $customerPaket, array $input): CustomerStaticPaket
    {
        return $customerPaket->customer_static_paket()->save(new CustomerStaticPaket([
            'ip_address' =>  $input['ip_address'] ?? null,
            'interface' =>  $input['selectedMikrotikInterface'] ?? null
        ]));
    }

    public function updateStaticPaket(CustomerStaticPaket $customerStaticPaket, array $input)
    {
        $customerStaticPaket->forceFill([
            'ip_address' =>  $input['ip_address'],
            'mac_address' =>  $input['mac_address'],
            'interface' =>  $input['selectedMikrotikInterface'] ?? null
        ])->save();
    }

    public function update_secret_id($customerPaket, $secretID)
    {
        $customerPaket->customer_ppp_paket->forceFill([
            'secret_id' => $secretID,
        ])->save();
    }

    public function update_status_customer_paket($customerPaket, $status)
    {
        $customerPaket->forceFill([
            'status' =>  $status
        ])->save();
    }

    public function delete_paket($customerPaket)
    {
        $customerPaket->delete();
    }

    public function updatePaketCustomerPaket(CustomerPaket $customerPaket, $paketID)
    {
        $customerPaket->forceFill([
            'paket_id' => $paketID
        ])->save();
    }

    public function update_ip_static_paket_id($ipStaticPaket, $simpleque_id, $arp_id, $simpleque_name)
    {
        $ipStaticPaket->forceFill([
            'simpleque_id' => $simpleque_id,
            'simpleque_name' => $simpleque_name,
            'arp_id' => $arp_id,
        ])->save();
    }
}

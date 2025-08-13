<?php

namespace App\Livewire\Actions\Users;

use App\Models\User;
use App\Models\UserAddress;
use App\Traits\StandardPhoneNumber;
use App\Livewire\Actions\Customers\CustomerPaketAddressAction;


class UserAddressAction
{
    use StandardPhoneNumber;
    /**
     * Validate and create a newly address user.
     *
     * @param  array<string, string>  $input
     */

    public function addUserAddress(User $user, array $input): UserAddress
    {
        $userAddress = new UserAddress();
        $user->user_address()->save($userAddress);
        $this->updateUserAddress($user, $input);
        return $userAddress;
    }

    public function addInstallationAddress(User $user, array $input): UserAddress
    {
        $userAddress = $user->user_address;
        if ($input['checkbox_address_installation']) {
            $installationAddress = new UserAddress();
            $user->user_address()->save($userAddress);
            $this->updateUserAddress($user, $input);
        } else {
            $installationAddress = $userAddress->replicate();
            $installationAddress->address_type = $input["address_type"];
            $installationAddress->save();
        }
        return  $installationAddress;
    }

    public function addBillingAddress(User $user, array $input): UserAddress
    {
        $userAddress = $user->user_address;
        if ($input['checkbox_address_billing']) {
            $billingAddress = new UserAddress();
            $user->user_address()->save($billingAddress);
            $this->updateUserAddress($user, $input);
        } else {
            $billingAddress = $userAddress->replicate();
            $billingAddress->address_type = $input["address_type"];
            $billingAddress->save();
        }
        return $billingAddress;
    }
    public function updateUserAddress(User $user, array $input, $changeInstallationAddress = false, $changeBillingAddress = false)
    {
        $input['phone'] ?? $input['phone'] = $user->user_address->phone;
        if ($input['phone']) $input['phone'] = $this->internationalPhoneNumberFormat($input['phone']);
        $user->user_address->forceFill([
            'country' => $input['country'],
            'province' => $input['province'],
            'city' => $input['city'],
            'district' => $input['district'],
            'subdistrict' => $input['subdistrict'],
            'phone' => $input['phone'],
            // 'phone' => $input['phone'],
            'address' => $input['address'] ?? null,
        ])->save();

       // dd($user->customer_pakets);
        foreach ($user->customer_pakets as $customer_paket) {
            if ($changeInstallationAddress) {
                $inputInstallationAddress = [
                    'installation_country' => $input['country'],
                    'installation_province' => $input['province'],
                    'installation_city' => $input['city'],
                    'installation_district' => $input['district'],
                    'installation_subdistrict' => $input['subdistrict'],
                    'installation_phone' => $input['phone'],
                    // 'phone' => $input['phone'],
                    'installation_address' => $input['address'] ?? null,
                ];
                (new CustomerPaketAddressAction())->updateInstallationAddress($customer_paket->customer_installation_address, $inputInstallationAddress);
            }

            if ($changeBillingAddress) {
                $inputBillingAddress = [
                'billing_country' => $input['country'],
                'billing_province' => $input['province'],
                'billing_city' => $input['city'],
                'billing_district' => $input['district'],
                'billing_subdistrict' => $input['subdistrict'],
                'billing_phone' => $input['phone'],
                'billing_address' => $input['address'] ?? null,
                ];
                (new CustomerPaketAddressAction())->updateBillingAddress($customer_paket->customer_billing_address, $inputBillingAddress);
            }
        }
        /*
        if ($user->customer_pakets && $input['checkbox_address_installation'])
            $inputInstallationAddress = [
                'installation_country' => $input['country'],
                'installation_province' => $input['province'],
                'installation_city' => $input['city'],
                'installation_district' => $input['district'],
                'installation_subdistrict' => $input['subdistrict'],
                'installation_phone' => $this->internationalPhoneNumberFormat($input['phone']),
                // 'phone' => $input['phone'],
                'installation_address' => $input['address'] ?? null,
            ];
        foreach ($user->customer_pakets as $customer_paket) {

            (new CustomerPaketAddressAction())->updateInstallationAddress($customer_paket->customer_installation_address, $inputInstallationAddress);
        }

        if ($user->customer_pakets && $input['checkbox_billing_installation'])
            $inputBillingAddress = [
                'billing_country' => $input['country'],
                'billing_province' => $input['province'],
                'billing_city' => $input['city'],
                'billing_district' => $input['district'],
                'billing_subdistrict' => $input['subdistrict'],
                'billing_phone' => $this->internationalPhoneNumberFormat($input['phone']),
                'billing_address' => $input['address'] ?? null,
            ];
        foreach ($user->customer_pakets as $customer_paket) {
            (new CustomerPaketAddressAction())->updateInstallationAddress($customer_paket->customer_billing_address, $inputBillingAddress);
        }*/
    }
}

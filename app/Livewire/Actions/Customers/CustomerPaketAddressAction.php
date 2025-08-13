<?php

namespace App\Livewire\Actions\Customers;

use App\Models\User;
use App\Models\UserAddress;
use App\Traits\StandardPhoneNumber;
use App\Models\Customers\CustomerPaket;
use App\Models\Customers\CustomerPaketAddress;


class CustomerPaketAddressAction
{
    use StandardPhoneNumber;

    public function addInstallationAddress(CustomerPaket $customerPaket, array $input)
    {
        $installationAddress = new CustomerPaketAddress();
        $installationAddress = $customerPaket->customer_paket_address()->save($installationAddress);
        $this->updateInstallationAddress($installationAddress, $input);
    }

    public function addBillingAddress(CustomerPaket $customerPaket, array $input)
    {
        $billingAddress = new CustomerPaketAddress();
        $billingAddress = $customerPaket->customer_paket_address()->save($billingAddress);
        $this->updateBillingAddress($billingAddress, $input);
    }
    public function updateInstallationAddress(CustomerPaketAddress $customerPaketAddress, array $input)
    {
        $input['installation_phone'] ?? $input['installation_phone'] = null;
        if ($input['installation_phone']) $input['installation_phone'] = $this->internationalPhoneNumberFormat($input['installation_phone']);
        $customerPaketAddress->forceFill([
            'address_type' => 'installation-address',
            'country' => $input['installation_country'] ?? null,
            'province' => $input['installation_province'] ?? null,
            'city' => $input['installation_city'] ?? null,
            'district' => $input['installation_district'] ?? null,
            'subdistrict' => $input['installation_subdistrict'] ?? null,
            'phone' => $this->internationalPhoneNumberFormat($input['installation_phone'] ?? null),
            'address' => $input['installation_address'] ?? null,
        ])->save();
    }

    public function updateBillingAddress(CustomerPaketAddress $customerPaketAddress, array $input)
    {
        $input['billing_phone'] ?? $input['billing_phone'] = null;
        if ($input['billing_phone']) $input['billing_phone'] = $this->internationalPhoneNumberFormat($input['billing_phone']);
        $customerPaketAddress->forceFill([
            'address_type' => 'billing-address',
            'country' => $input['billing_country'] ?? null,
            'province' => $input['billing_province'] ?? null,
            'city' => $input['billing_city'] ?? null,
            'district' => $input['billing_district'] ?? null,
            'subdistrict' => $input['billing_subdistrict'] ?? null,
            'phone' => $input['billing_phone'],
            'address' => $input['billing_address'] ?? null,
        ])->save();
    }

    public function disableWaNotificationInstallationAddress(CustomerPaketAddress $customerPaketAddress)
    {
        $customerPaketAddress->forceFill([
            'wa_notification' => $customerPaketAddress->wa_notification ? false : true
        ])->save();
    }
}

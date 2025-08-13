<?php

namespace App\Http\Requests\Customers;

use App\Models\Customers\CustomerPaket;
use App\Models\Customers\CustomerStaticPaket;
use Illuminate\Support\Facades\Validator;

class AddCustomerPaketRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'selectedServer' => ['required'],
            'selectedPaket' => ['required'],
            'selectedInternetService' => ['required'],
            'selectedPppService' => ['required_if:selectedInternetService,==,ppp', 'nullable'],
            'ip_address' => ['required_if:selectedInternetService,==,ip_static', 'nullable', 'ipv4'],
            'selectedMikrotikInterface' => ['nullable'],
            'discount' => ['nullable'],
        ])->validate();


        if ($input['selectedInternetService'] == 'ip_static') {
            $customerPaketStatics = CustomerStaticPaket::whereIpAddress($input['ip_address'])->withTrashed()->get();

            $unique = '';
            foreach ($customerPaketStatics as $customerPaketStatic) {
               // dd($customerPaketStatic->customer_paket_id);
                $customerPaket = CustomerPaket::whereId($customerPaketStatic->customer_paket_id)->withTrashed()->first();

                $mikrotik = $customerPaket->paket->mikrotik->id;
               // dd($mikrotik);
                if ($mikrotik == (int)$input['selectedServer']) {
                    $unique = 'unique:customer_static_pakets';
                }
            }
/*

            if ($customerPaketStatic->exists()) {
                $mikrotik = $customerPaketStatic->first()->customer_paket->paket->mikrotik->id;
                if ($mikrotik == (int)$input['selectedServer']) {
                    $unique = 'unique:customer_static_pakets';
                }
            }
*/
            Validator::make($input, [
                'ip_address' => [$unique],
            ], [
                'ip_address.unique' => trans('customer.validation-error-message.ip-address-unique')
            ])->validate();
        }

        return $input;
        // return true;
    }
}

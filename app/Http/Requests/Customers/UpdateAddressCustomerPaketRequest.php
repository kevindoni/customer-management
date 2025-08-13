<?php

namespace App\Http\Requests\Customers;

use Illuminate\Validation\Rule;
use App\Traits\StandardPhoneNumber;
use Illuminate\Support\Facades\Validator;

class UpdateAddressCustomerPaketRequest
{
    use StandardPhoneNumber;
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate($customerPaket, array $input)
    {
        $input['phone'] ?? $input['phone'] = null;
        if ($input['phone']) $input['phone'] = $this->internationalPhoneNumberFormat($input['phone']);
        Validator::make($input, [
            'phone' => ['nullable', 'regex:/^([0-9\s\+\(\)]*)$/', 'min:9', 'max:14', Rule::unique('customer_paket_addresses')->ignore($customerPaket->id, 'customer_paket_id')],
            'country' => ['required'],
            'province' => ['required'],
           // 'city' => ['required'],
           // 'district' => ['required'],
           // 'subdistrict' => ['required'],
            'address' => ['required', 'min:6'],
        ])->validate();
    }
}

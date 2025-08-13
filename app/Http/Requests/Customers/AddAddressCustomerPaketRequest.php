<?php

namespace App\Http\Requests\Customers;

use App\Traits\StandardPhoneNumber;
use Illuminate\Support\Facades\Validator;

class AddAddressCustomerPaketRequest
{
    use StandardPhoneNumber;
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input)
    {
        $input['phone'] ?? $input['phone'] = null;
        if ($input['phone']) $input['phone'] = $this->internationalPhoneNumberFormat($input['phone']);
        Validator::make($input, [
            'phone' => ['nullable', 'regex:/^([0-9\s\+\(\)]*)$/', 'min:9', 'max:14', 'unique:user_addresses'],
            'country' => ['required'],
            'province' => ['required'],
            //'city' => ['required'],
            //'district' => ['required'],
            //'subdistrict' => ['required'],
            'address' => ['required', 'min:6'],
        ])->validate();
    }
}

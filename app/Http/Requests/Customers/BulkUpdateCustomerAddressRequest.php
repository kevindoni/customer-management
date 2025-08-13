<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Validator;

class BulkUpdateCustomerAddressRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): void
    {
         Validator::make($input, [
            'country' => ['required'],
            'province' => ['required'],
            //'city' => ['required'],
           // 'district' => ['required'],
           // 'subdistrict' => ['required'],
            'address' => ['required', 'min:6'],
        ])->validate();
    }
}

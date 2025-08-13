<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class CreateCustomerStepThreeRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): void
    {
        Validator::make($input, [

            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:users'],
            'role' => ['required'],
            'password' =>  ['required', 'string', Rules\Password::defaults()]
        ])->validate();
    }
}

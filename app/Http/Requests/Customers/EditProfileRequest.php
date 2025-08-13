<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Validator;

class EditProfileRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate($input): void
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'min:2', 'max:15'],
            'last_name' => ['nullable', 'string', 'min:2', 'max:40'],
            'nin' => ['required', 'numeric'],
            'dob' => ['required'],
            'gender' => ['required'],
        ])->validate();
    }
}

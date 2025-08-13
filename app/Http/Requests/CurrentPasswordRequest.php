<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class CurrentPasswordRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
        ], [
            'current_password.required' => __('customer.validation-error-message.current-password-required'),
        ])->validate();

    }
}

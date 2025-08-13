<?php

namespace App\Http\Requests\Mikrotik;

use Illuminate\Support\Facades\Validator;

class AddMikrotikRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'description' => ['nullable', 'string', 'min:10', 'max:255'],
            'current_password' => ['required', 'string', 'current_password:web'],

        ], [
            'current_password.required' => __('mikrotik.validation-error-message.current-password-required'),
        ])->validate();

        return $input;
    }
}

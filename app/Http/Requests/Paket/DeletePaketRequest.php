<?php

namespace App\Http\Requests\Paket;

use Illuminate\Support\Facades\Validator;

class DeletePaketRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input, $paket): bool
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'selectedPaket' => $paket->customer_pakets->count() ? ['required'] : ['nullable']
        ], [
            'current_password.required' => __('customer.validation-error-message.current-password-required'),
        ])->validate();

        return true;
        // return true;
    }
}

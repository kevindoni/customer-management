<?php

namespace App\Http\Requests\Customers;

use Illuminate\Support\Facades\Validator;

class CreateCustomerStepOneRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): void
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'min:2', 'max:15'],
            'last_name' => ['nullable', 'string', 'min:2', 'max:40'],
            'dob' => ['required'],
            'gender' => ['required'],
        ], [
            'first_name.required' => __('customer.validation-error-message.first-name-required'),
            'first_name.min' => __('customer.validation-error-message.first-name-min'),
            'first_name.max' => __('customer.validation-error-message.first-name-max'),
            'last_name.min' => __('customer.validation-error-message.last-name-min'),
            'last_name.max' => __('customer.validation-error-message.last-name-max'),
        ])->validate();
    }
}

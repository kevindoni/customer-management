<?php

namespace App\Http\Requests\Websystem;


use Illuminate\Support\Facades\Validator;

class ConfigGeneralRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input)
    {
        Validator::make($input, [
            'title' => ['required'],
            'app_url' => ['required'],
            'diff_day' => ['numeric', 'max:15', 'required'],
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255'],
            'address' => ['required'],
            'city' => ['required'],
            'postal_code' => ['required'],
            'phone' => ['required', 'numeric'],
            'tax_rate' => ['required', 'numeric'],
        ])->validate();
    }
}

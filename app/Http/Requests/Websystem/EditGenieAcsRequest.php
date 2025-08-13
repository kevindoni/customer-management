<?php

namespace App\Http\Requests\Websystem;

use App\Models\AutoIsolir;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EditGenieAcsRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'host' =>  ['required', 'url:http,https'],
            'username' => ['required'],
            'password' => ['required'],
            'port' => ['numeric'],
        ])->validate();

        return $input;
        // return true;
    }
}

<?php

namespace App\Http\Requests\Paket;

use App\Models\Customer\IpStaticPaket;
use Illuminate\Support\Facades\Validator;

class AddPaketStep1Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:pakets'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'min:10', 'max:255'],
        ])->validate();

        return $input;
        // return true;
    }
}

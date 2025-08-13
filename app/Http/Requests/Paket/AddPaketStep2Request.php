<?php

namespace App\Http\Requests\Paket;

use App\Models\Customer\IpStaticPaket;
use Illuminate\Support\Facades\Validator;

class AddPaketStep2Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'selectedProfile' => ['required'],
            'selectedServer' => ['required'],
            'current_password' => ['required', 'string', 'current_password:web'],
        ])->validate();

        return $input;
        // return true;
    }
}

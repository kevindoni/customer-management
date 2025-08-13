<?php

namespace App\Http\Requests\Paket;

use App\Models\Pakets\Paket;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EditPaketStep2Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(Paket $paket, array $input): array
    {
        Validator::make($input, [
           // 'selectedServer' => ['required'],
            'selectedProfile' => ['required'],
            'current_password' => ['required', 'string', 'current_password:web'],
        ])->validate();

        return $input;
        // return true;
    }
}

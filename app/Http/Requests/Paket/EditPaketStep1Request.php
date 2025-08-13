<?php

namespace App\Http\Requests\Paket;

use App\Models\Pakets\Paket;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class EditPaketStep1Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(Paket $paket, array $input): array
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('pakets')->ignore($paket->id)],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'min:10', 'max:255'],
        ])->validate();

        return $input;
        // return true;
    }
}

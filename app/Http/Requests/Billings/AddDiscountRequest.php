<?php

namespace App\Http\Requests\Billings;

use Illuminate\Support\Facades\Validator;

class AddDiscountRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        $value = 1000;
        Validator::make($input, [
            'discount' => ['required', 'numeric', 'min:' . $value],
        ])->validate();

        return $input;
    }
}

<?php

namespace App\Http\Requests\Customers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class UpdateUserStepThreeRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(User $user, array $input): void
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
            'role' => ['required'],
            'password' =>  ['nullable', 'string', 'confirmed', Rules\Password::defaults()]
        ])->validate();
    }
}

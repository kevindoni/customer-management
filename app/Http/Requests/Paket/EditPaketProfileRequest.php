<?php

namespace App\Http\Requests\Paket;

use Illuminate\Validation\Rule;
use App\Models\Pakets\PaketProfile;
use Illuminate\Support\Facades\Validator;

class EditPaketProfileRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(PaketProfile $paketProfile, array $input): array
    {
        Validator::make($input, [
            'profile_name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('paket_profiles')->ignore($paketProfile->id)],
            'max_limit' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'burst_limit' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'burst_threshold' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'burst_time' => ['required', 'regex:/^([0-9\/]*)$/'],
            'priority' => ['required', 'numeric'],
            'limit_at' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],

        ])->validate();

        return $input;
        // return true;
    }
}

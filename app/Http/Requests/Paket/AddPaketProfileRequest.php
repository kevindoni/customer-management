<?php

namespace App\Http\Requests\Paket;

use App\Models\Customer\IpStaticPaket;
use Illuminate\Support\Facades\Validator;

class AddPaketProfileRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'profile_name' => ['required', 'string', 'min:3', 'max:255', 'unique:paket_profiles'],
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

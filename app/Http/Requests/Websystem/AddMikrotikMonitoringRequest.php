<?php

namespace App\Http\Requests\Websystem;


use Illuminate\Support\Facades\Validator;

class AddMikrotikMonitoringRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input): array
    {
        Validator::make($input, [
            'selectedServer' => ['required', 'unique:mikrotik_monitorings,mikrotik_id'],
            'interface' => ['required'],
            'min_download' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'max_download' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'min_upload' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
            'max_upload' => ['required', 'regex:/^([0-9\/\(G)\(M)\(k)]*)$/'],
        ])->validate();

        return $input;
        // return true;
    }
}

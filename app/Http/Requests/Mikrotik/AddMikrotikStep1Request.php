<?php

namespace App\Http\Requests\Mikrotik;

use Illuminate\Support\Facades\Validator;

class AddMikrotikStep1Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'host' => ['required', 'unique:mikrotiks'],
            'port' => ['required', 'numeric'],
            'username' => ['required'],
            'password' => ['required'],
            'web_port' => ['required', 'numeric'],
            'version' => ['required_if:add_without_test,==,true'],
            'subVersion1' => ['required_if:add_without_test,==,true'],
            'subVersion2' => ['required_if:add_without_test,==,true'],

        ], [
            'version.required_if' => trans('Version mikrotik required.'),
            'subVersion1.required_if' => trans('Version mikrotik required.'),
            'subVersion2.required_if' => trans('Version mikrotik required.')
        ])->validate();
        // return true;
    }
}

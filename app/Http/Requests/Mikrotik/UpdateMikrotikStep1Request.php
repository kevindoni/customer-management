<?php

namespace App\Http\Requests\Mikrotik;

use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateMikrotikStep1Request
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(Mikrotik $mikrotik, array $input)
    {
        Validator::make($input, [
            'name' => ['required'],
            'host' => ['required', Rule::unique('mikrotiks')->ignore($mikrotik->id, 'id')],
            'port' => ['required', 'numeric'],
            'username' => ['nullable'],
            'password' => ['nullable'],
            'web_port' => ['required', 'numeric'],
            'version' => ['required_if:add_without_test,==,true'],
            'subVersion1' => ['required_if:add_without_test,==,true'],
            'subVersion2' => ['required_if:add_without_test,==,true'],
            //'description' => ['nullable', 'string', 'min:10', 'max:255'],
        ], [
            'version.required_if' => trans('Version mikrotik required.'),
            'subVersion1.required_if' => trans('Version mikrotik required.'),
            'subVersion2.required_if' => trans('Version mikrotik required.')
        ])->validate();
    }
}

<?php

namespace App\Http\Requests\Websystem;


use Illuminate\Support\Facades\Validator;

class AddAutoisolirRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input)
    {
        Validator::make($input, [
            'selectedServer' => ['required', 'unique:auto_isolirs,mikrotik_id'],
            'selectedProfile' => ['required'],
            // 'selectedRunIsolirOption' => ['required'],
            'address_list_isolir' => ['required'],
            'name' => ['required', 'alpha_dash', 'unique:auto_isolirs,name'],
            'selectedAutoIsolirOption' => ['required'],
            'due_date' => ['required_if:selectedAutoIsolirOption,==,false', 'numeric', 'nullable'],
        ])->validate();
    }
}

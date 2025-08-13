<?php

namespace App\Http\Requests\Websystem;

use Illuminate\Validation\Rule;
use App\Models\Customers\AutoIsolir;
use Illuminate\Support\Facades\Validator;

class EditAutoisolirRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(AutoIsolir $autoisolir, array $input)
    {
        Validator::make($input, [
            'selectedProfile' => ['required'],
            'address_list_isolir' => ['required'],
            // 'selectedRunIsolirOption' => ['required'],
            'name' => ['required', 'alpha_dash', Rule::unique('auto_isolirs')->ignore($autoisolir->id)],
            'selectedAutoIsolirOption' => ['required'],
            'due_date' => ['required_if:selectedAutoIsolirOption,==,false', 'numeric', 'nullable'],
        ])->validate();
    }
}

<?php

namespace App\Http\Requests\Customers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers\CustomerPppPaket;

class EditPppPaketRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input, $pppPaketUpdate): array
    {
        Validator::make($input, [
            'username' => ['required', 'string'],
            'password_ppp' => ['required', 'string'],
        ])->validate();

        $pppPakets = CustomerPppPaket::whereUsername($input['username'])->get();
        $unique = '';
        foreach ($pppPakets as $pppPaket) {
            if ($pppPaket->username == $input['username']) {
                $mikrotikID = $pppPaket->customer_paket->paket->mikrotik_id;
                if ($mikrotikID == $pppPaketUpdate->customer_paket->paket->mikrotik_id) {
                    $unique = Rule::unique('customer_ppp_pakets')->ignore($pppPaketUpdate->id);
                }
            }
        };



        Validator::make($input, [
            'username' => [$unique],
        ], [
            'username.unique' => trans('customer.validation-error-message.username-unique')
        ])->validate();

        return $input;
    }
}

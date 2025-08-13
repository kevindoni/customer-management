<?php

namespace App\Http\Requests\Customers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers\CustomerStaticPaket;

class EditIpStaticPaketRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate(array $input, $ipStaticPaketUpdate, $mikrotikInterface): array
    {
        if (is_null($mikrotikInterface)){
            $required = 'nullable';
        } else {
            $required = 'required';
        }
        Validator::make($input, [
            'ip_address' => ['required', 'ipv4'],
            'mac_address' => ['required', 'mac_address'],
            'selectedMikrotikInterface' => [$required],
        ])->validate();

        $ipStaticPakets = CustomerStaticPaket::whereIpAddress($input['ip_address'])->get();
        $unique = '';
        foreach ($ipStaticPakets as $ipStaticPaket) {
            if ($ipStaticPaket->ip_address == $input['ip_address']) {
                $mikrotikID = $ipStaticPaket->customer_paket->paket->mikrotik->id;
                if ($mikrotikID == $ipStaticPaketUpdate->customer_paket->paket->mikrotik->id) {
                    $unique = Rule::unique('customer_static_pakets')->ignore($ipStaticPaketUpdate->id);
                }
            }
        };

        Validator::make($input, [
            'ip_address' => [$unique],
        ], [
            'ip_address.unique' => trans('customer.validation-error-message.ip-address-unique')
        ])->validate();

        return $input;
    }
}

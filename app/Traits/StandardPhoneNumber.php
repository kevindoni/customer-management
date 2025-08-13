<?php

namespace App\Traits;

use App\Models\WhatsappGateway\WhatsappGatewayGeneral;

trait StandardPhoneNumber
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function internationalPhoneNumberFormat($number)
    {
        $country_code = WhatsappGatewayGeneral::first()->country_code;
        //  $number = $request->input('number');
        if (substr($number, 0, 2) == $country_code) {
            return preg_replace('/^' . $country_code . '?/', $country_code, $number);
        } else if (substr($number, 0, 1) == '0') {
            return preg_replace('/^0?/', $country_code, $number);
        } else {
            return $number;
        }
    }

    public function localPhoneNumberFormat($number)
    {
        $country_code = WhatsappGatewayGeneral::first()->country_code;
        //  $number = $request->input('number');
        // if (substr($number, 0, 2) == $country_code) {
        return preg_replace('/^' . $country_code . '?/', 0, $number);
        // }
    }
}

<?php

namespace App\Http\Requests\WhatsappGateway;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function validate(array $input)
    {
        Validator::make($input, [
            'product' => ['required'],
            'renewal_period' => ['required'],
            'payment_method' => ['required'],
        ],
        [
            'product.required' => 'The product is required, please select one product!',
            'renewal_period.required' => 'The renewal period is required, please select one!',
        ])->validate();
    }
}

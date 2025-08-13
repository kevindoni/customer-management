<?php

namespace App\Http\Requests\Billings;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PaymentRequest
{
    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     */
    public function validate($invoice, array $input): array
    {
        $max = $invoice->amount - $invoice->discount + $invoice->tax;
        //$payments = $invoice->payments;
        if ($invoice->payments->count()){
            $max = $max - $invoice->payments()->whereReconciliationStatus('partial')->whereNull('refund_status')->sum('amount');
        }

        Validator::make($input, [
            'selectedPaymentMethode' => ['required'],
            //'amount' => [Rule::requiredIf($input['selectedPaymentMethode'] != 'paylater'),'lte:'.$max],
            'amount' => ['required','lte:'.$max],
            'paylaterDate' => ['required_if:selectedPaymentMethode,==,paylater', 'nullable'],
            'selectedBankTransfer' => ['required_if:selectedPaymentMethode,==,bank_transfer', 'nullable']

        ], [
            'amount.lte' => __('billing.validation-error-message.amount-less-than', ['max' => number_format($max, 0)]),
            'selectedPaymentMethode.required' => __('billing.validation-error-message.payment-methode-required'),
            'selectedBankTransfer.required_if' => __('billing.validation-error-message.selected-bank-required'),
        ])->validate();

        return $input;
    }
}

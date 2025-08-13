<?php

namespace App\Services\Billings;

use App\Models\Websystem;

/**
 * Summary of PartialPaymentService
 */
class TaxService
{
    public function calculateTax($amount)
    {
        $websystem = Websystem::first();
        $totalTax = $amount * ($websystem->tax_rate / 100);
        return round($totalTax, 2);
    }
}

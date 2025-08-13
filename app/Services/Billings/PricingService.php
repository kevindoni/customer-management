<?php

namespace App\Services\Billings;

use App\Models\Websystem;
use App\Models\Customers\CustomerPaket;


class PricingService
{
    public function calculatePrice(CustomerPaket $customerPaket)
    {
       // dd($customerPaket);
        switch ($customerPaket->renewal_period) {
            case 'monthly':
                //return dd($customerPaket);
                return $this->calculateMonthlyPrice($customerPaket);
            case 'bimonthly':
                return $this->calculateMonthlyPrice($customerPaket)*2;
            case 'quarterly':
                return $this->calculateMonthlyPrice($customerPaket)*3;
            case 'semi-annually':
                return $this->calculateMonthlyPrice($customerPaket)*6;
            case 'annually':
                return $this->calculateMonthlyPrice($customerPaket)*12;
        }

    }

    private function calculateMonthlyPrice(CustomerPaket $customerPaket)
    {
    //  return dd($customerPaket);
        return $this->totalAmount($customerPaket->price, $customerPaket->discount);
    }

    public function totalAmount($price, $discount=0)
    {
       // dd($price);


            if ($discount){
                return ($price - $discount) + $this->tax($price - $discount);
            } else {
                return $price + $this->tax($price);
            }

    }

    public function tax($ammount)
    {
        $websystem = Websystem::first();
        return ($ammount * $websystem->tax_rate)/100;
    }
}

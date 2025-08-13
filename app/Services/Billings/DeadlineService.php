<?php

namespace App\Services\Billings;

use Illuminate\Support\Carbon;
use App\Models\Customers\CustomerPaket;
use App\Models\Websystem;


class DeadlineService
{

    public function calculateDeadline(CustomerPaket $customerPaket)
    {
        switch (Websystem::first()->subscription_mode) {
            case 'pascabayar':
                $expiredDate = is_null($customerPaket->expired_date) ? $customerPaket->next_billed_at : $customerPaket->expired_date;
                return $this->convertSubscription($customerPaket->renewal_period, $expiredDate);
            case 'prabayar':
                if (is_null($customerPaket->expired_date)) {
                    return Carbon::now()->addDays($customerPaket->paket->trial_days);
                }
                return $this->convertSubscription($customerPaket->renewal_period, $customerPaket->expired_date);
        }
    }

    public function convertSubscription($renewalPeriod, $expiredDate)
    {

        switch ($renewalPeriod) {
            case 'monthly':
                return Carbon::parse($expiredDate)->addMonth();
            case 'bimonthly':
                return Carbon::parse($expiredDate)->addMonths(2);
            case 'quarterly':
                return Carbon::parse($expiredDate)->addMonths(3);
            case 'semi-annually':
                return Carbon::parse($expiredDate)->addMonths(6);
            case 'annually':
                return Carbon::parse($expiredDate)->addMonths(12);
        }
    }

    public function calculateStartDate($renewalPeriod, $expiredDate)
    {

        switch ($renewalPeriod) {
            case 'monthly':
                return Carbon::parse($expiredDate)->subMonth();
            case 'bimonthly':
                return Carbon::parse($expiredDate)->subMonths(2);
            case 'quarterly':
                return Carbon::parse($expiredDate)->subMonths(3);
            case 'semi-annually':
                return Carbon::parse($expiredDate)->subMonths(6);
            case 'annually':
                return Carbon::parse($expiredDate)->subMonths(12);
        }
    }

    public function calculateQuantity($renewalPeriod)
    {
        switch ($renewalPeriod) {
            case 'monthly':
                return 1;
            case 'bimonthly':
                 return 2;
            case 'quarterly':
                 return 3;
            case 'semi-annually':
                return 6;
            case 'annually':
                 return 12;
            default:
                 return 1;
        }

    }
}

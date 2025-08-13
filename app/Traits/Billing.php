<?php

namespace App\Traits;

use App\Models\Websystem;
use Illuminate\Support\Carbon;
use App\Models\Customers\CustomerPaket;

trait Billing
{
    public function differentDayCreateInvoice()
    {
        return Websystem::first()->different_day_create_billing;
    }

    public function resetNextBill()
    {
         $customerPakets = CustomerPaket::all();
        foreach ($customerPakets as $customerPaket){
            $customerPaket->forceFill([
                'next_billed_at' => Carbon::parse($customerPaket->start_date)->subDays($this->differentDayCreateInvoice())
            ])->save();
        }
    }

    /*
    public function paket_payment($customer_paket)
    {
        $taxRate = Websystem::first()->tax_rate;
        $bill = $customer_paket->price - $customer_paket->discount;
        if ($taxRate > 0) {
            $bill = $bill + ($bill * ($taxRate / 100));
        }
        return $bill;
    }

    public function billing_periode($billing)
    {
        $customer_paket = $billing->customer_paket;
        $activationDate = Carbon::parse($customer_paket->activation_date)->format('d');
        $deadlineDate = Carbon::parse($customer_paket->activation_date)->subDay()->format('d');

        $billingMonthPeriode = Carbon::parse($billing->billing_periode)->format('F');
        $nextMonth = Carbon::parse($billing->billing_periode)->addMonth();
        $totalDaysNextMonth = $nextMonth->daysInMonth;
        if ($activationDate > $totalDaysNextMonth) {
            $deadlineDate = $totalDaysNextMonth;
        }
        $startPeriode = $activationDate . ' ' . $billingMonthPeriode . ' ' . Carbon::now()->format('Y');

        if ($activationDate == 1) {
            $endPeriode = Carbon::now()->endOfMonth()->format('d F Y');
        } else {
            $endPeriode =  $deadlineDate . ' ' . $nextMonth->format('F') . ' ' . Carbon::now()->format('Y');
        }

        return Carbon::parse($startPeriode)->format('d M Y') . ' - ' . $endPeriode;
    }

    public function billing_deadline($customer_paket)
    {
        $activationDate = Carbon::parse($customer_paket->activation_date)->format('d');
        $deadlineDate = Carbon::parse($customer_paket->activation_date)->subDay()->format('d');

        $totalDaysInMonth = Carbon::now()->daysInMonth;
        if ($activationDate > $totalDaysInMonth) {
            $deadlineDate = $totalDaysInMonth;
        }

        if ($activationDate == 1) {
            $deadline = Carbon::now()->endOfMonth()->format('d F Y');
        } else {
            $deadline =  $deadlineDate . ' ' . Carbon::now()->format('F Y');
        }

        return $deadline;
    }

    public function next_billing_deadline($billing)
    {
        // dd($billing->billing_periode);
        $activationDate = Carbon::parse($billing->customer_paket->activation_date)->format('d');
        $deadlineDate = Carbon::parse($billing->customer_paket->activation_date)->subDay()->format('d');
        // $nextMonth = Carbon::now()->addMonth();
        $nextMonth = Carbon::parse($billing->billing_periode)->addMonth()->format('F');
        $totalDaysNextMonth = Carbon::parse($billing->periode)->addMonth()->daysInMonth;
        // $totalDaysNextMonth = Carbon::now()->addMonth()->daysInMonth;
        if ($activationDate > $totalDaysNextMonth) {
            $deadlineDate = $totalDaysNextMonth;
        }
        // $deadline = Carbon::parse($activationDate . ' ' . Carbon::now()->format('F Y'));
        if ($activationDate == 1) {
            $deadline = Carbon::now()->endOfMonth()->format('d F Y');
        } else {
            $deadline =  $deadlineDate . ' ' . Carbon::parse($nextMonth)->format('F Y');
        }

        return $deadline;
    }


    public function prev_billing_deadline($billing)
    {
        $billings = $billing->customer_paket->billing_pakets();
        if ($billings->count() > 1) {
            $deadline = Carbon::parse($billing->deadline);
        } else {
            $deadline = Carbon::parse($billing->customer_paket->activation_date)->addDays(3);
        }

        return $deadline;
    }
        */
}

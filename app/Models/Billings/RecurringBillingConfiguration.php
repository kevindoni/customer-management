<?php

namespace App\Models\Billings;

use Illuminate\Database\Eloquent\Model;

class RecurringBillingConfiguration extends Model
{
    protected $fillable = [
        'invoice_id',
        'frequency',
        'billing_day',
        'next_billing_date',
        'is_active'
    ];

    protected $casts = [
        'next_billing_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function calculateNextBillingDate($frequency, $billingDay = null)
    {
        $date = now();

        if ($billingDay && $billingDay > $date->day) {
            $date->setDay($billingDay);
        } else {
            $date = match($frequency) {
                'monthly' => $date->addMonth(),
                'bimonthly' => $date->addMonths(2),
                'quarterly' => $date->addMonths(3),
                'semi-annually' => $date->addMonths(6),
                'annually' => $date->addYear(),
                default => $date->addMonth()
            };

            if ($billingDay) {
                $date->setDay($billingDay);
            }
        }

        return $date;
    }
}

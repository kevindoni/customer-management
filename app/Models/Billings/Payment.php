<?php

namespace App\Models\Billings;

use App\Models\Billings\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Payment extends Model
{
    use SoftDeletes;
    use HasUuids;
    public $guarded = [];

    /**
     * The "booting" function of model
     *
     * @return void
     */
   /* public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }*/


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isRefundable()
    {
        return $this->refund_status === 'none' || $this->refund_status === 'partial';
    }

    public function isCancelled()
    {
        return $this->payment_method === 'cash' || $this->payment_method === 'bank_transfer' || $this->payment_method === 'paylater';
    }

    public function user_teller_payments()
    {
        return $this->hasMany(UserTellerPayment::class);
    }
}

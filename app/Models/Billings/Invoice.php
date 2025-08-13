<?php

namespace App\Models\Billings;

use App\Models\Billings\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Customers\CustomerPaket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;

class Invoice extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['payments', 'orders', 'invoice_items', 'payment_histories'];
    protected $dates = ['deleted_at'];
    protected $fetchMethod = 'get'; // get, cursor, lazy or chunk

    public $guarded = [];

    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
        'last_late_fee_date' => 'datetime',
        'late_fee_amount' => 'decimal:2',
        'viewed_at' => 'datetime',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'status_history' => 'array',
    ];

    public function user()
    {
        return $this->customer_paket->user();
    }

    public function user_address()
    {
        return $this->customer_paket->user->user_address();
    }
    public function customer_paket(): BelongsTo
    {
        return $this->belongsTo(CustomerPaket::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment_histories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function needsReminder()
    {
        if ($this->status === 'paid') {
            return false;
        }

        $remainingDay = WhatsappGatewayGeneral::first()->remaining_day;
        $remainingDay = Carbon::parse($this->due_date)->startOfDay()->subDays($remainingDay);
        return $remainingDay->lte(Carbon::now()->startOfDay()) && Carbon::parse($this->due_date)->startOfDay()->gte(Carbon::now()->startOfDay()) && $this->customer_paket->isActive();
    }



    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getFormattedAmount()
    {
        // return number_format($this->total_amount, 2) . ' ' . $this->currency;
        return number_format($this->total_amount, 2);
    }

    public function recurringConfiguration()
    {
        return $this->hasOne(RecurringBillingConfiguration::class);
    }


}

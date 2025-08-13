<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;

class UsageRecord extends Model
{
    protected $fillable = [
        'customer_paket_id',
        'metric_name',
        'quantity',
        'recorded_at',
        'processed',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'processed' => 'boolean',
        'quantity' => 'decimal:2'
    ];

    public function customer_paket()
    {
        return $this->belongsTo(CustomerPaket::class);
    }
}

<?php

namespace App\Models\Servers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MikrotikClientHistory extends Model
{
    public $guarded = [];

    function customer_paket(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\CustomerPaket::class);
    }
    function user()
    {
       return $this->customer_paket->user();
   }

   function mikrotik()
    {
       return $this->customer_paket->paket->mikrotik();
   }
}

<?php

namespace App\Models\Billings;

use Illuminate\Database\Eloquent\Model;

class UserTellerPayment extends Model
{
    public $guarded = [];
    
    public function user()
    {
        $this->belongsTo(App\Models\User::class);
    }
}

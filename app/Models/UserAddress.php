<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'address',
        'subdistrict',
        'district',
        'city',
        'province',
        'city',
        'country',
        'phone',
        'wa_notification',
     ];

     public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

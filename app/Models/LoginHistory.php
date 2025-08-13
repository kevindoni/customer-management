<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    use SoftDeletes;

    public $guarded = [];

    function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

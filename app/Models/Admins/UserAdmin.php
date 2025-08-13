<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAdmin extends Model
{
    protected $fillable = [
        'nin',
        'dob',
        'gender',
        'bio',
     ];

     public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

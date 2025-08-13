<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCustomer extends Model
{
    use SoftDeletes;
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

    public function payments():HasMany
    {
        return $this->hasMany(\App\Models\Billings\Payment::class);
    }

    public function invoices():HasMany
    {
        return $this->hasMany(\App\Models\Billings\Invoice::class);
    }
}

<?php

namespace App\Models\Customers;

use App\Models\Servers\Mikrotik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoIsolir extends Model
{
    use SoftDeletes;
    public $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function mikrotik(): BelongsTo
    {
        return $this->belongsTo(Mikrotik::class);
    }
}

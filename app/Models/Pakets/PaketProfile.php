<?php

namespace App\Models\Pakets;

use App\Models\Servers\Mikrotik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PaketProfile extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['pakets'];
    protected $dates = ['deleted_at'];
    protected $fetchMethod = 'get'; // get, cursor, lazy or chunk

    public $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    //public function mikrotik(): BelongsTo
    //{
    //     return $this->belongsTo(Mikrotik::class);
    // }
    public function mikrotiks(): BelongsToMany
    {
        return $this->belongsToMany(Mikrotik::class, Paket::class);
    }

    public function paket(): HasOne
    {
        return $this->hasOne(Paket::class);
    }

    public function pakets(): HasMany
    {
        return $this->hasMany(Paket::class);
    }

    public function mikrotik_paket($mikrotik)
    {
        return $this->pakets()->where('mikrotik_id', $mikrotik->id)->first();
    }

    public function customer_pakets(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Customers\CustomerPaket::class,
            \App\Models\Pakets\Paket::class,
            'paket_profile_id',
            'paket_id',
            'id', /* Local key on mikrotiks table... */
            'id' /* Local key on customer_pppoes table... */
        );
    }
}

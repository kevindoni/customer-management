<?php

namespace App\Models\Servers;

use App\Services\CustomerPaketService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Mikrotik extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['pakets', 'mikrotik_monitoring', 'wan_monitorings', 'auto_isolir', 'mikrotik_client_histories'];
    protected $dates = ['deleted_at'];
    protected $fetchMethod = 'get'; // get, cursor, lazy or chunk

    public $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function paket_profiles(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Pakets\PaketProfile::class, \App\Models\Pakets\Paket::class);
    }

    public function pakets(): HasMany
    {
        return $this->hasMany(\App\Models\Pakets\Paket::class);
    }

    public function customer_pakets(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Customers\CustomerPaket::class,
            \App\Models\Pakets\Paket::class,
            'mikrotik_id',
            'paket_id',
            'id',
            'id'
        );
    }

    public function customer_paket(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Customers\CustomerPaket::class,
            \App\Models\Pakets\Paket::class,
            'mikrotik_id',
            'paket_id',
            'id',
            'id'
        );
    }

    public function paketsOrderByPrice(): HasMany
    {
        return $this->hasMany(\App\Models\Pakets\Paket::class)->orderByRaw('CAST(price as DECIMAL(8,2)) ASC')->orderBy('name', 'ASC');
    }

    public function mikrotik_monitoring(): HasOne
    {
        return $this->hasOne(\App\Models\Servers\MikrotikMonitoring::class);
    }

    public function wan_monitorings(): HasMany
    {
        return $this->hasMany(\App\Models\Servers\WanMonitoring::class);
    }

    public function auto_isolir(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\AutoIsolir::class);
    }

    public function mikrotik_client_histories(): HasMany
    {
        return $this->hasMany(\App\Models\Servers\MikrotikClientHistory::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::restoring(function ($mikrotik) {
            foreach ($mikrotik->pakets()->whereDeletedAt($mikrotik->deleted_at)->withTrashed()->get() as $paket) {
                foreach ($paket->customer_pakets()->whereDeletedAt($paket->deleted_at)->withTrashed()->get() as $customerPaket) {
                    (new CustomerPaketService)->restore_deleted_customer_paket($customerPaket);
                }
                $paket->restore();
            }
        });
    }
}

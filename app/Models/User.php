<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\CustomerPaketService;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Sanctum\HasApiTokens;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens;
    use HasFactory, Notifiable, SoftDeletes, CascadeSoftDeletes;
    use HasSlug;

    protected $cascadeDeletes = ['user_address', 'user_admin', 'user_customer', 'customer_pakets', 'user_boot_message', 'login_histories'];
    protected $dates = ['deleted_at'];
    protected $fetchMethod = 'get'; // get, cursor, lazy or chunk
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function getRedirectRoute()
    {
        /* return match($this->hasRole) {
            'admin' => 'dashboard',
            'customer' => 'customer.dashboard',
            // ...
        };
        */
        return 'customer.dashboard';
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('username');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function activation()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function user_address(): HasOne
    {
        return $this->hasOne(\App\Models\UserAddress::class);
    }

    public function user_admin(): HasOne
    {
        return $this->hasOne(\App\Models\Admins\UserAdmin::class);
    }

    public function user_customer(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\UserCustomer::class);
    }


    function customer_pakets(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CustomerPaket::class);
    }

    function customer_ppp_pakets(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Customers\CustomerPppPaket::class,
            \App\Models\Customers\CustomerPaket::class,
            'user_id',
            'customer_paket_id',
            'id', /* Local key on mikrotiks table... */
        );
    }

    function customer_static_pakets(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Customers\CustomerStaticPaket::class,
            \App\Models\Customers\CustomerPaket::class,
            'user_id',
            'customer_paket_id',
            'id', /* Local key on mikrotiks table... */
        )->withTrashed();
    }

    function customer_paket_addresses(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Customers\CustomerPaketAddress::class,
            \App\Models\Customers\CustomerPaket::class,
            'user_id',
            'customer_paket_id',
            'id', /* Local key on mikrotiks table... */
        )->withTrashed();
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Billings\Invoice::class,
            \App\Models\Customers\CustomerPaket::class,
            'user_id',
            'customer_paket_id',
            'id', /* Local key on mikrotiks table... */
        );
    }



    public function user_boot_message(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\UserBootMessage::class);
    }

    function login_histories(): HasMany
    {
        return $this->hasMany(\App\Models\LoginHistory::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::restoring(function ($user) {
            $user->user_address()->withTrashed()->restore();
            $user->user_customer()->withTrashed()->restore();

            foreach ($user->customer_pakets()->whereHas('paket')->whereDeletedAt($user->deleted_at)->withTrashed()->get() as $customerPaket) {
                (new CustomerPaketService)->restore_deleted_customer_paket($customerPaket);
            }

            $user->login_histories()->withTrashed()->restore();
            $user->user_boot_message()->withTrashed()->restore();
        });
    }
}

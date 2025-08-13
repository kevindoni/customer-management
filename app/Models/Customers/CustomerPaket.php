<?php

namespace App\Models\Customers;

use App\Models\Websystem;
use Spatie\Sluggable\HasSlug;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
//use App\Models\Servers\Mikrotik;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Mikrotiks\MikrotikPppService;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//use Illuminate\Database\Eloquent\Relations\HasOneThrough;
//use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CustomerPaket extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    use HasSlug;

    protected $cascadeDeletes = ['customer_ppp_pakets', 'customer_static_pakets', 'customer_paket_addresses', 'invoices', 'mikrotik_client_histories'];
    protected $dates = ['deleted_at'];
    protected $fetchMethod = 'get'; // get, cursor, lazy or chunk
    public $guarded = [];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['user_id', 'paket_id'])
            ->saveSlugsTo('slug')
            ->usingSuffixGenerator(
                fn(string $slug, int $iteration) => bin2hex(random_bytes(6))
            )
            ->doNotGenerateSlugsOnUpdate();
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    function customer_ppp_paket(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\CustomerPppPaket::class);
    }

    function customer_ppp_pakets(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CustomerPppPaket::class);
    }

    public function customer_static_paket(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\CustomerStaticPaket::class);
    }

    public function customer_static_pakets(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CustomerStaticPaket::class);
    }

    function customer_paket_address(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\CustomerPaketAddress::class);
    }

    function customer_paket_addresses(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CustomerPaketAddress::class);
    }

    public function customer_billing_address(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\CustomerPaketAddress::class, 'customer_paket_id', 'id')->whereAddressType('billing-address');
    }

    public function customer_installation_address(): HasOne
    {
        return $this->hasOne(\App\Models\Customers\CustomerPaketAddress::class, 'customer_paket_id', 'id')->whereAddressType('installation-address');
    }

    public function internet_service(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pakets\InternetService::class);
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pakets\Paket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /* function billing_pakets(): HasMany
    {
        return $this->hasMany(BillingPaket::class);
    }*/

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function mikrotik()
    {
        return $this->paket->mikrotik();
    }

    /*public function needsRenew()
    {
        if (!$this->next_billed_at) {
            return true;
        }
        $nextBillingDate = Carbon::parse($this->next_billed_at)->add($this->getRenewalPeriod());
        return $nextBillingDate->isPast() && $this->status === 'active' && Carbon::parse($this->expired_date)->isFuture();
    }
*/
    public function activation($activationDate = null)
    {
        $this->activation_date = $activationDate ? $activationDate : now();
        $this->status = 'active';
        return $this->save();
    }

    public function cancel()
    {
        $this->auto_renew = false;
        $this->status = 'cancelled';
        return $this->save();
    }

    public function suspend()
    {
        $this->status = 'suspended';
        return $this->save();
    }

    public function resume()
    {
        if ($this->status === 'suspended') {
            $this->status = 'active';
            return $this->save();
        }
        return false;
    }

    public function checkSubscriptionStatus()
    {
        $expiredDate = $this->paylater_date ? (Carbon::parse($this->expired_date)->gt(Carbon::parse($this->paylater_date)) ? $this->expired_date : $this->paylater_date) : $this->expired_date;
        if (Carbon::parse($expiredDate)->isPast()) {
            $this->status = 'expired';
            //$this->online = false;
            $this->save();
        }
        return $this->status;
        /*else {
            $this->status = 'active';
            $this->save();
            return $this->status;
        }*/
    }

    public function setSubscriptionStatus()
    {
        // $mikrotik = $this->paket->mikrotik;
        $expiredDate = $this->paylater_date ? (Carbon::parse($this->expired_date)->gt(Carbon::parse($this->paylater_date)) ? $this->expired_date : $this->paylater_date) : $this->expired_date;
        if (Carbon::parse($expiredDate)->isPast()) {
            // if ($this->internet_service->value === 'ppp'){
            //     $profileIsolir = $mikrotik->auto_isolir->profile_id;
            //     $username = $this->customer_ppp_paket->username;
            //      (new MikrotikPppService())->updateProfileSecret($mikrotik, $username, $profileIsolir);
            // } else if ($this->internet_service->value === 'ip_static'){
            //
            //  }
            $this->status = 'expired';
            //$this->online = false;
            $this->save();
            return $this->status;
        } else {
            // if ($this->internet_service->value === 'ppp'){
            //     $username = $this->customer_ppp_paket->username;
            //     $profileName = $this->paket->paket_profile->profile_name;
            //     (new MikrotikPppService())->updateProfileSecret($mikrotik, $username, $profileName);
            // } else if ($this->internet_service->value === 'ip_static'){
            //
            // }
            $this->status = 'active';
            $this->save();
            return $this->status;
        }
    }

    public function setActive()
    {
        $this->status = 'active';
        $this->save();
    }
    public function isActive()
    {
        //return $this->status === 'active' && Carbon::parse($this->expired_date)->isFuture();
        return $this->status === 'active';
    }

    public function needsBilling($invoicePeriod, $nextBilledAt)
    {
        if (!$this->next_billed_at) {
            return true;
        }

        $customerPaketInvoice = Invoice::whereCustomerPaketId($this->id)
            ->wherePeriode($invoicePeriod->format('Y-m-d'))
            ->first();

        if (!$customerPaketInvoice) {
            return $nextBilledAt->isPast();
        } else {
            return false;
        }
    }

    /*public function needsReminder()
    {
        if ($this->status === 'paid') {
            return false;
        }

        $remainingDay = WhatsappGatewayGeneral::first()->remaining_day;
        $remainingDay = Carbon::parse($this->expired_date)->subDays($remainingDay)->startOfDay();
        return $remainingDay->lte(Carbon::now()->startOfDay()) && $this->isActive();
    }*/
    public function getRenewalPeriod()
    {
        switch ($this->renewal_period) {
            case 'monthly':
                return '1 month';
            case 'bimonthly':
                return '2 month';
            case 'quarterly':
                return '3 months';
            case 'semi-annually':
                return '6 months';
            case 'annually':
                return '1 year';
            default:
                return '1 month';
        }
    }

    public function mikrotik_client_histories(): HasMany
    {
        return $this->hasMany(\App\Models\Servers\MikrotikClientHistory::class);
    }

    public function isPpp()
    {
        return $this->internet_service->value === 'ppp';
    }

    public function isIpStatic()
    {
        return $this->internet_service->value === 'ip_static';
    }

    /* public function restoreCustomerPaket()
    {
        $this->customer_static_pakets()->withTrashed()->restore();
            $this->customer_ppp_pakets()->withTrashed()->restore();
    }*/

    protected static function boot()
    {
        parent::boot();

        static::restoring(function ($customer_paket) {
            // dd($customer_paket);
            $customer_paket->customer_static_paket()->withTrashed()->restore();
            $customer_paket->customer_ppp_paket()->withTrashed()->restore();
            $customer_paket->customer_paket_addresses()->withTrashed()->restore();
            $customer_paket->invoices()->withTrashed()->restore();
        });
    }
}

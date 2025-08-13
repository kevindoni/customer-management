<?php

namespace App\Models\Customers;

use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPppPaket extends Model
{
    use HasSlug, SoftDeletes;

    public $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('username')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->usingSuffixGenerator(
                fn(string $slug, int $iteration) => bin2hex(random_bytes(4))
            );
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    function paket()
    {
        return $this->customer_paket->paket();
    }

    //public function user()
   // {
   //     return $this->customer_paket->user();
   // }

    function customer_paket(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\CustomerPaket::class)->withTrashed();
    }

    public function ppp_type(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pakets\PppType::class);
    }
}

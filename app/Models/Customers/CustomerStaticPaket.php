<?php

namespace App\Models\Customers;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerStaticPaket extends Model
{
    public $guarded = [];
    use HasSlug, SoftDeletes;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('ip_address')
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

    function customer_paket(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\CustomerPaket::class);
    }
}

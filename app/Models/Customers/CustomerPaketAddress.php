<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPaketAddress extends Model
{
    use SoftDeletes;
    public $guarded = [];


}

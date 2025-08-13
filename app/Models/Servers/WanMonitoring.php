<?php

namespace App\Models\Servers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WanMonitoring extends Model
{
    use SoftDeletes;
    public $guarded = [];
}

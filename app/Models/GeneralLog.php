<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralLog extends Model
{
    protected $casts = [
        'log_data' => 'array'
    ];
    protected $fillable = [
        'log_type',
        'log_data',
        'author',
    ];
}

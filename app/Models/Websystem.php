<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Websystem extends Model
{
    public $guarded = [];

    public function isDriverMikrotik()
    {
      //  $this->isolir_driver === 'mikrotik' ? return true : return false;
        if ($this->isolir_driver === 'mikrotik'){
            return true;
        } else {
            return false;
        }
    }
}

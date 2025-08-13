<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    public $guarded = [];



    public function isActive()
    {
        return $this->is_active;
    }

    public function setActive()
    {
        $this->is_active = true;
        $this->save();
    }
    public function setNonActive()
    {
        $this->is_active = false;
        $this->save();
    }
    public function active($paymentGateway)
    {
        return PaymentGateway::whereValue($paymentGateway)->first()->isActive;
    }

    public function disable()
    {
        $this->is_active = false;
        $this->save();
    }

   // public function methode($value)
   // {
   //     $this->methode = $value;
   //     $this->save();
   // }
}

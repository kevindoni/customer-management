<?php

namespace App\Traits;

use App\Models\Customers\CustomerPppPaket;
use Illuminate\Support\Str;

trait CustomerPaketTrait
{
    public function getRenewalPeriod($renewalPeriod)
    {
        switch ($renewalPeriod) {
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

    public function generateUsername($string): string
    {
        $username = strtolower(str_replace(' ', '-', $string));
        $count = 1;
        while ($this->usernameExists($username)) {
            $username = strtolower(str_replace(' ', '-', $string)) . '-' . $count;
            $count++;
        }
        return $username;
    }

    public function generatePassword()
    {
        $password = strtolower(str(Str::random(6))->slug());
        return $password;
    }

    protected function usernameExists($username)
    {
        return CustomerPppPaket::where('username', $username)->exists();
    }

    public function getQuantity($renewalPeriod)
    {
        switch ($renewalPeriod) {
            case 'monthly':
                return 1;
            case 'bimonthly':
                 return 2;
            case 'quarterly':
                 return 3;
            case 'semi-annually':
                return 6;
            case 'annually':
                 return 12;
            default:
                 return 1;
        }
    }
}


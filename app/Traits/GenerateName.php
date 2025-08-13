<?php

namespace App\Traits;

use App\Models\BillingPaket;

trait GenerateName
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateName($string)
    {
        $path = strtolower(str_replace([' ', '-', '.', '/'], '_', $string));
        $path = str_replace('__', '_', $path);
        $count = 1;

        while ($this->nameExists($path)) {
            $path =  $path . '_' . $count;
            $count++;
        }

        return $path;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected function nameExists($path)
    {
        return BillingPaket::where('invoice_path', $path)->exists();
    }
}

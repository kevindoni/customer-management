<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class Dashboard extends Component
{
    public function render()
    {
        $notCompleteAddress = false;
        $notCompleteProfile = false;

        $tableAddress = 'user_addresses'; // change it to your db table
        $columnsAddress = collect(Schema::getColumnListing($tableAddress));
        $columnsAddress = $columnsAddress->filter(function ($value, $key) {
            return in_array($value, ['id', 'created_at', 'updated_at']) === false;
        });
        //dd($columnsAddress);
        $userAddress = Auth::user()->user_address()->where(function ($query) use ($columnsAddress) {
            foreach ($columnsAddress as $columnAddress) {
                $query->orWhereNull($columnAddress);
            }
        })->get();


        $tableCustomers = 'user_customers'; // change it to your db table
        $columnsCustomers = collect(Schema::getColumnListing($tableCustomers));
        $columnsCustomers = $columnsCustomers->filter(function ($value, $key) {
            return in_array($value, ['id', 'bio', 'created_at', 'updated_at']) === false;
        });
        //dd($columnsAddress);
        $customer = Auth::user()->user_customer()->where(function ($query) use ($columnsCustomers) {
            foreach ($columnsCustomers as $columnCustomer) {
                $query->orWhereNull($columnCustomer);
            }
        })->get();


     // dd($customer);
        if($userAddress->count()) $notCompleteAddress = true;
        if($customer->count()) $notCompleteProfile = true;

        //dd($data);
        return view('livewire.customer.dashboard', compact('notCompleteAddress','notCompleteProfile'));
    }
}

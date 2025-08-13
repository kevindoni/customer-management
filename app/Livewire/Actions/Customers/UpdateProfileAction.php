<?php

namespace App\Livewire\Actions\Customers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Customers\UserCustomer;
use App\Models\UserAddress;
use Illuminate\Support\Carbon;

class UpdateProfileAction
{
    /**
     * Validate and create a newly registered user from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function handle($user, array $input): User
    {
        // dd($user);
        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'] ?? null,
        ])->save();

        $user->user_customer->forceFill([
            'gender' => $input['gender'],
            'nin' => $input['nin'],
            'dob' => Carbon::parse($input['dob'])->format('Y-m-d'),
        ])->save();

        return $user;
    }
}

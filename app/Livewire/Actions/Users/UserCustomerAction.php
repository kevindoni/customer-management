<?php

namespace App\Livewire\Actions\Users;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Customers\UserCustomer;


class UserCustomerAction
{
    /**
     * Add user to customer table and role
     *
     * @param  array<string, string>  $input
     */
    public function addUserCustomer(User $user, array $input)
    {
        $user->assignRole('customer');
        $userCustomer = new UserCustomer();
        $user->user_address()->save($userCustomer);
        $this->updateUserCustomer($user, $input);
    }

    public function updateUserCustomer(User $user, array $input)
    {
        $user->user_customer->forceFill([
            'dob' => Carbon::parse($input['dob']),
            'gender' => $input['gender']
        ])->save();
    }
}

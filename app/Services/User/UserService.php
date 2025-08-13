<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Admins\UserAdmin;
use App\Traits\StandardPhoneNumber;
use Illuminate\Support\Facades\Hash;
use App\Models\Customers\UserCustomer;
use App\Livewire\Actions\Users\UserAction;
use App\Livewire\Actions\Users\UserAdminAction;
use App\Livewire\Actions\Users\UserAddressAction;
use App\Livewire\Actions\Users\UserCustomerAction;
use Illuminate\Support\Facades\Log;


class UserService
{
    public function addUserAdmin(array $input): User
    {
        $user = (new UserAction())->addUser($input);
        (new UserAddressAction())->addUserAddress($user, $input);
        (new UserAdminAction())->addUserAdmin($user, $input);
        return $user;
    }

    public function updateUserAdmin(User $user, array $input)
    {
        //Log::info($input);
        (new UserAction())->updateUser($user, $input);
        (new UserAddressAction())->updateUserAddress($user, $input);
        (new UserAdminAction())->updateUserAdmin($user, $input);
    }

    public function addUserCustomer(array $input): User
    {
        $user = (new UserAction())->addUser($input);
        (new UserAddressAction())->addUserAddress($user, $input);
        (new UserCustomerAction())->addUserCustomer($user, $input);
        return $user;
    }

    public function updateUserCustomer(User $user, array $input, $changeInstallationAddress, $changeBillingAddress)
    {
        (new UserAction())->updateUser($user, $input);

       // if ($user->customer_paket && $input['checkbox_address_installation'])
        (new UserAddressAction())->updateUserAddress($user, $input, $changeInstallationAddress, $changeBillingAddress);

       // if ($user->customer_paket && $input['checkbox_billing_installation'])
        (new UserCustomerAction())->updateUserCustomer($user, $input);
    }
}

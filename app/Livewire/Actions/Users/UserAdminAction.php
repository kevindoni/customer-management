<?php

namespace App\Livewire\Actions\Users;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Admins\UserAdmin;

class UserAdminAction
{
    /**
     * Add user to admin table and role
     *
     * @param  array<string, string>  $input
     */
    public function addUserAdmin(User $user, array $input)
    {
        $userAdmin = new UserAdmin();
        $user->user_admin()->save($userAdmin);
        $this->updateUserAdmin($user, $input);
    }

    public function updateUserAdmin(User $user, array $input)
    {
        $user->syncRoles($input['role']);
        $user->user_admin->forceFill([
            'dob' => Carbon::parse($input['dob']),
            'gender' => $input['gender']
        ])->save();
    }
}

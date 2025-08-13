<?php

namespace App\Livewire\Actions\Users;

use App\Models\User;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Hash;


class UserAction
{


    public $generalLogServices;

    public function __construct(GeneralLogServices $generalLogServices = null) {
        $this->generalLogServices = $generalLogServices ?? new GeneralLogServices;
    }
    /**
     * Validate and create a newly registered user from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function addUser(array $input): User
    {
     //   dd($input);
      $user=  User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'] ?? null,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'disabled' => $input['disabled'] ?? true,
        ]);
        return $user;
    }


    public function updateUser(User $user, array $input)
    {
        if (!empty($input['password'])) {
            $user->forceFill([
                'password' => Hash::make($input['password'])
            ])->save();
        }

        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'] ?? null,
            'email' => $input['email'],
        ])->save();
    }


    /**
     * Validate delete user from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function delete(User $user)
    {

        $userName = $user->full_name;
        $user->delete();
        $this->generalLogServices->admin_action($this->generalLogServices::DELETE_CUSTOMER, "Delete " . $userName);
    }

    public function force_delete(User $user)
    {
        $user->forceDelete();
    }



}

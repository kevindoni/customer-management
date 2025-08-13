<?php

namespace App\Livewire\Actions\Users;

use App\Models\User;
use Illuminate\Support\Carbon;

class UpdateProfileAdminAction
{
    /**
     * Validate and create a newly registered user from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function handle(User $user, array $input): User
    {
        // dd($user);
        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'] ?? null,
        ])->save();

        $user->user_admin->forceFill([
            'gender' => $input['gender'],
            'nin' => $input['nin'],
            'dob' => Carbon::parse($input['dob'])->format('Y-m-d'),
        ])->save();

        return $user;
    }
}

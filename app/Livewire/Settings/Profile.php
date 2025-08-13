<?php

namespace App\Livewire\Settings;

use App\Http\Requests\Customers\EditProfileRequest;
use App\Livewire\Actions\Customers\UpdateProfileAction;
use App\Livewire\Actions\Users\UpdateProfileAdminAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{

    public $input = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if ($user->user_customer){
        $this->input = array_merge([
            'gender' => $user->user_customer->gender,
            'nin' => $user->user_customer->nin,
            'dob' => Carbon::parse($user->user_customer->dob)->format('Y-m-d'),
        ], $user->withoutRelations()->toArray());
        } else {
            $this->input = array_merge([
                'gender' => $user->user_admin->gender,
                'nin' => $user->user_admin->nin,
                'dob' => Carbon::parse($user->user_admin->dob)->format('Y-m-d'),
                ], $user->withoutRelations()->toArray());
            
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        (new EditProfileRequest())->validate($this->input);
        
        if ($user->user_customer){
        (new UpdateProfileAction())->handle($user, $this->input);
        } else {
            (new UpdateProfileAdminAction)->handle($user, $this->input);
        }

        $this->dispatch('profile-updated', name: $user->fullname);
    }
}

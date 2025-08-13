<?php

namespace App\Listeners;

//use IlluminateAuthEventsLogin;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class LoginSuccessful
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $lastLogin = LoginHistory::find(Auth::user()->id);
        if ($lastLogin) {
            $lastLogin->forceFill([
                'ip_address' => request()->ip(),
                'user_agent' => request()->server('HTTP_USER_AGENT'),
                'created_at' => \Carbon\Carbon::now(),
            ])->save();
        } else {
            LoginHistory::insert([
                'user_id' => Auth::user()->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->server('HTTP_USER_AGENT'),
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}

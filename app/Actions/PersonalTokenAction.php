<?php
namespace App\Actions;

use Illuminate\Support\Str;

class PersonalTokenAction
{
    /**
     * @param mixed $name
     */
    public function create($user)
    {
        $token = $user->createToken($user->username)->plainTextToken;
        [$id, $token] = explode('|', $token, 2);
        return [
            'id_token' => $id,
            'login_token' => $token,
        ];
    }
}

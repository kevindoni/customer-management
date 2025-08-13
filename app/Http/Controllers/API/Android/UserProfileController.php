<?php

namespace App\Http\Controllers\API\Android;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Android\UserProfileResource;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
         return response()->json(
                new UserProfileResource($user)
            , 200);
        
    }
}
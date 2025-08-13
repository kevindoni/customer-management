<?php

namespace App\Http\Controllers\Api\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Android\UserBillingResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\AndroidApiResponse;

class UserBillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AndroidApiResponse $androidApiResponse)
    {
        $user = Auth::user();
        //$userBillings = $user->invoices;
        $userBillings = $user->user_customer->invoices;

        if ($userBillings) {
            return $androidApiResponse->successResourceResponse(
                UserBillingResource::collection($userBillings)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

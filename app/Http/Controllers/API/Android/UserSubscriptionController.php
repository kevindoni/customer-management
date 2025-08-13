<?php

namespace App\Http\Controllers\API\Android;

use App\Models\User;
use App\Models\Customers\CustomerPaket;
use App\Http\Resources\Android\UserResource;
use App\Http\Resources\Android\CustomerPaketResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\AndroidApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{

    /**
     * Get user.
     */
    public function getUser(Request $request, AndroidApiResponse $androidApiResponse)
    {
        $user = Auth::user();
        Log::info("access from android");
        return $androidApiResponse->successResourceResponse(new UserResource($user));

    }
    /**
     * Get user subscription.
     */
    public function userCustomerPaket(Request $request, AndroidApiResponse $androidApiResponse)
    {
        $user = Auth::user();
        $customerPakets = $user->customer_pakets;
       // $customerPakets = CustomerPaket::where('user_id', 320)->get();
       // Log::info($customerPakets);
        if ($customerPakets){
            return $androidApiResponse->successResourceResponse(
               CustomerPaketResource::collection($customerPakets)
            );
        }// else {
          //   return $androidApiResponse->successResourceResponse(
         //       null
          //  );
       //}

       // return $androidApiResponse->succesResourceResponse(
       //     CustomerPaketResource::collection($customerPakets)
       // );

        //return $androidApiResponse->succesResourceResponse(new CustomerPaketResource($customerPakets->first()));

    }

}

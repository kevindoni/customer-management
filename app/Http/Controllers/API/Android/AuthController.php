<?php

namespace App\Http\Controllers\API\Android;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Actions\PersonalTokenAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\ApiCodes;
use Illuminate\Support\Facades\Validator;
use App\Http\Responses\ApiResponseBuilder;
use App\Http\Responses\AndroidApiResponse;
use App\Http\Resources\Android\UserResource;
use App\Http\Resources\Android\CustomerPaketResource;
use App\Http\Resources\Android\PersonalAccessTokenResource;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $createUserToken;
    public $input = [];
    public function __construct()
    {
        $this->createUserToken = new PersonalTokenAction;
    }

    /**
     * Login response.
     */
    public function login(Request $request, AndroidApiResponse $androidApiResponse)
    {
        Log::info($request->all());
        $validasi = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $androidApiResponse->errorResponse($val[0], 400);
           // return ApiResponseBuilder::errorWithMessage(ApiCodes::BAD_REQUEST,  $val[0], ApiCodes::BAD_REQUEST);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user->tokens()->delete();
            $userToken = $this->createUserToken->create($user);
            $user['token'] = $userToken;
          
            //return $androidApiResponse->succesResourceResponse(new PersonalAccessTokenResource($user));
            return response()->json(
                new PersonalAccessTokenResource($user)
            , 200);
            
        } else {
           // $msg = 'Login failed!';
            //return ApiResponseBuilder::errorWithMessage(ApiCodes::BAD_REQUEST,  $msg, ApiCodes::BAD_REQUEST);
            //return AndroidApiResponse::errorWithMessage(ApiCodes::BAD_REQUEST,  $msg, ApiCodes::BAD_REQUEST);
          // return $androidApiResponse->errorResponse($msg, 400);
           return response()->json([
                'message' => 'Login failed!',
            ], 400);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, AndroidApiResponse $androidApiResponse)
    {
        $validasi = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'min:8'],
            'phone' => ['required', 'required'],
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
           // return $androidApiResponse->errorResponse($val[0], 400);
             return response()->json([
                'message' => $val[0],
            ], 400);
        }

        try {
            DB::beginTransaction();
            $user =  (new UserService())->addUserCustomer($request->all());
            $userToken = $this->createUserToken->create($user);
            $user['token'] = $userToken;
            DB::commit();
           // return ApiResponseBuilder::successWithMessage([
           //     'user' =>  new UserResource($user),
           // ], 'User Register Successfully', 201);
           //return $androidApiResponse->succesResourceResponse(new UserResource($user));
           return response()->json(
                new UserResource($user)
            , 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getMessage());
           // return ApiResponseBuilder::errorWithMessage(ApiCodes::NOT_ACCEPTABLE, 'Something wrong, please //contact administrator.', ApiCodes::NOT_ACCEPTABLE);
          // return $androidApiResponse->errorResponse("Something wrong, please contact administrator", 400);
           return response()->json([
                'message' => 'Something wrong, please contact administrator!',
            ], 400);
           
        }
    }

    public function sanctumToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
           // return ApiResponseBuilder::errorWithMessage(ApiCodes::NOT_ACCEPTABLE, 'The provided credentials //are incorrect.', ApiCodes::NOT_ACCEPTABLE);
           // return $androidApiResponse->errorResponse("The provided credentials are incorrect.", 400);
           return response()->json([
                'message' => 'The provided credentials are incorrect',
            ], 400);
        }
        return $user->createToken($request->device_name)->plainTextToken;
    }
}

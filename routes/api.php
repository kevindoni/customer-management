<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::name('android.')->prefix('android')->group(function () {
    Route::controller(App\Http\Controllers\API\Android\AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(App\Http\Controllers\API\Android\UserSubscriptionController::class)->name('user.')->prefix('user')->group(function () {
            Route::get('/subscription', 'userCustomerPaket');
            Route::get('/get', 'getUser');
        });

        Route::controller(App\Http\Controllers\API\Android\UserProfileController::class)->name('user.')->prefix('user')->group(function () {
            Route::get('/profile', 'index');
        });

        Route::controller(App\Http\Controllers\API\Android\UserBillingController::class)->name('user.')->prefix('user')->group(function () {
            Route::get('/invoices', 'index');
        });

        Route::controller(App\Http\Controllers\API\Android\PaymentController::class)->name('user.')->prefix('user')->group(function () {
            Route::get('/invoice/payment/{invoice}', 'requestPayment');
            Route::post('/invoice/order/add', 'createOrder');
            Route::get('/invoice/order/{order}', 'requestOrder');
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

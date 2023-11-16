<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\UserForgotPasswordViaEmailController;
use Modules\Auth\Http\Controllers\UserVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::withoutMiddleware([CheckSuspended::class, CheckVerified::class])->group(function () {




    Route::controller(AuthController::class)->group(function () {

        Route::post('phone-register', 'RegistrationViaPhone');
        Route::post('email-register', 'RegistrationViaEmail');
        Route::post('login', 'Login');


    });





    Route::controller(UserVerificationController::class)->group(function () {
        Route::post('resend-email-otp', 'ResendEmailOtp');
        Route::post('check-email-otp', 'CheckEmailOtpVerification');

    });

    Route::controller(UserForgotPasswordViaEmailController::class)->group(function () {
        Route::post('send-rest-otp', 'SendRestPasswordViaEmailOTP');
        Route::post('check-rest-otp', 'CheckOtpForRestPasswordViaEmail');
        Route::post('change-password-otp', 'changePasswordViaEmail');

    });
});

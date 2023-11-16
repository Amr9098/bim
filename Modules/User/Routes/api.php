<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\UserPasswordController;

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

Route::controller(UserController::class)->group(function () {

    Route::get('user-data', 'GetAuthUserData');
    Route::put('user-data', 'updateAuthUserData');


});
Route::controller(UserPasswordController::class)->group(function () {

    Route::put('user-change-password', 'authUserChangePassword');


});

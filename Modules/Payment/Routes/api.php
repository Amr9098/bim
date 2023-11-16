<?php

use App\Http\Middleware\CheckAdminToken;
use App\Http\Middleware\CheckSuspended;
use App\Http\Middleware\CheckVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

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
    Route::middleware([CheckAdminToken::class])->group(function () {

        Route::apiResource("payment", PaymentController::class);

    });
});

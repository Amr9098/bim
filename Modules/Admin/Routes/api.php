<?php

use App\Http\Middleware\CheckAdminToken;
use App\Http\Middleware\CheckSuspended;
use App\Http\Middleware\CheckVerified;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AdminUserController;
use Modules\Admin\Http\Controllers\SuperAdminController;

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

Route::post('admin-login', [AdminController::class, 'login'])->withoutMiddleware([CheckSuspended::class, CheckVerified::class]);


Route::withoutMiddleware([CheckSuspended::class, CheckVerified::class])->group(function () {
    Route::middleware([CheckAdminToken::class])->group(function () {
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('view-all-users', 'ViewAllUsers');
            Route::delete('delete-user/{id}', 'deleteUser');
            Route::get('ban-user/{id}', 'banUser');
            Route::post('admin-add-user', 'addUser');
            Route::put('admin-edit-user/{id}', 'editUserData');
            Route::post('admin-user-password/{id}', 'AdminChangePassword');
            Route::get('admin-user-data/{id}', 'AdminUserDataById');
        });

        Route::controller(SuperAdminController::class)->group(function () {
            Route::post('add-admin', 'addAdmin');

        });
    });
});

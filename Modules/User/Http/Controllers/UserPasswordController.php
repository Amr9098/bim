<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\authUserChangePasswordRequest;

class UserPasswordController extends Controller
{


    public function authUserChangePassword(authUserChangePasswordRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        if (!Hash::check($validatedData['old_password'], $user->password)) {
            return response()->json(["message" => "Invalid old password"], 422);
        }

        $this->updatePassword($user, $validatedData['new_password']);

        Auth::logout();

        return response()->json(["message" => "New password changed successfully"], 201);
    }

    private function updatePassword($user, $newPassword)
    {
        $user->password =$newPassword;
        $user->save();
    }
}

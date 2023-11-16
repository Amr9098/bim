<?php

namespace App\Traits\User;

use App\Models\EmailVerificationCode;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

trait TokenTrait
{

    public function CreateUserToken($user)
    {
        JWTAuth::factory()->setTTL(60 * 24 * 100);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'code' => 1,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            "permissions" => $user->getAllPermissions()->pluck('name'),
            "roles" => implode(', ', $user->getRoleNames()->toArray()),
        ]);
    }
}

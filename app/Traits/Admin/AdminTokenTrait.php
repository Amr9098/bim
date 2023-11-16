<?php

namespace App\Traits\Admin;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

trait AdminTokenTrait
{

    public function CreateAdminToken($user)
    {
        JWTAuth::factory()->setTTL(60 * 24 * 100);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'id' => 1,
            'token' => $token,
            'username' => $user->name,
            'email' => $user->email,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            "permissions" => $user->getAllPermissions()->pluck('name'),
            "roles" => implode(', ', $user->getRoleNames()->toArray()),
        ]);
    }
}

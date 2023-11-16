<?php

namespace Modules\Admin\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Traits\Admin\AdminTokenTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\AdminLoginRequest;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class AdminController extends Controller
{
    use AdminTokenTrait;
    public function login(AdminLoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!Auth::guard('admin')->attempt($credentials)) {
                return response()->json(['message' => 'Email or password incorrect'], 422);
            } else {
                $user = Auth::guard('admin')->user();
                return $this->CreateAdminToken($user);
                // return response()->json($user);
            }
        } catch (JWTException $e) {
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}

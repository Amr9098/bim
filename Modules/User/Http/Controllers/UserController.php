<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Modules\User\Http\Requests\updateAuthUserRequest;
use Modules\User\Transformers\AuthUserDataResource;

class UserController extends Controller
{
    public function getAuthUserData()
    {
        $user = Auth::user();

        if (!$user) {
            return Response::json(['message' => 'User data not found'], 404);
        }
        return new AuthUserDataResource($user);
    }


    public function updateAuthUserData(updateAuthUserRequest $request)
    {
        $validReq = $request->validationData();
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User data not found'], 404);
        }

        DB::transaction(function () use ($user, $validReq) {
            $user->update([
                'first_name' => $validReq['first_name'],
                'last_name' => $validReq['last_name'],
            ]);
        });

        $user->refresh();
        return new AuthUserDataResource($user);
    }
}

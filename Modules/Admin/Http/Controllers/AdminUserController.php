<?php

namespace Modules\Admin\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AdminUserRequest;
use Modules\Admin\Transformers\AdminUserResource;
use Throwable;

class AdminUserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:admin', 'permission:login-dashboard']);
    }

    public function ViewAllUsers()
    {

        $allUsers = User::all();

        if ($allUsers->isEmpty()) {
            return response()->json(["message" => "No User Found "], 404);
        } else {

            return AdminUserResource::collection($allUsers);
        }
    }


    public function editUserData(AdminUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validReq = $request->validationData();

            $user->update([
                "first_name" => $validReq['first_name'],
                "last_name" => $validReq['last_name'],
                "phone" => $validReq['phone'],
                "email" => $validReq['email'],
            ]);

            return response()->json(["message" => "User data updated successfully"], 200);
        } catch (Throwable $e) {
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    public function addUser(AdminUserRequest $request)
    {

        try {
            $validReq = $request->validationData();
            $user = User::create([
                "first_name" => $validReq['first_name'],
                "last_name" => $validReq['last_name'],
                "phone" => $validReq['phone'],
                "email" => $validReq['email'],
                "password" => $validReq['password'],
                "verified" => true,
            ])->assignRole("user");
            return response()->json(["message" => $user->first_name . ' Added successful'], 201);
        } catch (Throwable $e) {
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }


    public function deleteUser($id)
    {

        $deletedUser = User::find($id);

        if (!$deletedUser) {
            return response()->json(["message" => "No User Found "], 404);
        } else {
            $deletedUser->delete();
            return response()->json(["message" => "User Deleted Successfully "], 200);
        }
    }
    public function banUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "No User Found"], 404);
        }

        $isBanned = $user->ban;
        $user->ban = !$isBanned;
        $user->save();

        $banStatus = $isBanned ? 'Unbanned' : 'Banned';
        $message = "User $user->first_name $banStatus Successfully";

        return response()->json(["message" => $message], 200);
    }


    public function AdminChangePassword(AdminUserRequest $request, $id){

        $validReq = $request->validationData();
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "No User Found "], 404);
        } else {
            $user->update([
                "password" => $validReq['new_password'],
            ]);

            return response()->json(["message" => "Password change Successfully "], 201);
        }


    }
    public function AdminUserDataById($id){

        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "No User Found "], 404);
        } else {
            return new AdminUserResource($user);
        }


    }




}

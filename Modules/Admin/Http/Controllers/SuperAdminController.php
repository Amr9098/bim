<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Admin;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AdminRequest;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:super-admin']);
    }
    
    public function addAdmin(AdminRequest $request)
    {

        $validatedData = $request->validated();

        try {
            $admin = Admin::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ]);

            $admin->assignRole("admin");

            return response()->json(['message' => 'Admin created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the admin.'], 500);
        }
    }




}

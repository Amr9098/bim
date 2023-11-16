<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $loginApp= 'login-app';
        $loginDashboard= 'login-dashboard';
        $AddAdmin= 'add-admin';


        Permission::create(['name' => $loginApp ]);
        Permission::create(['guard_name' => 'admin','name' => $loginDashboard ]);
        Permission::create(['guard_name' => 'admin','name' => $AddAdmin ]);



        $superAdmin='super-admin';
        $Admin='admin';
        $User='user';


         Role::create(['guard_name' => 'admin','name' => $superAdmin])
        ->givePermissionTo([$loginDashboard, $AddAdmin]);

         Role::create(['guard_name' => 'admin','name' => $Admin])
        ->givePermissionTo([$loginDashboard]);


         Role::create(['name' => $User])
        ->givePermissionTo([$loginApp]);

    }
}

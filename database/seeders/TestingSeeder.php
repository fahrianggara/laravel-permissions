<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\LabelPermission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        // $authorities = [
        //     'authorities' => [
        //         'Manage Users' => [
        //             'create user',
        //             'edit user',
        //             'delete user',
        //             'view user',
        //         ],
        //         'Manage Roles' => [
        //             'create role',
        //             'edit role',
        //             'delete role',
        //             'view role',
        //         ],
        //         'Manage Permissions' => [
        //             'create permission',
        //             'edit permission',
        //             'delete permission',
        //             'view permission',
        //         ]
        //     ]
        // ];

        // $labelPermission = [];
        // $listPermission = [];
        // $superadminPermissions = [];
        // $adminPermissions = [];

        // foreach ($authorities as $label => $permissions) {

        //     foreach ($permissions as $key => $permission) {

        //         $labelPermission[] = [
        //             "title" => $key,
        //             "created_at" => date('Y-m-d H:i:s'),
        //             "updated_at" => date('Y-m-d H:i:s'),
        //         ];

        //         $listPermission[] = [
        //             "name" => $permission,
        //             'guard_name' => 'web',
        //             "created_at" => date('Y-m-d H:i:s'),
        //             "updated_at" => date('Y-m-d H:i:s'),
        //         ];

        //         labelPermissions()->attach($key);

        //         $superadminPermissions[] = $permission;

        //         if (in_array($key, ['Manage Users'])) {
        //             $adminPermissions[] = $permission;
        //         }
        //     }
        // }

        // dd($labelPermission);

        // LabelPermission::insert($labelPermission);
        // Permission::insert($listPermission);

        // DB::table('users')->insert([
        //     [
        //         'name' => 'Super Admin',
        //         'email' => 'superadmin@mail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'remember_token' => Str::random(10),
        //         'photo' => 'avatar.png',
        //     ],
        //     [
        //         'name' => 'Admin',
        //         'email' => 'admin@mail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'remember_token' => Str::random(10),
        //         'photo' => 'avatar.png',
        //     ]
        // ]);

        // // SUPER ADMIN
        // $superadmin = Role::create([
        //     "name" => "superadmin",
        //     'guard_name' => 'web',
        //     "created_at" => date('Y-m-d H:i:s'),
        //     "updated_at" => date('Y-m-d H:i:s'),
        // ]);
        // // ADMIN
        // $admin = Role::create([
        //     "name" => "admin",
        //     'guard_name' => 'web',
        //     "created_at" => date('Y-m-d H:i:s'),
        //     "updated_at" => date('Y-m-d H:i:s'),
        // ]);

        // $superadmin->givePermissionTo($superadminPermissions);
        // $admin->givePermissionTo($adminPermissions);

        // User::find(1)->assignRole("superadmin");
        // User::find(2)->assignRole("admin");

        // dd("Super Admin", $superadminPermissions);
        // dd("Admin", $adminPermissions);
        // dd($listPermission);

        // // Create permissions groups
        // $groups = [
        //     'Manage Users',
        //     'Manage Roles',
        //     'Manage Permissions'
        // ];
        // foreach ($groups as $group) {
        //    LabelPermission::create(['title' => $group]);
        // }

        // // Create permission
        // $permissions = [
        //     'create user',
        //     'edit user',
        //     'delete user',
        //     'view user',
        //     'create role',
        //     'edit role',
        //     'delete role',
        //     'view role',
        //     'create permission',
        //     'edit permission',
        //     'delete permission',
        //     'view permission',
        // ];
        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        // Relations

    }
}

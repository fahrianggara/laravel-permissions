<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user
        Permission::create([
            'name' => 'create user'
        ])->labelPermissions()->attach(1);
        Permission::create([
            'name' => 'edit user'
        ])->labelPermissions()->attach(1);
        Permission::create([
            'name' => 'delete user'
        ])->labelPermissions()->attach(1);
        Permission::create([
            'name' => 'view user'
        ])->labelPermissions()->attach(1);

        // role
        Permission::create([
            'name' => 'create role'
        ])->labelPermissions()->attach(2);
        Permission::create([
            'name' => 'edit role'
        ])->labelPermissions()->attach(2);
        Permission::create([
            'name' => 'delete role'
        ])->labelPermissions()->attach(2);
        Permission::create([
            'name' => 'view role'
        ])->labelPermissions()->attach(2);

        // permission
        Permission::create([
            'name' => 'create permission'
        ])->labelPermissions()->attach(3);
        Permission::create([
            'name' => 'edit permission'
        ])->labelPermissions()->attach(3);
        Permission::create([
            'name' => 'delete permission'
        ])->labelPermissions()->attach(3);
        Permission::create([
            'name' => 'view permission'
        ])->labelPermissions()->attach(3);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'superadmin'
        ]);

        $admin = Role::create([
            'name' => 'admin'
        ]);
        $admin->givePermissionTo('create user');
        $admin->givePermissionTo('edit user');
        $admin->givePermissionTo('delete user');
        $admin->givePermissionTo('view user');

        Role::create([
            'name' => 'user'
        ]);
    }
}

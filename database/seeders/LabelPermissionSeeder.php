<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\LabelPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LabelPermission::create([
            'title' => 'Manage Users'
        ]);
        LabelPermission::create([
            'title' => 'Manage Roles'
        ]);
        LabelPermission::create([
            'title' => 'Manage Permissions'
        ]);
    }
}

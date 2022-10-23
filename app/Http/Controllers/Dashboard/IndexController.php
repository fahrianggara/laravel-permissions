<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\LabelPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class IndexController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index',[
            'roles' => Role::whereNotIn('name', ['superadmin'])->count(),
            'permissions' => Permission::count(),
            'users' => User::count(),
            'groups' => LabelPermission::whereHas("labelPermissions")->count(),
        ]);
    }
}

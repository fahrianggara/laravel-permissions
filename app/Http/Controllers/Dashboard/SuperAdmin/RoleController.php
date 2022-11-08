<?php

namespace App\Http\Controllers\Dashboard\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\LabelPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role', ['only' => ['index', 'show']]);
        $this->middleware('permission:create role', ['only' => 'store']);
        $this->middleware('permission:edit role', ['only' => 'update']);
        $this->middleware('permission:delete role', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.superadmin.roles.index', [
            'roles' => Role::whereNotIn('name', ['superadmin'])->get(),
            'permissions' => LabelPermission::whereHas('labelPermissions')->get(),
        ]);
    }

    public function fetchPermission($id)
    {
        if (request()->ajax()) {
            $role = Role::find($id);
            $permissions = LabelPermission::whereHas('labelPermissions')->get();
            $checked = $role->permissions->pluck('id')->toArray();

            $output = '';

            $output .= '
            <div id="edit_container" class="row" style="margin-left: -9px;margin-bottom: 4px">
        ';
            foreach ($permissions as $permission) {

                $output .= '
            <ul class="list-group mx-1">
                    <li class="list-group-item mt-1 bg-info text-white">
                    ' . $permission->title . '
                    </li>';

                foreach ($permission->labelPermissions as $item) {
                    $checkBoxCheck = in_array($item->id, old('permissions', $checked)) ? 'checked' : null;

                    if (old('permissions',  $checked)) {
                        $checkBox = '
                        <input id="edit' . $item->id . '" name="permissions[]"
                        class="form-check-input checkPermissionEdit" type="checkbox"
                        value="' . $item->id . '" ' . $checkBoxCheck . '>
                    ';
                    } else {
                        $checkBox = '
                        <input id="edit' . $item->id . '" name="permissions[]"
                        class="form-check-input checkPermissionEdit" type="checkbox"
                        value="' . $item->id . '">
                    ';
                    }

                    $output .= '
                    <li class="list-group-item">
                        <div class="form-check show_edit">
                            ' . $checkBox . '

                            <label for="edit' . $item->id . '" class="form-check-label checks">
                                ' . $item->name . '
                            </label>
                        </div>
                    </li>
                ';
                }
                $output .= '</ul>';
            }
            $output .= '</div>';

            echo $output;
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_space|max:15|min:3|unique:roles,name',
            'permissions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                $role = Role::create([
                    'name' => $request->name,
                ]);
                $role->givePermissionTo($request->permissions);

                return response()->json([
                    'status' => 200,
                    'message' => "New role has been added!",
                ]);
            } catch (\Throwable $th) {
                dd('Error: ' . $th);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        if (request()->ajax()) {
            if ($role) {
                return response()->json([
                    'status' => 200,
                    'data' => $role
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Role data not found!'
                ]);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_space|min:3|unique:roles,name,' . $id,
            'permissions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                $role = Role::find($id);

                $role->name = $request->input('name');
                $role->syncPermissions($request->permissions);
                $role->update();

                return response()->json([
                    'status' => 200,
                    'message' => "The role has been updated!",
                ]);
            } catch (\Throwable $th) {
                dd('Error: ' . $th);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (User::role($role->name)->count()) {
            return response()->json([
                'status' => 400,
                'message' => "The $role->name role cannot be deleted, because it is currently in use."
            ]);
        }

        DB::beginTransaction();
        try {
            $role->revokePermissionTo($role->permissions->pluck('name')->toArray());
            $role->delete();

            return response()->json([
                'status' => 200,
                'message' => "The $role->name role has been deleted!"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 400,
                'title' => "an error occurred while deleting data",
                'message' => "Message: $th"
            ]);
        } finally {
            DB::commit();
        }
    }
}

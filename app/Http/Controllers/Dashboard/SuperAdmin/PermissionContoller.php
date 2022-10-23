<?php

namespace App\Http\Controllers\Dashboard\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\LabelPermission as Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index', 'show']]);
        $this->middleware('permission:create permission', ['only' => 'store']);
        $this->middleware('permission:edit permission', ['only' => 'update']);
        $this->middleware('permission:delete permission', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.superadmin.permissions.index', [
            'labels' => Label::whereHas('labelPermissions')->get(),
            'labelcruds' => Label::with('labelPermissions')->get(),
        ]);
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
            'name' => 'required|alpha_space|min:3|unique:permissions,name',
            'label' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                $permission = Permission::create([
                    'name' => $request->name,
                ]);

                $permission->labelPermissions()->attach($request->label);

                return response()->json([
                    'status' => 200,
                    'message' => "New permission has been added!",
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
        $permission = Permission::with('labelPermissions')->find($id);

        if (request()->ajax()) {
            if ($permission) {
                return response()->json([
                    'status' => 200,
                    'data' => $permission
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Permission data not found!'
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
            'name' => 'required|alpha_space|min:3|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                $permission = Permission::find($id);

                $permission->name = $request->input('name');
                $permission->labelPermissions()->sync($request->input('label'));
                $permission->update();

                return response()->json([
                    'status' => 200,
                    'message' => "The permission has been updated!",
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
        $permission = Permission::find($id);

        $used = Role::join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
            ->where('permission_id', $permission->id)->get();

        if ($used->count() > 0) {
            return response()->json([
                'status' => 400,
                'message' => 'The "' . $permission->name . '" permission cannot be deleted, because it is currently in use.'
            ]);
        }

        DB::beginTransaction();
        try {
            $permission->delete();
            $permission->labelPermissions()->detach();

            return response()->json([
                'status' => 200,
                'message' => "The $permission->name permission has been deleted!"
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

<?php

namespace App\Http\Controllers\Dashboard\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\LabelPermission as Labels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class LabelPermissionController extends Controller
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
        return view('dashboard.superadmin.permissions.label.index', [
            'labels' => Labels::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|alpha_space|min:3|unique:label_permissions,title'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                Labels::create([
                    'title' => $request->title,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "New label permission has been added!",
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
        $label = Labels::find($id);

        if (request()->ajax()) {
            if ($label) {
                return response()->json([
                    'status' => 200,
                    'data' => $label
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Label of permissions data not found!'
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
            'title' => 'required|alpha_space|min:3|unique:label_permissions,title,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            try {
                $label = Labels::find($id);

                $label->title = $request->input('title');

                if ($label->isDirty()) {
                    $label->update();

                    return response()->json([
                        'status' => 200,
                        'message' => "The permission labels has been updated!",
                    ]);
                } else {
                    return response()->json([
                        'status' => 201,
                        'message' => "Oops, The permission labels no change!",
                    ]);
                }
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
        $label = Labels::find($id);

        DB::beginTransaction();
        try {

            $uses = Permission::join('group_permissions', 'group_permissions.permission_id', '=', 'permissions.id')
                ->where('group_permissions.label_permission_id', $label->id)
                ->get();

            if ($uses->count() > 0) {
                return response()->json([
                    'status' => 400,
                    'title' => "Oops!",
                    'message' => "The $label->title cannot be deleted, because it is currently in use."
                ]);
            } else {
                $label->delete();

                return response()->json([
                    'status' => 200,
                    'message' => "The $label->title has been deleted!"
                ]);
            }
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

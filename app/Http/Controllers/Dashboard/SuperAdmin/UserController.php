<?php

namespace App\Http\Controllers\Dashboard\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create user', ['only' => 'store']);
        $this->middleware('permission:edit user', ['only' => 'update']);
        $this->middleware('permission:delete user', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.superadmin.users.index', [
            'users' => User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->orderBy('model_has_roles.role_id', 'asc')->where('users.id', '!=', Auth::id())->get(),
            'roles' => Role::select('id', 'name')->orderBy('id', 'asc')->get(),
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
            'name' => 'required|alpha_space|max:20|min:3',
            'role' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:16|confirmed',
            'phone' => 'required|numeric|digits_between:11,13',
            'photo' => 'image|mimes:jpg,png,jpeg,gif|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction();
            try {
                // Configure user photo profile
                if ($request->hasFile('photo')) {
                    $path = 'assets/dashboard/img/users/';
                    $photo = $request->file('photo');
                    $newPhoto = uniqid('USER-') . '.' . $photo->extension();
                    // Resize
                    $resize = Image::make($photo->path());
                    $resize->fit(1000, 1000)->save($path . '/' . $newPhoto);
                }

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'photo' => $newPhoto ?? 'avatar.png',
                    'phone' => $request->phone,
                ]);

                $user->assignRole($request->role);

                return response()->json([
                    'status' => 200,
                    'message' => 'User has been created!'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 401,
                    'title' => 'an error occurred while creating data',
                    'message' => "message: $th"
                ]);
            } finally {
                DB::commit();
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
        $user = User::with('roles')->find($id);

        if (request()->ajax()) {
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'data' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'data' => 'User data not found!'
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
            'name' => 'required|alpha_space|max:20|min:3',
            'role' => 'required',
            'phone' => 'required|numeric|digits_between:11,13',
            'photo' => 'image|mimes:jpg,png,jpeg,gif|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction();
            try {
                $user = User::find($id);

                if ($request->hasFile('photo')) {
                    $path = 'assets/dashboard/img/users/';
                    // Jika mau ganti foto maka hapus foto lama ganti baru
                    if (File::exists($path . $user->photo)) {
                        File::delete($path . $user->photo);
                    }
                    $photo = $request->file('photo');
                    $newPhoto = uniqid('USER-') . '.' . $photo->extension();
                    // Resize
                    $resize = Image::make($photo->path());
                    $resize->fit(1000, 1000)->save($path . '/' . $newPhoto);
                    // insert
                    $user->photo = $newPhoto;
                }

                $user->name = $request->input('name');
                $user->phone = $request->input('phone');
                $user->syncRoles($request->role);

                $user->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'User updated successfully!'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 401,
                    'title' => 'an error occurred while updating data',
                    'message' => "Message: $th"
                ]);
            } finally {
                DB::commit();
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
        $user = User::find($id);

        DB::beginTransaction();
        try {
            $path = 'assets/dashboard/img/users/';
            if (File::exists($path . $user->photo)) {
                File::delete($path . $user->photo);
            }

            $user->removeRole($user->roles->first());
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 400,
                'title' => 'an error occurred while deleting data',
                'message' => "Message: $th"
            ]);
        } finally {
            DB::commit();
        }
    }
}

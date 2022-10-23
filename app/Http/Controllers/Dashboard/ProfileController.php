<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.profile');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'image|mimes:jpg,png,jpeg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' =>  400,
                'message' => $validator->errors()->toArray(),
            ]);
        } else {
            try {
                $user = User::find(Auth::id());

                if ($request->base64image || $request->base64image != '0') {
                    $path = 'assets/dashboard/img/users/';
                    // PECAHIN FILE INPUT DARI BASE64IMAGE
                    $img_parts = explode(";base64", $request->base64image);
                    $img_type_aux = explode("image/", $img_parts[0]);
                    $img_type = $img_type_aux[1];
                    $img_base64 = base64_decode($img_parts[1]);
                    $filename = uniqid('USER-') . '.' . $img_type;
                    $file = $path . $filename;
                    $upload = file_put_contents($file, $img_base64);
                }

                if (!$upload) {
                    return response()->json([
                        'status' =>  401,
                        'message' => "an error occurred while updating data!",
                    ]);
                } else {
                    $oldPicture = $user->getAttributes()['photo'];
                    if ($oldPicture != '') {
                        if (File::exists($path . $oldPicture)) {
                            File::delete($path . $oldPicture);
                        }
                    }

                    $updated = $user->update([
                        'photo' => $filename,
                    ]);

                    if ($updated) {
                        return response()->json([
                            'status' => 200,
                            'message' => "Your picture profile has been updated!",
                        ]);
                    } else {
                        return response()->json([
                            'status' => 401,
                            'message' => "an error occurred while updating data!",
                        ]);
                    }
                }
            } catch (\Illuminate\Database\QueryException $th) {
                $msg = $th->getMessage();

                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            } catch (Exception $th) {
                $msg = $th->getMessage();

                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_space|max:20|min:3',
            'phone' => 'required|numeric|digits_between:11,13',
            'bio' => 'nullable|string|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray(),
            ]);
        } else {

            try {
                $user = User::find(Auth::id());

                if ($user) {
                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->bio = $request->bio;

                    if ($user->isDirty()) {
                        $user->update();

                        return response()->json([
                            'status' => 200,
                            'message' => "Your profile has been updated!",
                        ]);
                    } else {
                        return response()->json([
                            'status' => 404,
                            'message' => "No Changes!",
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => "Your data not found!",
                    ]);
                }
            } catch (\Illuminate\Database\QueryException $th) {
                $msg = $th->getMessage();

                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            } catch (Exception $th) {
                $msg = $th->getMessage();

                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            }
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpass' => [
                'required', 'string', 'min:8', 'max:16',
                function ($attr, $val, $fail) {
                    if (!Hash::check($val, Auth::user()->password)) {
                        $fail("The current password is incorrect!");
                    }
                }
            ],
            'newpass' => ['required', 'string', 'min:8', 'max:16'],
            'confirmpass' => ['required', 'string', 'min:8', 'max:16', 'same:newpass'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction();
            try {
                $user = User::find(Auth::id());

                if ($user) {
                    $user->password = Hash::make($request->newpass);

                    if ($user->isDirty()) {
                        $user->update();

                        return response()->json([
                            'status' => 200,
                            'message' => "Your password has been updated!",
                        ]);
                    } else {
                        return response()->json([
                            'status' => 201,
                            'message' => "No changes!",
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 201,
                        'message' => "Your data not found!",
                    ]);
                }
            } catch (\Illuminate\Database\QueryException $th) {
                DB::rollBack();

                $msg = $th->getMessage();
                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            } catch (Exception $th) {
                DB::rollBack();

                $msg = $th->getMessage();
                if (isset($th->errorInfo[2])) {
                    $msg = $th->errorInfo[2];
                }

                return response()->json([
                    'status' => 401,
                    'message' => "an error occurred while updating data!\nMessage: $msg",
                ]);
            } finally {
                DB::commit();
            }
        }
    }
}

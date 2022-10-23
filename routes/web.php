<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function() {
    return redirect()->route('login');
});

Auth::routes();
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'web']], function () {
    Route::get('/', [App\Http\Controllers\Dashboard\IndexController::class, 'dashboard'])->name('dashboard');
    Route::get("profile", [App\Http\Controllers\Dashboard\ProfileController::class, 'index'])->name('profile.index');
    Route::put("profile/update-password", [App\Http\Controllers\Dashboard\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::put("profile/update-profile", [App\Http\Controllers\Dashboard\ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::post("profile/update-photo", [App\Http\Controllers\Dashboard\ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');

    Route::get('/fetch-edit-permission/{id}', [App\Http\Controllers\Dashboard\SuperAdmin\RoleController::class, 'fetchPermission'])->name('permissions.fetchEdit');
    Route::resource('/roles', App\Http\Controllers\Dashboard\SuperAdmin\RoleController::class)
        ->except('edit');
    Route::resource('/permissions', App\Http\Controllers\Dashboard\SuperAdmin\PermissionContoller::class)
        ->except('edit');
    Route::resource('/label-permissions', App\Http\Controllers\Dashboard\SuperAdmin\LabelPermissionController::class)
        ->except('edit');

    Route::resource('/users', App\Http\Controllers\Dashboard\SuperAdmin\UserController::class);
});

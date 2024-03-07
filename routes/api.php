<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageUsers;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->middleware(['guest'])->name('user.login');
Route::post('/register', [ManageUsers::class, 'register'])->middleware(['guest'])->name('user.register');

Route::middleware('auth:api')->group(function () {
    Route::get('/view_all_users', [ManageUsers::class, 'view_all_users'])->name('auth.view_all_users');
    Route::patch('/update', [ManageUsers::class, 'update'])->name('auth.update_profile');
    Route::post('/update_role', [ManageUsers::class, 'update_role'])->name('auth.update_role');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::delete('/delete_user', [ManageUsers::class, 'delete_user'])->name('auth.admin.delete_user');
});


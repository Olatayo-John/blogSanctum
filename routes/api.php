<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

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


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('user', UserController::class);

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put  ('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::apiResource('blog', BlogController::class);

    Route::apiResource('comment', CommentController::class);

    // Route::post('auth/regenerate-token', [AuthController::class, 'regenerateToken'])->name('regenerate-token');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('auth/logout-all-devices', [AuthController::class, 'logoutAllDeviice'])->name('logout-all-devices');
});

Route::middleware('guest:sanctum')->group(function () {

    Route::post('auth/login', [AuthController::class, 'login'])->name('login');

    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
});

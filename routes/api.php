<?php

use App\Http\Controllers\Auth as Auth;
use App\Http\Controllers\Api as Api;
use Illuminate\Support\Facades\Route;

Route::post('register', [Auth\RegistrationController::class, 'store']);
Route::post('login', [Auth\LoginController::class, 'store']);
Route::delete('logout', [Auth\LoginController::class, 'destroy'])->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('tasks', Api\TaskController::class, ['except' => ['create', 'edit']]);
    Route::put('tasks/{task}/complete', [Api\TaskController::class, 'complete']);
});

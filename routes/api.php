<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Middleware\HandleCors;

Route::middleware([HandleCors::class])->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Rutas para usuarios
Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    // Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/delete-account', [UserController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('statistics', [UserController::class, 'statistics']);
});


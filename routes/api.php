<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Rutas para usuarios
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']); // Listar todos los usuarios
    Route::get('/users/{id}', [UserController::class, 'show']); // Mostrar un usuario específico
    Route::post('/users', [UserController::class, 'store']); // Crear un nuevo usuario
    Route::put('/users/{id}', [UserController::class, 'update']); // Actualizar un usuario
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Eliminar un usuario
});



Route::middleware('auth:sanctum')->group( function(){
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('/statistics', [UserController::class, 'statistics']);

});

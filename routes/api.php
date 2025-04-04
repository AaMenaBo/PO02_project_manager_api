<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiController::class, 'register']); //Registrar Usuario
Route::post('/login', [ApiController::class, 'login']); //Iniciar Sesion

//Rutas para majoe de proyectos 
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
});

//rutas para manejo de tareas
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});

//Rutas para manejo de usuarios
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::delete('/logout', [ApiController::class, 'logout']); //Cerrar Sesion
    Route:: get('/profile', [ApiController::class, 'profile']); //Obtener perfil
});

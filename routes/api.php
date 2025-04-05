<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiController::class, 'register']); //Registrar Usuario
Route::post('/login', [ApiController::class, 'login']); //Iniciar Sesion

//Rutas para majoe de proyectos 
Route::group(['prefix' => 'project', 'middleware' => 'auth:sanctum'], function(){
    Route::get('/list', [ProjectController::class, 'index']); //Listar Proyectos
    Route::get('/show/{project}', [ProjectController::class, 'show']); //Mostrar Proyecto
    Route::get('/add-user/{user}/{project}', [ProjectController::class, 'addUser']); //Agregar Usuario a Proyecto
    Route::post('/store', [ProjectController::class, 'store']); //Crear Proyecto
    Route::put('/update', [ProjectController::class, 'update']); //Actualizar Proyecto
    Route::delete('/remove-user/{user}/{project}', [ProjectController::class, 'removeUser']); //Eliminar Usuario de Proyecto
    Route::delete('/destroy/{project}', [ProjectController::class, 'destroy']); //Eliminar Proyecto
});

//Rutas para manejo de sesiones
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::delete('/logout', [ApiController::class, 'logout']); //Cerrar Sesion
    Route:: get('/profile', [ApiController::class, 'profile']); //Obtener perfil
});

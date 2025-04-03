<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('projects')->group(function () {
    Route::get('/', 'ProjectController@index');
    Route::post('/', 'ProjectController@store');
    Route::get('/{id}', 'ProjectController@show');
    Route::put('/{id}', 'ProjectController@update');
    Route::delete('/{id}', 'ProjectController@destroy');
});

Route::prefix('tasks')->group(function () {
    Route::get('/', 'TaskController@index');
    Route::post('/', 'TaskController@store');
    Route::get('/{id}', 'TaskController@show');
    Route::put('/{id}', 'TaskController@update');
    Route::delete('/{id}', 'TaskController@destroy');
});

Route::prefix('users')->group(function () {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@store');
    Route::get('/{id}', 'UserController@show');
    Route::put('/{id}', 'UserController@update');
    Route::delete('/{id}', 'UserController@destroy');
});
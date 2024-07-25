<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Task\TaskController;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'store']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy']);
    Route::get('user', [UserController::class, 'index']);
    Route::prefix('task')->group(function (){
        Route::get('/{id?}', [TaskController::class, 'index']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'delete']);
        Route::get('/search', [TaskController::class, 'search']);
        Route::post('/create', [TaskController::class, 'store']);
        Route::get('/export', [TaskController::class, 'export']);
    });
});

<?php

use App\Http\Controllers\FriendsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/user.php';



Route::get('/beranda', [TodoController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::get('/todos', [TodoController::class, 'show']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
    Route::post('/todos/edit/{id}', [TodoController::class, 'edit']);
    Route::get('/goals', [GoalsController::class, 'show']);
    Route::post('/goals', [GoalsController::class, 'store']);
    Route::delete('/goals/{id}', [GoalsController::class, 'destroy']);
    Route::apiResource('friends', FriendsController::class);
});

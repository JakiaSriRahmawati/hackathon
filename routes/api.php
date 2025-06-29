<?php

use App\Http\Controllers\FriendsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/user.php';



Route::get('/beranda', [TodoController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/todos', [TodoController::class, 'store']);
    Route::post('/goals', [GoalsController::class, 'store']);
    Route::apiResource('friends', FriendsController::class);
});

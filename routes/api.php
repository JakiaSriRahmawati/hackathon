<?php

use App\Http\Controllers\FriendsController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/user.php';



Route::post('todos', [TodoController::class, 'store']);
Route::get('/todos', [TodoController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('friends', FriendsController::class);
});

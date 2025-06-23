<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
});
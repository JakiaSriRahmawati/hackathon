<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/profile/{id}', [UserController::class, 'profileById']);
    Route::post('/user/upload-profile-picture', [UserController::class, 'uploadProfilePicture']);

});
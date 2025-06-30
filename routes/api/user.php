<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

Route::get('/profile/{id}', [UserController::class, 'profileById']);
Route::middleware('api.auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/upload-profile-picture', [UserController::class, 'uploadProfilePicture']);
});
<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/user.php';



Route::post('todos', [TodoController::class, 'store']);

Route::get('test', function (){

return "testing berhasil";
});
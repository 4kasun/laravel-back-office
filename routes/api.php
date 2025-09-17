<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;

Route::post('/login', [AuthController::class, 'login']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('posts', BlogController::class);
Route::post('/posts/{id}/priority', [BlogController::class, 'updatePriority']);
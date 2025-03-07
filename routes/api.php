<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum'); // Route för att registrera användare
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Route för att logga ut
Route::put('/user/{id}', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum'); // Route för att logga ut


// Public routes
Route::post('/login', [AuthController::class, 'login']); // Route för att logga in
Route::post('/register', [AuthController::class, 'register']); // Route för att registrera användare

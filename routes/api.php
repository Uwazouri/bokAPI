<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikedBooksController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;

// Protected routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum'); // Route för att registrera användare
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Route för att logga ut
Route::put('/user/{id}', [UserController::class, 'updateProfile'])->middleware('auth:sanctum'); // Route för att logga ut
Route::put('/user/{id}/deleteavatar', [UserController::class, 'deleteAvatar'])->middleware('auth:sanctum'); // Route för ta bort avatar ur storage
Route::post('/likedbooks', [LikedBooksController::class, 'addToLikedBooks'])->middleware(('auth:sanctum')); // Route för att lägga till bok i gillade böcker för inloggad användare
Route::delete('/likedbooks/{id}', [LikedBooksController::class, 'removeFromLikedBooks'])->middleware(('auth:sanctum')); // Route för att ta bort bok från gillade böcker för inloggad användare
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/review', [ReviewController::class, 'store']);
    Route::put('/review/{id}', [ReviewController::class, 'update']);
    Route::delete('/review/{id}', [ReviewController::class, 'destroy']);
});

// Public routes
Route::post('/login', [AuthController::class, 'login']); // Route för att logga in
Route::post('/register', [AuthController::class, 'register']); // Route för att registrera användare
Route::get('/user/{id}', [UserController::class, 'getUserInfo']); // Hämta publik info för en användare (id, namn, bio, current_read, avatar)
Route::get('/user/{id}/likedbooks', [LikedBooksController::class, 'getLikedBooks']); // Hämta publik info för en användare (id, namn, bio, current_read, avatar)
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/latest', [ReviewController::class, 'getLatestReviews']);
Route::get('/review/{id}', [ReviewController::class, 'show']);
Route::get('book/{id}/reviews', [ReviewController::class, 'getReviewsForBook']);
Route::get('book/{bookId}/likes', [LikedBooksController::class, 'getNumberOfLikes']);
Route::get('user/{id}/reviews', [ReviewController::class, 'getReviewsByUser']);

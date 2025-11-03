<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup']);
    Route::post('/signin', [UserController::class, 'signin']);

    Route::middleware(['auth.jwt'])->group(function () {

        Route::get('/me', [UserController::class, 'me']);
        Route::get('/logout', [UserController::class, 'logout']);
    });
});
Route::prefix('books')->group(function () {
    Route::get('/collections', [BookController::class, 'collections']);
    Route::get('/sales', [BookController::class, 'sales']);
});

<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'signup']);
    Route::post('/signin', [UserController::class, 'signin']);
});


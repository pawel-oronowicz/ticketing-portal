<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/');
});

Route::middleware('guest')->group(function () {
    Route::post('/register', RegisterUserController::class);
    Route::post('/login', LoginUserController::class);
});


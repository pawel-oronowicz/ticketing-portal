<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/login', LoginUserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/');

    // Companies
    Route::get('/companies', [CompanyController::class, 'index']);
});

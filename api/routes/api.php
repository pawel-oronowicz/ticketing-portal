<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/login', LoginUserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/');

    // Enums
    Route::get('/enums', [EnumController::class, 'index']);

    // Companies
    Route::get('/companies', [CompanyController::class, 'index']);

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
});
